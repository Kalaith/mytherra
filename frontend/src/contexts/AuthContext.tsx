import React, { createContext, useContext, useEffect, useState, ReactNode } from 'react';
import { User } from '../entities/auth';
import authService from '../services/authService';

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
  const [user, setUser] = useState<User | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    // Check for authentication on mount
    initializeAuth();
    
    // Check for auth callback in URL
    handleAuthCallback();
  }, []);

  const initializeAuth = async () => {
    try {
      if (authService.isAuthenticated()) {
        const currentUser = await authService.getCurrentUser();
        setUser(currentUser);
      }
    } catch (error) {
      console.error('Failed to initialize auth:', error);
      // Clear any invalid tokens
      authService.logout();
    } finally {
      setIsLoading(false);
    }
  };

  const handleAuthCallback = async () => {
    // Check if we're on the auth callback route
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');
    
    if (token) {
      try {
        setIsLoading(true);
        const user = await authService.handleAuthCallback(token);
        setUser(user);
        
        // Remove token from URL
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.delete('token');
        window.history.replaceState({}, '', newUrl.toString());
        
      } catch (error) {
        console.error('Auth callback failed:', error);
        // Redirect to login on failure
        await authService.redirectToLogin();
      } finally {
        setIsLoading(false);
      }
    }
  };

  const login = async () => {
    await authService.redirectToLogin();
  };

  const register = async () => {
    await authService.redirectToRegister();
  };

  const logout = async () => {
    await authService.logout();
    setUser(null);
  };

  const refreshUser = async () => {
    try {
      const currentUser = await authService.getCurrentUser();
      setUser(currentUser);
    } catch (error) {
      console.error('Failed to refresh user:', error);
      setUser(null);
    }
  };

  const updatePreferences = async (preferences: any) => {
    await authService.updatePreferences(preferences);
    // Refresh user data to get updated preferences
    await refreshUser();
  };

  const isAdmin = (): boolean => {
    return authService.isAdmin();
  };

  const hasRole = (role: string): boolean => {
    return authService.hasRole(role);
  };

  const value: AuthContextType = {
    user,
    isAuthenticated: !!user,
    isLoading,
    login,
    register,
    logout,
    refreshUser,
    updatePreferences,
    isAdmin,
    hasRole
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
