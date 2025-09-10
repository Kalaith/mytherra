import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { useAuth0 } from '@auth0/auth0-react';
import { User } from '../entities/auth';

interface AuthContextType {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: () => Promise<void>;
  register: () => Promise<void>;
  logout: () => Promise<void>;
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
  const { 
    isAuthenticated: auth0IsAuthenticated, 
    isLoading: auth0IsLoading, 
    user: auth0User, 
    loginWithRedirect, 
    logout: auth0Logout,
    getAccessTokenSilently 
  } = useAuth0();
  
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (!auth0IsLoading && auth0IsAuthenticated && auth0User) {
      initializeUser();
    } else if (!auth0IsLoading && !auth0IsAuthenticated) {
      setUser(null);
      setIsLoading(false);
    }
  }, [auth0IsLoading, auth0IsAuthenticated, auth0User]);

  // Set up token provider for API calls
  useEffect(() => {
    if (auth0IsAuthenticated) {
      // Set the token provider for API calls
      setTokenProvider(getAccessTokenSilently);
    }
  }, [auth0IsAuthenticated, getAccessTokenSilently]);

  const initializeUser = async () => {
    try {
      setIsLoading(true);
      
      // Get the local user profile from our backend
      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/auth/verify-user`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${await getAccessTokenSilently()}`
        },
        body: JSON.stringify({
          auth0_user: auth0User
        })
      });

      if (response.ok) {
        const userData = await response.json();
        setUser(userData.data);
      } else {
        console.error('Failed to get user profile');
      }
    } catch (error) {
      console.error('Failed to initialize user:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async () => {
    await loginWithRedirect();
  };

  const register = async () => {
    await loginWithRedirect({
      authorizationParams: {
        screen_hint: 'signup'
      }
    });
  };

  const logout = async () => {
    setUser(null);
    auth0Logout({ 
      logoutParams: { 
        returnTo: window.location.origin + (import.meta.env.VITE_BASE_PATH || '') 
      } 
    });
  };

  const refreshUser = async () => {
    if (auth0IsAuthenticated) {
      await initializeUser();
    }
  };

  const updatePreferences = async (preferences: any) => {
    if (!user) return;
    
    try {
      const response = await fetch(`${import.meta.env.VITE_API_BASE_URL}/users/${user.id}/preferences`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${await getAccessTokenSilently()}`
        },
        body: JSON.stringify({ preferences })
      });

      if (response.ok) {
        const updatedUser = await response.json();
        setUser(updatedUser.data);
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
        isAuthenticated: auth0IsAuthenticated && !!user,
        isLoading: auth0IsLoading || isLoading,
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

export const setTokenProvider = (provider: () => Promise<string>) => {
  tokenProvider = provider;
};

export const getAuthHeaders = async (): Promise<HeadersInit> => {
  const headers: HeadersInit = {
    'Content-Type': 'application/json',
  };
  
  if (tokenProvider) {
    try {
      const token = await tokenProvider();
      headers['Authorization'] = `Bearer ${token}`;
    } catch (error) {
      console.error('Failed to get auth token:', error);
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
