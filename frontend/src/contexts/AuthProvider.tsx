import React, {
  useCallback,
  useEffect,
  useMemo,
  useState,
  type ReactNode,
} from "react";
import type { User } from "../entities/auth";
import { AuthContext, type Preferences } from "./authContext";
import { setTokenProvider } from "./authHeaders";

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

export const AuthProvider: React.FC<AuthProviderProps> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(() => {
    try {
      const storage = localStorage.getItem("auth-storage");
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
      const response = await fetch(
        `${import.meta.env.VITE_API_BASE_URL}/auth/login-url?return_url=${encodeURIComponent(window.location.href)}`,
      );
      const data = (await response.json()) as PortalUrlResponse;
      const loginUrl = data.data?.login_url;
      if (data.success && loginUrl) window.location.href = loginUrl;
    } catch (error) {
      console.error("Failed to get login URL", error);
    }
  }, []);

  const register = useCallback(async () => {
    try {
      const response = await fetch(
        `${import.meta.env.VITE_API_BASE_URL}/auth/register-url?return_url=${encodeURIComponent(window.location.href)}`,
      );
      const data = (await response.json()) as PortalUrlResponse;
      const registerUrl = data.data?.register_url;
      if (data.success && registerUrl) window.location.href = registerUrl;
    } catch (error) {
      console.error("Failed to get register URL", error);
    }
  }, []);

  const initializeUser = useCallback(async (authToken: string | null) => {
    if (!authToken) return;

    try {
      setIsLoading(true);

      const response = await fetch(
        `${import.meta.env.VITE_API_BASE_URL}/auth/session`,
        {
          headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${authToken}`,
          },
        },
      );

      if (!response.ok) {
        if (response.status === 401) {
          console.warn(
            "Backend rejected token (401). Showing login URL instead of redirect.",
          );
        }
        return;
      }

      const data = (await response.json()) as SessionResponse;
      const maybeUser =
        data.data && "user" in data.data
          ? data.data.user
          : (data.data as User | undefined);
      if (data.success && maybeUser) setUser(maybeUser);
    } catch (error) {
      console.error("Failed to initialize user:", error);
    } finally {
      setIsLoading(false);
    }
  }, []);

  useEffect(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const urlToken = urlParams.get("token");

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
      localStorage.setItem("auth-storage", JSON.stringify(authState));

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
    localStorage.removeItem("auth-storage");
  }, []);

  const refreshUser = useCallback(async () => {
    if (token) await initializeUser(token);
  }, [initializeUser, token]);

  const updatePreferences = useCallback(
    async (preferences: Preferences) => {
      if (!user || !token) return;

      try {
        const response = await fetch(
          `${import.meta.env.VITE_API_BASE_URL}/auth/preferences`,
          {
            method: "PUT",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${token}`,
            },
            body: JSON.stringify({ preferences }),
          },
        );

        if (response.ok) {
          setUser((prev) =>
            prev ? { ...prev, game_preferences: preferences } : null,
          );
        }
      } catch (error) {
        console.error("Failed to update preferences:", error);
      }
    },
    [token, user],
  );

  const isAdmin = useCallback(
    (): boolean => user?.role === "admin",
    [user?.role],
  );

  const hasRole = useCallback(
    (role: string): boolean =>
      user?.role === "admin" ? true : user?.role === role,
    [user?.role],
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
    ],
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};
