import { useState, useEffect, useCallback } from "react";
import { Region } from "../entities/region";
import { getRegions } from "../api/apiService";

interface UseRegionsOptions {
  autoRefresh?: boolean;
  refreshInterval?: number;
}

interface UseRegionsReturn {
  regions: Region[];
  isLoading: boolean;
  error: string | null;
  selectedRegion: Region | null;
  selectRegion: (region: Region | null) => void;
  refetch: () => Promise<void>;
}

export const useRegions = (
  options: UseRegionsOptions = {},
): UseRegionsReturn => {
  const { autoRefresh = false, refreshInterval = 30000 } = options;

  const [regions, setRegions] = useState<Region[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedRegion, setSelectedRegion] = useState<Region | null>(null);

  const fetchRegions = useCallback(async () => {
    try {
      setIsLoading(true);
      const regionsData = await getRegions();
      setRegions(regionsData);
      setError(null);
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError("An unknown error occurred while fetching regions.");
      }
      console.error("Failed to load regions:", err);
    } finally {
      setIsLoading(false);
    }
  }, []);

  const selectRegion = useCallback(
    (region: Region | null) => {
      setSelectedRegion(selectedRegion?.id === region?.id ? null : region);
    },
    [selectedRegion],
  );

  const refetch = useCallback(() => {
    return fetchRegions();
  }, [fetchRegions]);

  // Initial load
  useEffect(() => {
    fetchRegions();
  }, [fetchRegions]);

  // Auto-refresh effect
  useEffect(() => {
    if (!autoRefresh || refreshInterval <= 0) return;

    const interval = setInterval(() => {
      fetchRegions();
    }, refreshInterval);

    return () => clearInterval(interval);
  }, [autoRefresh, refreshInterval, fetchRegions]);

  return {
    regions,
    isLoading,
    error,
    selectedRegion,
    selectRegion,
    refetch,
  };
};
