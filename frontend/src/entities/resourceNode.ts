// F:\WebDevelopment\Mytherra\frontend\src\entities\resourceNode.ts

/**
 * Represents a resource node within a region
 */
export interface ResourceNode {
  id: string;
  regionId: string;
  settlementId?: string;
  type:
    | "mine"
    | "quarry"
    | "forest"
    | "farmland"
    | "fishing"
    | "magical_spring";
  name: string;
  outputValue: number; // 0-100 productivity
  status: "active" | "depleted" | "contested" | "corrupted";
  createdAt?: Date;
  updatedAt?: Date;
}
