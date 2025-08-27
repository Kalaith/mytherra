export interface User {
  id: number;
  auth_user_id: number;
  email: string;
  username: string;
  display_name: string;
  divine_influence: number;
  divine_favor: number;
  betting_stats: any;
  game_preferences: any;
  role: string;
  is_active: boolean;
}

export interface AuthResponse {
  success: boolean;
  message: string;
  data?: {
    token: string;
    user: User;
  };
}

export interface LoginUrlResponse {
  success: boolean;
  data: {
    login_url: string;
  };
}

export interface RegisterUrlResponse {
  success: boolean;
  data: {
    register_url: string;
  };
}
