import { apiService } from './apiService';

export interface GameSummary {
  currentEra: number;
  currentYear: number;
  totalHeroes: number;
  livingHeroes: number;
  totalRegions: number;
  totalSettlements: number;
  activeBets: number;
}

export interface HeroStatistics {
  roleDistribution: Record<string, number>;
  statusDistribution: Record<string, number>;
  levelDistribution: Record<string, number>;
  averageLevel: number;
  topHeroes: Array<{
    id: string;
    name: string;
    level: number;
    role: string;
  }>;
}

export interface RegionStatistics {
  averageProsperity: number;
  averageChaos: number;
  averageMagicAffinity: number;
  totalPopulation: number;
  mostDangerous: Array<{ id: string; name: string; danger_level: number }>;
  mostProsperous: Array<{ id: string; name: string; prosperity: number }>;
  statusDistribution: Record<string, number>;
}

export interface FinancialStatistics {
  totalBetsPlaced: number;
  totalInfluenceWagered: number;
  betsWon: number;
  betsLost: number;
  activeBets: number;
  payoutRatio: number;
}

export const statisticsService = {
  getSummary: async (): Promise<GameSummary> => {
    return await apiService.get<GameSummary>('statistics/summary');
  },

  getHeroStats: async (): Promise<HeroStatistics> => {
    return await apiService.get<HeroStatistics>('statistics/heroes');
  },

  getRegionStats: async (): Promise<RegionStatistics> => {
    return await apiService.get<RegionStatistics>('statistics/regions');
  },

  getFinancialStats: async (): Promise<FinancialStatistics> => {
    return await apiService.get<FinancialStatistics>('statistics/financials');
  },
};
