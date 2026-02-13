// Keep auth header logic out of React component modules to satisfy react-refresh rules.

let tokenProvider: (() => Promise<string | null>) | null = null;

export const setTokenProvider = (
  provider: (() => Promise<string | null>) | null,
) => {
  tokenProvider = provider;
};

export const getAuthHeaders = async (): Promise<HeadersInit> => {
  const headers: Record<string, string> = {
    "Content-Type": "application/json",
  };

  if (tokenProvider) {
    try {
      const token = await tokenProvider();
      if (token) headers["Authorization"] = `Bearer ${token}`;
      return headers;
    } catch {
      // Fall through to auth-storage
    }
  }

  let token: string | null = null;
  try {
    const storage = localStorage.getItem("auth-storage");
    if (storage) {
      const parsed = JSON.parse(storage) as { state?: { token?: string } };
      token = parsed.state?.token ?? null;
    }
  } catch {
    // ignore parse error
  }

  if (token) headers["Authorization"] = `Bearer ${token}`;
  return headers;
};
