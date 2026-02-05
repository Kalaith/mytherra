import { Region } from '../entities/region';
import { Hero } from '../entities/hero';
import { GameEvent } from '../entities/event';
import { Settlement } from '../entities/settlement';
import { Building } from '../entities/building';
import { Landmark } from '../entities/landmark';
import { ResourceNode } from '../entities/resourceNode';
import { DivineBet, SpeculationEvent, BettingOdds } from '../entities/divineBet';
import { getAuthHeaders } from '../contexts/AuthContext';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:5002/api';

export interface GameStatus {
  currentYear: number;
  divineFavor: number; // Added divineFavor
}

export const apiService = {
  get: <T>(path: string) => fetchData<T>(path),
  post: <T, R>(path: string, body: T) => postData<T, R>(path, body)
};

// Helper function to fetch data from the backend API
async function fetchData<T>(path: string): Promise<T> {
  const response = await fetch(`${API_BASE_URL}/${path}`, {
    headers: await getAuthHeaders()
  });

  if (!response.ok) {
    // Handle authentication errors
    if (response.status === 401) {
      // Redirect to login - allow app to handle it
      console.warn('Authentication required (401) - redirect disabled');
      // throw new Error('Authentication required');
      return {} as T; // Return empty object/null to prevent crash but avoid redirect trigger
    }

    // Attempt to parse error message from backend if available
    let errorMessage = `Failed to fetch ${path}: ${response.statusText}`;
    try {
      const errorBody = await response.json();
      if (errorBody && errorBody.message) {
        errorMessage = `Failed to fetch ${path}: ${errorBody.message}`;
      }
    } catch (e) {
      // Ignore if error body is not JSON or not present
    }
    throw new Error(errorMessage);
  }

  const text = await response.text();

  // Handle cases where backend might return an empty object for a single resource not found
  if (text === "{}") {
    // For list endpoints (e.g., /regions), an empty array is expected for no data.
    // For single item endpoints (e.g., /regions/id), an empty object means not found.
    // We will return it as is, and components can decide if {} means null or an empty entity.
    return JSON.parse(text) as T;
  }

  const parsed = JSON.parse(text);

  // Check if response is wrapped in { success: boolean, data: T } format
  if (parsed && typeof parsed === 'object' && 'success' in parsed && 'data' in parsed) {
    return parsed.data as T;
  }

  // Otherwise return the parsed response directly
  return parsed as T;
}

// Helper function to post data to the backend API
async function postData<T, R>(path: string, body: T): Promise<R> {
  const response = await fetch(`${API_BASE_URL}/${path}`, {
    method: 'POST',
    headers: await getAuthHeaders(),
    body: JSON.stringify(body),
  });

  if (!response.ok) {
    // Handle authentication errors
    if (response.status === 401) {
      // Redirect to login - allow app to handle it
      console.warn('Authentication required (401) - redirect disabled');
      // throw new Error('Authentication required');
      return {} as R;
    }

    let errorMessage = `Failed to post to ${path}: ${response.statusText}`;
    try {
      const errorBody = await response.json();
      if (errorBody && errorBody.message) {
        errorMessage = `Failed to post to ${path}: ${errorBody.message}`;
      }
    } catch (e) {
      // Ignore if error body is not JSON or not present
    }
    throw new Error(errorMessage);
  }
  return response.json() as R;
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

export const getGameEvents = (page: number = 1, limit: number = 10, regionId?: string, heroId?: string): Promise<GameEvent[]> => {
  const regionFilter = regionId ? `&regionId=${regionId}` : '';
  const heroFilter = heroId ? `&heroId=${heroId}` : '';
  return fetchData<GameEvent[]>(`events?page=${page}&limit=${limit}${regionFilter}${heroFilter}`);
};

export const getGameEventById = (id: string): Promise<GameEvent | Record<string, unknown>> => {
  return fetchData<GameEvent | Record<string, unknown>>(`events/${id}`);
};

export const getGameStatus = async (): Promise<GameStatus> => {
  const response = await fetchData<{ success: boolean, data: GameStatus, timestamp: string }>('status');
  // Extract the data property from the response
  return response.data;
};

export interface InfluenceActionPayload {
  action: string;
  entityId: string;
  entityType: 'region' | 'hero';
}

export const sendInfluenceAction = (payload: InfluenceActionPayload): Promise<any> => {
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
  return postData<Omit<InfluenceActionPayload, 'entityId' | 'entityType'>, any>(path, { action: payload.action });
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

export const getResourceNodeById = (id: string): Promise<ResourceNode | Record<string, unknown>> => {
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
