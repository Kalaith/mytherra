// F:\WebDevelopment\Mytherra\frontend\src\entities\building.ts

/**
 * Represents a building within a settlement
 */
export interface Building {
  id: string;
  settlementId: string;
  type:
    | "temple"
    | "market"
    | "fortress"
    | "library"
    | "inn"
    | "forge"
    | "farm"
    | "mine"
    | "tower";
  name: string;
  status: "active" | "abandoned" | "corrupted" | "ruined" | "blessed";
  condition: number; // 0-100
  specialProperties?: string[]; // ['magical', 'ancient', 'cursed', 'sacred']
  createdAt?: Date;
  updatedAt?: Date;
}
