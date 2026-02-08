import { createContext } from 'react';
import type { User } from '../entities/auth';

export type Preferences = Record<string, unknown>;

export interface AuthContextType {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: () => Promise<void>;
  register: () => Promise<void>;
  logout: () => void;
  refreshUser: () => Promise<void>;
  updatePreferences: (preferences: Preferences) => Promise<void>;
  isAdmin: () => boolean;
  hasRole: (role: string) => boolean;
}

export const AuthContext = createContext<AuthContextType | undefined>(undefined);

