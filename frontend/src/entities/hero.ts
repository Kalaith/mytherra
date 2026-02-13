// F:\WebDevelopment\Mytherra\frontend\src\entities\hero.ts

/**
 * Represents a hero character in the game.
 */
export interface Hero {
  id: string;
  name: string;
  regionId: string; // The region this hero is currently associated with
  role: "scholar" | "warrior" | "prophet" | "agent of change" | "undecided";
  description: string;
  feats: string[]; // Notable accomplishments
  level?: number; // Added level
  age?: number; // Added age, as it's often displayed with heroes
  isAlive?: boolean; // Added isAlive status
  deathReason?: string; // Added death reason
  status?: "living" | "deceased" | "undead" | "ascended"; // Status for special conditions beyond alive/dead
  personalityTraits?: string[]; // Personality traits like curious, vengeful, ambitious
  alignment?: {
    good: number; // 0-100 scale for good vs evil
    chaotic: number; // 0-100 scale for chaotic vs lawful
    lastChange?: string; // Reason for the last alignment change
  };
  influenceActionCosts?: {
    guideHero?: number;
    empowerHero?: number;
    reviveHero?: number; // Added reviveHero cost
    forceNotableEvent?: number; // Added forceNotableEvent cost
  };
}
