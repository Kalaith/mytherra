// F:\WebDevelopment\Mytherra\frontend\src\entities\event.ts

/**
 * Represents a significant event that occurs in the world.
 */
export interface GameEvent {
  id: string;
  timestamp: string; // ISO date string
  description: string;
  relatedRegionIds?: string[];
  relatedHeroIds?: string[];
  year?: number; // Add year to the frontend entity
}
