import { User, AuthResponse, LoginUrlResponse, RegisterUrlResponse } from '../entities/auth';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:5002/api';

class AuthService {
  private static instance: AuthService;
  private token: string | null = null;
  private user: User | null = null;

  private constructor() {
    // Load token from localStorage on initialization
    try {
      const storage = localStorage.getItem('auth-storage');
      if (storage) {
        const parsed = JSON.parse(storage) as { state?: { token?: string } };
        this.token = parsed.state?.token ?? null;
      }
    } catch {
      this.token = null;
    }
    console.log('AuthService initialized - token from localStorage:', this.token);
  }

  static getInstance(): AuthService {
    if (!AuthService.instance) {
      AuthService.instance = new AuthService();
    }
    return AuthService.instance;
  }

  /**
   * Get login URL from the backend (which redirects to auth portal)
   */
  async getLoginUrl(returnUrl?: string): Promise<string> {
    const params = new URLSearchParams();
    if (returnUrl) {
      params.append('return_url', returnUrl);
    }

    const response = await fetch(`${API_BASE_URL}/auth/login-url?${params.toString()}`);
    if (!response.ok) {
      throw new Error('Failed to get login URL');
    }

    const data: LoginUrlResponse = await response.json();
    if (!data.success) {
      throw new Error('Failed to get login URL');
    }

    return data.data.login_url;
  }

  /**
   * Get register URL from the backend (which redirects to auth portal)
   */
  async getRegisterUrl(returnUrl?: string): Promise<string> {
    const params = new URLSearchParams();
    if (returnUrl) {
      params.append('return_url', returnUrl);
    }

    const response = await fetch(`${API_BASE_URL}/auth/register-url?${params.toString()}`);
    if (!response.ok) {
      throw new Error('Failed to get register URL');
    }

    const data: RegisterUrlResponse = await response.json();
    if (!data.success) {
      throw new Error('Failed to get register URL');
    }

    return data.data.register_url;
  }

  /**
   * Handle callback from auth portal with token
   */
  async handleAuthCallback(token: string): Promise<User> {
    const response = await fetch(`${API_BASE_URL}/auth/callback?token=${encodeURIComponent(token)}`);
    if (!response.ok) {
      throw new Error('Failed to process authentication callback');
    }

    const data: AuthResponse = await response.json();
    if (!data.success || !data.data) {
      throw new Error(data.message || 'Authentication failed');
    }

    // Store token and user data
    this.token = data.data.token;
    this.user = data.data.user;
    const authState = {
      state: {
        token: this.token,
        isAuthenticated: true,
        user: this.user,
      },
      version: 0,
    };
    localStorage.setItem('auth-storage', JSON.stringify(authState));

    return this.user;
  }

  /**
   * Get current authenticated user
   */
  async getCurrentUser(): Promise<User | null> {
    console.log('getCurrentUser called - current token:', this.token);

    if (!this.token) {
      console.log('No token available, returning null');
      return null;
    }

    try {
      console.log('Making request to /auth/me with token');
      const response = await fetch(`${API_BASE_URL}/auth/me`, {
        headers: {
          'Authorization': `Bearer ${this.token}`,
          'Content-Type': 'application/json'
        }
      });

      console.log('Response status:', response.status);

      if (!response.ok) {
        if (response.status === 401) {
          // Token is invalid or expired - but don't logout, just return null
          console.log('401 response - token validation failed');
          return null;
        }
        throw new Error('Failed to get current user');
      }

      const data = await response.json();
      console.log('Response data:', data);

      if (data.success && data.data?.user) {
        this.user = data.data.user;
        return this.user;
      }

      return null;
    } catch (error) {
      console.error('Error getting current user:', error);
      console.log('Error occurred - returning null without clearing token');
      return null;
    }
  }

  /**
   * Update user preferences
   */
  async updatePreferences(preferences: Record<string, unknown>): Promise<void> {
    if (!this.token) {
      throw new Error('Not authenticated');
    }

    const response = await fetch(`${API_BASE_URL}/auth/preferences`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ preferences })
    });

    if (!response.ok) {
      throw new Error('Failed to update preferences');
    }

    const data = await response.json();
    if (!data.success) {
      throw new Error(data.message || 'Failed to update preferences');
    }
  }

  /**
   * Logout user
   */
  async logout(): Promise<void> {
    console.log('logout() called - but logout disabled for debugging');
    // Logout disabled - keeping token and user data
    // this.token = null;
    // this.user = null;
    // localStorage.removeItem('auth-storage');
  }

  /**
   * Check if user is authenticated
   */
  isAuthenticated(): boolean {
    return !!this.token;
  }

  /**
   * Get current token
   */
  getToken(): string | null {
    return this.token;
  }

  /**
   * Get current user (cached)
   */
  getUser(): User | null {
    return this.user;
  }

  /**
   * Redirect to login
   */
  async redirectToLogin(returnUrl?: string): Promise<void> {
    const loginUrl = await this.getLoginUrl(returnUrl || window.location.href);
    console.log('REDIRECT DISABLED FOR DEBUG - Would redirect to:', loginUrl);
    console.log('Current token:', this.token);
    console.log('Is authenticated:', this.isAuthenticated());
    console.log('Current user:', this.user);
    // window.location.href = loginUrl; // DISABLED FOR DEBUGGING
    console.log('Redirect to login prevented. URL would be:', loginUrl);
  }

  /**
   * Redirect to register
   */
  async redirectToRegister(returnUrl?: string): Promise<void> {
    const registerUrl = await this.getRegisterUrl(returnUrl || window.location.href);
    window.location.href = registerUrl;
  }

  /**
   * Check if user has admin role
   */
  isAdmin(): boolean {
    return this.user?.role === 'admin';
  }

  /**
   * Check if user has specific role
   */
  hasRole(role: string): boolean {
    if (this.user?.role === 'admin') {
      return true; // Admin has all roles
    }
    return this.user?.role === role;
  }
}

export default AuthService.getInstance();
