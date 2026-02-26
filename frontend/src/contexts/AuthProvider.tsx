import React, { useCallback, useEffect, useMemo, useState, type ReactNode } from 'react';
import type { User } from '../entities/auth';
import { AuthContext, type Preferences } from './authContext';
import { setTokenProvider } from './authHeaders';
import { apiClient } from '../api/apiClient';

interface AuthProviderProps {
  children: ReactNode;
}

interface PortalUrlResponse {
  success: boolean;
  data?: {
    login_url?: string;
    register_url?: string;
  };
}

interface SessionResponse {
  success: boolean;
  data?: {
    user?: User;
  } & Partial<User>;
}

const isAxiosLikeError = (error: unknown): error is { response?: { status?: number } } => {
  return typeof error === 'object' && error !== null;
};

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(() => {
    try {
      const storage = localStorage.getItem('auth-storage');
      if (storage) {
        const parsed = JSON.parse(storage) as { state?: { token?: string } };
        if (parsed.state?.token) return parsed.state.token;
      }
    } catch {
      // ignore parse error
    }
    return null;
  });
  const [isLoading, setIsLoading] = useState(true);

  const login = useCallback(async () => {
    try {
      const response = await apiClient.get<PortalUrlResponse>(
        `/auth/login-url?return_url=${encodeURIComponent(window.location.href)}`
      );
      const data = response.data;
      const loginUrl = data.data?.login_url;
      if (data.success && loginUrl) window.location.href = loginUrl;
    } catch (error) {
      console.error('Failed to get login URL', error);
    }
  }, []);

  const register = useCallback(async () => {
    try {
      const response = await apiClient.get<PortalUrlResponse>(
        `/auth/register-url?return_url=${encodeURIComponent(window.location.href)}`
      );
      const data = response.data;
      const registerUrl = data.data?.register_url;
      if (data.success && registerUrl) window.location.href = registerUrl;
    } catch (error) {
      console.error('Failed to get register URL', error);
    }
  }, []);

  const initializeUser = useCallback(async (authToken: string | null) => {
    if (!authToken) return;

    try {
      setIsLoading(true);

      const response = await apiClient.get<SessionResponse>('/auth/session', {
        headers: {
          Authorization: `Bearer ${authToken}`
        }
      });

      const data = response.data;
      const maybeUser =
        data.data && 'user' in data.data ? data.data.user : (data.data as User | undefined);
      if (data.success && maybeUser) setUser(maybeUser);
    } catch (error: unknown) {
      if (isAxiosLikeError(error) && error.response?.status === 401) {
        console.warn('Backend rejected token (401). Showing login URL instead of redirect.');
      } else {
        console.error('Failed to initialize user:', error);
      }
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const urlToken = urlParams.get('token');

    if (urlToken) {
      setToken(urlToken);

      const authState = {
        state: {
          token: urlToken,
          isAuthenticated: true,
          user: null,
        },
        version: 0,
      };
      localStorage.setItem('auth-storage', JSON.stringify(authState));

      window.history.replaceState({}, document.title, window.location.pathname);
      void initializeUser(urlToken);
      return;
    }

    if (token) {
      void initializeUser(token);
    } else {
      setIsLoading(false);
    }
  }, [initializeUser, token]);

  useEffect(() => {
    if (token) setTokenProvider(async () => token);
    else setTokenProvider(null);
  }, [token]);

  const logout = useCallback(() => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('auth-storage');
  }, []);

  const refreshUser = useCallback(async () => {
    if (token) await initializeUser(token);
  }, [initializeUser, token]);

  const updatePreferences = useCallback(
    async (preferences: Preferences) => {
      if (!user || !token) return;

      try {
        const response = await apiClient.put(
          '/auth/preferences',
          { preferences },
          {
            headers: {
              Authorization: `Bearer ${token}`
            }
          }
        );

        if (response.status >= 200 && response.status < 300) {
          setUser(prev => (prev ? { ...prev, game_preferences: preferences } : null));
        }
      } catch (error) {
        console.error('Failed to update preferences:', error);
      }
    },
    [token, user]
  );

  const isAdmin = useCallback((): boolean => user?.role === 'admin', [user?.role]);

  const hasRole = useCallback(
    (role: string): boolean => (user?.role === 'admin' ? true : user?.role === role),
    [user?.role]
  );

  const value = useMemo(
    () => ({
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
    }),
    [
      hasRole,
      isAdmin,
      isLoading,
      login,
      logout,
      refreshUser,
      register,
      token,
      updatePreferences,
      user,
    ]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
