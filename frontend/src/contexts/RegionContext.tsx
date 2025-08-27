import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { Region } from '../entities/region';
import { Settlement } from '../entities/settlement';
import { Landmark } from '../entities/landmark';
import { getRegions, getSettlements, getLandmarks } from '../api/apiService';

// Define the shape of our context
interface RegionContextType {
  regions: Region[];
  settlements: Settlement[];
  landmarks: Landmark[];
  isLoading: boolean;
  error: string | null;
  getRegionName: (regionId: string) => string;
  getSettlementsByRegion: (regionId: string) => Settlement[];
  getLandmarksByRegion: (regionId: string) => Landmark[];
}

// Create context with a default value
const RegionContext = createContext<RegionContextType>({
  regions: [],
  settlements: [],
  landmarks: [],
  isLoading: false,
  error: null,
  getRegionName: () => '',
  getSettlementsByRegion: () => [],
  getLandmarksByRegion: () => []
});

// Custom hook to use the region context
export const useRegions = () => useContext(RegionContext);

// Provider component
interface RegionProviderProps {
  children: ReactNode;
}

export const RegionProvider: React.FC<RegionProviderProps> = ({ children }) => {
  const [regions, setRegions] = useState<Region[]>([]);
  const [settlements, setSettlements] = useState<Settlement[]>([]);
  const [landmarks, setLandmarks] = useState<Landmark[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  // Fetch regions, settlements, and landmarks on component mount
  useEffect(() => {
    const fetchData = async () => {
      try {
        const [regionsData, settlementsData, landmarksData] = await Promise.all([
          getRegions(),
          getSettlements(),
          getLandmarks()
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

  // Helper function to get region name from regionId
  const getRegionName = (regionId: string): string => {
    const region = regions.find(r => r.id === regionId);
    return region ? region.name : regionId;
  };

  // Helper function to get settlements by region
  const getSettlementsByRegion = (regionId: string): Settlement[] => {
    return settlements.filter(s => s.regionId === regionId);
  };

  // Helper function to get landmarks by region
  const getLandmarksByRegion = (regionId: string): Landmark[] => {
    return landmarks.filter(l => l.regionId === regionId);
  };

  return (
    <RegionContext.Provider value={{ 
      regions, 
      settlements, 
      landmarks, 
      isLoading, 
      error, 
      getRegionName,
      getSettlementsByRegion,
      getLandmarksByRegion
    }}>
      {children}
    </RegionContext.Provider>
  );
};
