import React from 'react';
import DivineBettingPanel from '../components/DivineBettingPanel';
import PageLayout from '../components/PageLayout';
import { useGameStatus } from '../hooks/useGameStatus';

const BettingPage: React.FC = () => {
  const { gameStatus, isLoading, error } = useGameStatus();

  return (
    <PageLayout
      gameStatus={gameStatus}
      isLoading={isLoading}
      error={error}
      loadingMessage="Loading betting data..."
      errorPrefix="Error loading betting data"
      showEventLog={true}
    >
      <DivineBettingPanel currentDivineFavor={gameStatus?.divineFavor || 0} />
    </PageLayout>
  );
};

export default BettingPage;
