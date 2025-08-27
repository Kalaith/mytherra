import { Hero } from '../entities/hero';

/**
 * Utility function to determine status color for various game entities
 */
export const getStatusColor = (status: string): string => {
  switch (status) {
    case 'peaceful': return 'text-green-400';
    case 'corrupt': return 'text-yellow-400';
    case 'warring': return 'text-red-400';
    case 'abandoned': return 'text-gray-400';
    default: return 'text-gray-300';
  }
};

/**
 * Utility function to get settlement counts by type
 */
export const getSettlementSummary = (settlements: any[]) => {
  const counts = {
    city: settlements.filter(s => s.type === 'city').length,
    town: settlements.filter(s => s.type === 'town').length,
    village: settlements.filter(s => s.type === 'village').length,
    hamlet: settlements.filter(s => s.type === 'hamlet').length
  };
  return counts;
};

/**
 * Utility function to calculate total population from settlements
 */
export const getTotalPopulation = (settlements: any[]) => {
  return settlements.reduce((sum, settlement) => sum + settlement.population, 0);
};

/**
 * Utility function to get icon for settlement types
 */
export const getSettlementIcon = (type: string): string => {
  switch(type) {
    case 'city': return '🏰';
    case 'town': return '🏘️';
    case 'village': return '🏡';
    default: return '🏠'; // hamlet or unknown type
  }
};

/**
 * Utility function to get icon for landmark types
 */
export const getLandmarkIcon = (type: string): string => {
  switch(type) {
    case 'temple': return '⛩️';
    case 'ruin': return '🏛️';
    case 'forest': return '🌲';
    case 'mountain': return '⛰️';
    case 'river': return '🏞️';
    default: return '📍';
  }
};

/**
 * Utility function to filter and get only living heroes
 */
export const getLivingHeroes = (heroes: Hero[]): Hero[] => {
  return heroes.filter(hero => hero.isAlive !== false);
};

/**
 * Utility function to count living heroes
 */
export const getLivingHeroesCount = (heroes: Hero[]): number => {
  return heroes.filter(hero => hero.isAlive !== false).length;
};
