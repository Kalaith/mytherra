import { Region } from '../entities/region';
import { Hero } from '../entities/hero';
import { GameEvent } from '../entities/event';
import { Settlement } from '../entities/settlement';
import { Building } from '../entities/building';
import { Landmark } from '../entities/landmark';
import { ResourceNode } from '../entities/resourceNode';
import { DivineBet, SpeculationEvent, BettingOdds } from '../entities/divineBet';
import { getAuthHeaders } from '../contexts/authHeaders';
import { apiClient } from './apiClient';
import { ApiError } from './types';

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || 'http://localhost:5002/api';

export interface GameStatus {
  currentYear: number;
  divineFavor: number; // Added divineFavor
}

export interface ApiErrorBody {
  message?: string;
}

export const apiService = {
  get: <T>(path: string) => fetchData<T>(path),
  post: <T, R>(path: string, body: T) => postData<T, R>(path, body),
};

// Helper function to fetch data from the backend API
async function fetchData<T>(path: string): Promise<T> {
  // Add timeout controller
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), 10000); // 10s timeout

  try {
    const response = await apiClient.get<T>(path, { signal: controller.signal });
    clearTimeout(id);

    // The apiClient already handles 401s via the interceptor, but we still
    // return the data in the expected format for this legacy wrapper.

    // Check if response is wrapped in { success: boolean, data: T } format natively
    const data = response.data;
    if (data && typeof data === 'object' && 'success' in data && 'data' in data) {
      if (!(data as any).success) {
        throw new ApiError((data as any).error || 'API Error', 500);
      }
      return (data as any).data as T;
    }

    // Otherwise return the parsed response directly
    return data as T;
  } catch (error: any) {
    if (error?.code === 'ECONNABORTED' || error?.message === 'canceled') {
      throw new Error(`Request timeout for ${path}`);
    }

    // If it's already an ApiError, just throw it
    if (error instanceof ApiError) {
      throw error;
    }

    // Convert Axios errors to standard errors to match legacy behavior
    let errorMessage = `Failed to fetch ${path}`;
    if (error.response) {
      errorMessage = error.response.data?.message || `Status ${error.response.status}`;
      if (error.response.status === 401) {
        throw new Error('AUTHENTICATION_REQUIRED');
      }
    }
    throw new Error(errorMessage);
  }
}

// Helper function to post data to the backend API
async function postData<T, R>(path: string, body: T): Promise<R> {
  try {
    const response = await apiClient.post<R>(path, body);

    // Check if response is wrapped natively
    const data = response.data;
    if (data && typeof data === 'object' && 'success' in data && 'data' in data) {
      if (!(data as any).success) {
        throw new ApiError((data as any).error || 'API Error', 500);
      }
      return (data as any).data as R;
    }

    return data as R;
  } catch (error: any) {
    if (error instanceof ApiError) {
      throw error;
    }

    let errorMessage = `Failed to post to ${path}`;
    if (error.response) {
      if (error.response.status === 401) {
        console.warn('Authentication required (401) - handled by global interceptor');
        return {} as R;
      }
      errorMessage = error.response.data?.message || `Status ${error.response.status}`;
    }
    throw new Error(errorMessage);
  }
}

export const getRegions = (): Promise<Region[]> => {
  return fetchData<Region[]>('regions');
};

export const getRegionById = (id: string): Promise<Region | Record<string, unknown>> => {
  return fetchData<Region | Record<string, unknown>>(`regions/${id}`);
};

export const getHeroes = (): Promise<Hero[]> => {
  return fetchData<Hero[]>('heroes');
};

export const getHeroById = (id: string): Promise<Hero | Record<string, unknown>> => {
  return fetchData<Hero | Record<string, unknown>>(`heroes/${id}`);
};

export const getGameEvents = (
  page: number = 1,
  limit: number = 10,
  regionId?: string,
  heroId?: string
): Promise<GameEvent[]> => {
  const regionFilter = regionId ? `&regionId=${regionId}` : '';
  const heroFilter = heroId ? `&heroId=${heroId}` : '';
  return fetchData<GameEvent[]>(`events?page=${page}&limit=${limit}${regionFilter}${heroFilter}`);
};

export const getGameEventById = (id: string): Promise<GameEvent | Record<string, unknown>> => {
  return fetchData<GameEvent | Record<string, unknown>>(`events/${id}`);
};

export const getGameStatus = async (): Promise<GameStatus> => {
  return fetchData<GameStatus>('status');
};

export interface InfluenceActionPayload {
  action: string;
  entityId: string;
  entityType: 'region' | 'hero';
}

export interface InfluenceActionResponse {
  success?: boolean;
  message?: string;
  data?: unknown;
}

export const sendInfluenceAction = (
  payload: InfluenceActionPayload
): Promise<InfluenceActionResponse> => {
  // Backend endpoint to be defined, e.g., /api/influence
  // For now, let's assume a generic endpoint that can differentiate based on entityType
  let path = '';
  if (payload.entityType === 'region') {
    path = `influence/region/${payload.entityId}`;
  } else if (payload.entityType === 'hero') {
    path = `influence/hero/${payload.entityId}`;
  } else {
    // Should not happen with current types, but good for robustness
    return Promise.reject(new Error('Invalid entity type for influence action'));
  }
  // The actual data sent might be just the action, as entityId is in the path
  // Or the backend might prefer the full payload. Adjust as needed.
  return postData<Omit<InfluenceActionPayload, 'entityId' | 'entityType'>, InfluenceActionResponse>(
    path,
    { action: payload.action }
  );
};

// ===== Settlement API =====
export const getSettlements = (): Promise<Settlement[]> => {
  return fetchData<Settlement[]>('settlements');
};

export const getSettlementById = (id: string): Promise<Settlement | Record<string, unknown>> => {
  return fetchData<Settlement | Record<string, unknown>>(`settlements/${id}`);
};

// ===== Building API =====
export const getBuildings = (): Promise<Building[]> => {
  return fetchData<Building[]>('buildings');
};

export const getBuildingById = (id: string): Promise<Building | Record<string, unknown>> => {
  return fetchData<Building | Record<string, unknown>>(`buildings/${id}`);
};

// ===== Landmark API =====
export const getLandmarks = (): Promise<Landmark[]> => {
  return fetchData<Landmark[]>('landmarks');
};

export const getLandmarkById = (id: string): Promise<Landmark | Record<string, unknown>> => {
  return fetchData<Landmark | Record<string, unknown>>(`landmarks/${id}`);
};

// ===== Resource Node API =====
export const getResourceNodes = (): Promise<ResourceNode[]> => {
  return fetchData<ResourceNode[]>('resource-nodes');
};

export const getResourceNodeById = (
  id: string
): Promise<ResourceNode | Record<string, unknown>> => {
  return fetchData<ResourceNode | Record<string, unknown>>(`resource-nodes/${id}`);
};

// ===== Divine Betting API =====
export interface CreateDivineBetPayload {
  betType: string;
  targetId: string;
  description: string;
  timeframe: number;
  confidence: string;
  divineFavorStake: number;
}

export const placeDivineBet = (payload: CreateDivineBetPayload): Promise<DivineBet> => {
  return postData<CreateDivineBetPayload, DivineBet>('bets', payload);
};

export const getDivineBets = (): Promise<DivineBet[]> => {
  return fetchData<DivineBet[]>('bets');
};

export const getDivineBetById = (id: string): Promise<DivineBet | Record<string, unknown>> => {
  return fetchData<DivineBet | Record<string, unknown>>(`bets/${id}`);
};

export const getSpeculationEvents = (): Promise<SpeculationEvent[]> => {
  return fetchData<SpeculationEvent[]>('speculation-events');
};

export const getBettingOdds = (): Promise<BettingOdds[]> => {
  return fetchData<BettingOdds[]>('betting-odds');
};
