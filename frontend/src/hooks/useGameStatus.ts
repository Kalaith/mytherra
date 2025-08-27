import { useState, useEffect } from 'react';
import { getGameStatus } from '../api/apiService';
import type { GameStatus } from '../api/apiService';

interface UseGameStatusOptions {
  autoRefresh?: boolean;
  refreshInterval?: number;
}

interface UseGameStatusReturn {
  gameStatus: GameStatus | null;
  isLoading: boolean;
  error: string | null;
  refetch: () => Promise<void>;
}

export const useGameStatus = (options: UseGameStatusOptions = {}): UseGameStatusReturn => {
  const { autoRefresh = false, refreshInterval = 10000 } = options;
  
  const [gameStatus, setGameStatus] = useState<GameStatus | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  const fetchGameStatus = async () => {
    try {
      const statusData = await getGameStatus();
      setGameStatus(statusData);
      setError(null);
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('An unknown error occurred while fetching game status.');
      }
      console.error("Failed to load game status:", err);
    }
  };

  const refetch = async () => {
    setIsLoading(true);
    try {
      await fetchGameStatus();
    } finally {
      setIsLoading(false);
    }
  };

  useEffect(() => {
    const initialFetch = async () => {
      setIsLoading(true);
      try {
        await fetchGameStatus();
      } finally {
        setIsLoading(false);
      }
    };

    initialFetch();

    if (autoRefresh) {
      const intervalId = setInterval(() => {
        fetchGameStatus();
      }, refreshInterval);

      return () => {
        clearInterval(intervalId);
      };
    }
  }, [autoRefresh, refreshInterval]);

  return {
    gameStatus,
    isLoading,
    error,
    refetch
  };
};
