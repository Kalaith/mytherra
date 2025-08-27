// F:\WebDevelopment\Mytherra\frontend\src\entities\landmark.ts

/**
 * Represents a landmark within a region
 */
export interface Landmark {
  id: string;
  regionId: string;
  name: string;
  type: 'temple' | 'ruin' | 'forest' | 'mountain' | 'river' | 'monument' | 'dungeon' | 'tower' | 'battlefield' | 'grove';
  description: string;
  status: 'pristine' | 'weathered' | 'corrupted' | 'blessed' | 'haunted' | 'active';
  magicLevel: number; // 0-100
  dangerLevel: number; // 0-100
  discoveredYear?: number;
  lastVisitedYear?: number;
  associatedEvents: string[];
  traits: string[]; // ['ancient', 'dragon_lair', 'portal', 'cursed_ground', 'holy_site']
  createdAt?: Date;
  updatedAt?: Date;
}
