// F:\WebDevelopment\Mytherra\frontend\src\api\apiService.ts
import { Region } from '../entities/region';
import { Hero } from '../entities/hero';
import { GameEvent } from '../entities/event';

const API_BASE_URL = process.env.VITE_API_BASE_URL || 'http://localhost:5001/api';

// Helper function to fetch data from the backend API
async function fetchData<T>(path: string): Promise<T> {
  const response = await fetch(`${API_BASE_URL}/${path}`);
  if (!response.ok) {
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
  // Handle cases where backend might return an empty object for a single resource not found
  // For lists, an empty array is fine.
  const text = await response.text();
  if (text === "{}" && !path.includes('/')) { // Heuristic: path doesn't include '/' for list endpoints like 'regions'
    // If expecting a single object and got {}, treat as not found / null for consistency if needed by UI
    // However, for lists, an empty array is the correct representation of no data.
    // The current backend returns {} for single item not found, which is fine for direct consumption.
    // If a component expects null instead of {}, that transformation should happen in the component or service layer.
  }
  return JSON.parse(text) as Promise<T>; 
}

export const getRegions = (): Promise<Region[]> => {
  return fetchData<Region[]>('regions');
};

export const getRegionById = (id: string): Promise<Region> => {
  return fetchData<Region>(`regions/${id}`);
};

export const getHeroes = (): Promise<Hero[]> => {
  return fetchData<Hero[]>('heroes');
};

export const getHeroById = (id: string): Promise<Hero> => {
  return fetchData<Hero>(`heroes/${id}`);
};

export const getGameEvents = (): Promise<GameEvent[]> => {
  return fetchData<GameEvent[]>('events');
};

export const getGameEventById = (id: string): Promise<GameEvent> => {
  return fetchData<GameEvent>(`events/${id}`);
};
