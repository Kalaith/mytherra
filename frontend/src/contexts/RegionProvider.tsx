import React, { useEffect, useState, type ReactNode } from 'react';
import { getRegions, getSettlements, getLandmarks } from '../api/apiService';
import { RegionContext } from './regionContext';
import type { Region } from '../entities/region';
import type { Settlement } from '../entities/settlement';
import type { Landmark } from '../entities/landmark';

interface RegionProviderProps {
  children: ReactNode;
}

export const RegionProvider: React.FC<RegionProviderProps> = ({ children }) => {
  const [regions, setRegions] = useState<Region[]>([]);
  const [settlements, setSettlements] = useState<Settlement[]>([]);
  const [landmarks, setLandmarks] = useState<Landmark[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [regionsData, settlementsData, landmarksData] = await Promise.all([
          getRegions(),
          getSettlements(),
          getLandmarks(),
        ]);

        setRegions(regionsData);
        setSettlements(settlementsData);
        setLandmarks(landmarksData);
        setError(null);
      } catch (err) {
        if (err instanceof Error) {
          setError(err.message);
        } else {
          setError('Failed to fetch data');
        }
        console.error('Error fetching data:', err);
      } finally {
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  const getRegionName = (regionId: string): string => {
    const region = regions.find(r => r.id === regionId);
    return region ? region.name : regionId;
  };

  const getSettlementsByRegion = (regionId: string): Settlement[] => {
    return settlements.filter(s => s.regionId === regionId);
  };

  const getLandmarksByRegion = (regionId: string): Landmark[] => {
    return landmarks.filter(l => l.regionId === regionId);
  };

  return (
    <RegionContext.Provider
      value={{
        regions,
        settlements,
        landmarks,
        isLoading,
        error,
        getRegionName,
        getSettlementsByRegion,
        getLandmarksByRegion,
      }}
    >
      {children}
    </RegionContext.Provider>
  );
};
