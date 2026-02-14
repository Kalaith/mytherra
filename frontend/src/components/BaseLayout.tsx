import React, { ReactNode } from 'react';
import type { GameStatus } from '../api/apiService';
import Header from './Header';
import NavigationBar from './NavigationBar';

interface BaseLayoutProps {
  gameStatus: GameStatus | null;
  isLoading?: boolean;
  error?: string | null;
  loadingMessage?: string;
  errorPrefix?: string;
  children: ReactNode;
}

const BaseLayout: React.FC<BaseLayoutProps> = ({
  gameStatus,
  isLoading,
  error,
  loadingMessage = 'Loading...',
  errorPrefix = 'Error loading data',
  children,
}) => {
  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-900 text-gray-100">
        <div className="text-center p-8">{loadingMessage}</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen bg-gray-900 text-gray-100">
        <div className="text-center p-8 text-red-500">
          {errorPrefix}: {error}
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-900 text-gray-100">
      <Header gameStatus={gameStatus} />
      <NavigationBar />
      <div className="container mx-auto p-4">{children}</div>
    </div>
  );
};

export default BaseLayout;
