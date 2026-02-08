import { createContext } from 'react';
import type { Region } from '../entities/region';
import type { Settlement } from '../entities/settlement';
import type { Landmark } from '../entities/landmark';

export interface RegionContextType {
  regions: Region[];
  settlements: Settlement[];
  landmarks: Landmark[];
  isLoading: boolean;
  error: string | null;
  getRegionName: (regionId: string) => string;
  getSettlementsByRegion: (regionId: string) => Settlement[];
  getLandmarksByRegion: (regionId: string) => Landmark[];
}

export const RegionContext = createContext<RegionContextType>({
  regions: [],
  settlements: [],
  landmarks: [],
  isLoading: false,
  error: null,
  getRegionName: () => '',
  getSettlementsByRegion: () => [],
  getLandmarksByRegion: () => [],
});

