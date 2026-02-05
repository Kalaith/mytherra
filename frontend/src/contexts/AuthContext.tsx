import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { User } from '../entities/auth';

interface AuthContextType {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: () => Promise<void>;
  register: () => Promise<void>;
  logout: () => void; // Changed to synchronous as we just clear local state
  refreshUser: () => Promise<void>;
  updatePreferences: (preferences: any) => Promise<void>;
  isAdmin: () => boolean;
  hasRole: (role: string) => boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

interface AuthProviderProps {
  children: ReactNode;
}

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(() => {
    // Check auth-storage (Zustand/State storage)
    try {
      const storage = localStorage.getItem('auth-storage');
      if (storage) {
        const parsed = JSON.parse(storage);
        if (parsed.state && parsed.state.token) {
          return parsed.state.token;
        }
      }
    } catch (e) {
      console.error('Failed to parse auth-storage', e);
    }
    // Fallback to simple keys
    return localStorage.getItem('token') || localStorage.getItem('auth_token');
  });
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // Check for token in URL (callback from auth portal)
    const urlParams = new URLSearchParams(window.location.search);
    const urlToken = urlParams.get('token');

    if (urlToken) {
      setToken(urlToken);
      setToken(urlToken);

      // Save to auth_storage to match expected format
      const authState = {
        state: {
          token: urlToken,
          isAuthenticated: true,
          user: null // Will be populated after fetch
        },
        version: 0
      };
      localStorage.setItem('auth-storage', JSON.stringify(authState));

      // Also keep simple key for compatibility if needed
      localStorage.setItem('token', urlToken);

      // Clean up URL
      window.history.replaceState({}, document.title, window.location.pathname);
    }

    if (token || urlToken) {
      initializeUser(urlToken || token);
    } else {
      setIsLoading(false);
    }
  }, [token]);

  // Set up token provider for API calls
  useEffect(() => {
    if (token) {
      setTokenProvider(async () => token);
    } else {
      setTokenProvider(null);
    }
  }, [token]);

  const initializeUser = async (authToken: string | null) => {
    if (!authToken) return;

    try {
      setIsLoading(true);

      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/auth/session`, {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${authToken}`
        }
      });

      if (response.ok) {
        const data = await response.json();
        if (data.success && data.data.user) {
          setUser(data.data.user);
        } else {
          // Fallback if structure is different
          setUser(data.data || data);
        }
      } else {
        console.error('Failed to get user profile');
        // If 401, clear token
        if (response.status === 401) {
          // logout(); // DISABLED: Keep session active even if backend validation fails
          console.warn('Backend rejected token (401), but keeping frontend session active.');
        }
      }
    } catch (error) {
      console.error('Failed to initialize user:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async () => {
    try {
      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/auth/login-url?return_url=${encodeURIComponent(window.location.href)}`);
      const data = await response.json();
      if (data.success && data.data.login_url) {
        window.location.href = data.data.login_url;
      }
    } catch (error) {
      console.error("Failed to get login URL", error);
    }
  };

  const register = async () => {
    try {
      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/auth/register-url?return_url=${encodeURIComponent(window.location.href)}`);
      const data = await response.json();
      if (data.success && data.data.register_url) {
        window.location.href = data.data.register_url;
      }
    } catch (error) {
      console.error("Failed to get register URL", error);
    }
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('auth-storage');
    localStorage.removeItem('token');
    localStorage.removeItem('auth_token');
    // window.location.href = '/'; // DISABLED: Prevent redirect loop or exit
    console.log('Logged out (redirect disabled)');
  };

  const refreshUser = async () => {
    if (token) {
      await initializeUser(token);
    }
  };

  const updatePreferences = async (preferences: any) => {
    if (!user || !token) return;

    try {
      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/auth/preferences`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify({ preferences })
      });

      if (response.ok) {
        const updatedUser = await response.json();
        // Update user state with new preferences
        // Assuming the response returns the updated field or we fetch user again
        // For efficiency, we might just merge it locally if we know it succeeded
        // For efficiency, we might just merge it locally if we know it succeeded
        setUser(prev => prev ? { ...prev, game_preferences: preferences } : null);
      }
    } catch (error) {
      console.error('Failed to update preferences:', error);
    }
  };

  const isAdmin = (): boolean => {
    return user?.role === 'admin' || false;
  };

  const hasRole = (role: string): boolean => {
    return user?.role === role || false;
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        isAuthenticated: !!user && !!token,
        isLoading,
        login,
        register,
        logout,
        refreshUser,
        updatePreferences,
        isAdmin,
        hasRole,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

// Token provider function for API calls
let tokenProvider: (() => Promise<string>) | null = null;

export const setTokenProvider = (provider: (() => Promise<string>) | null) => {
  tokenProvider = provider;
};

export const getAuthHeaders = async (): Promise<HeadersInit> => {
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
  };

  if (tokenProvider) {
    try {
      const token = await tokenProvider();
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      }
    } catch (error) {
      console.error('Failed to get auth token:', error);
    }
  } else {
    // Fallback to localStorage if provider not set (e.g. during init)
    let token = localStorage.getItem('token') || localStorage.getItem('auth_token');

    // Check auth-storage if simple keys fail
    if (!token) {
      try {
        const storage = localStorage.getItem('auth-storage');
        if (storage) {
          const parsed = JSON.parse(storage);
          if (parsed.state && parsed.state.token) {
            token = parsed.state.token;
          }
        }
      } catch (e) { /* ignore parse error */ }
    }

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }
  }

  return headers;
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
