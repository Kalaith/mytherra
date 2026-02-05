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
        const response = await apiService.get<{ success: boolean; data: GameSummary }>('/statistics/summary');
        return response.data;
    },

    getHeroStats: async (): Promise<HeroStatistics> => {
        const response = await apiService.get<{ success: boolean; data: HeroStatistics }>('/statistics/heroes');
        return response.data;
    },

    getRegionStats: async (): Promise<RegionStatistics> => {
        const response = await apiService.get<{ success: boolean; data: RegionStatistics }>('/statistics/regions');
        return response.data;
    },

    getFinancialStats: async (): Promise<FinancialStatistics> => {
        const response = await apiService.get<{ success: boolean; data: FinancialStatistics }>('/statistics/financials');
        return response.data;
    }
};
