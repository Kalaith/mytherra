import React from "react";
import RegionGrid from "../components/RegionGrid";
import RegionDetailWrapper from "../components/RegionDetailWrapper";
import PageLayout from "../components/PageLayout";
import PageHeader from "../components/PageHeader";
import EmptyState from "../components/EmptyState";
import RegionInfluencePanel from "../components/RegionInfluencePanel";
import { useGameStatus } from "../hooks/useGameStatus";
import { useRegions } from "../hooks/useRegions";

const WorldMapPage: React.FC = () => {
  const {
    gameStatus,
    isLoading: isLoadingGameStatus,
    error: gameStatusError,
    refetch: refetchGameStatus,
  } = useGameStatus({
    autoRefresh: true,
    refreshInterval: 10000,
  });

  const {
    regions,
    isLoading: isLoadingRegions,
    error: regionsError,
    selectedRegion,
    selectRegion,
    refetch: refetchRegions,
  } = useRegions({
    autoRefresh: true,
    refreshInterval: 30000,
  });

  const handleActionSuccess = () => {
    refetchGameStatus();
    refetchRegions();
  };

  const isLoading = isLoadingGameStatus || isLoadingRegions;
  const error = gameStatusError || regionsError;
  const topContent = (
    <RegionInfluencePanel
      selectedRegion={selectedRegion}
      currentDivineFavor={gameStatus?.divineFavor || 0}
      onActionSuccess={handleActionSuccess}
    />
  );

  return (
    <PageLayout
      gameStatus={gameStatus}
      isLoading={isLoading}
      error={error}
      loadingMessage="Loading world map..."
      errorPrefix="Error loading world map"
      showEventLog={true}
      selectedRegionId={selectedRegion?.id}
      topContent={topContent}
    >
      <PageHeader
        title="World of Mytherra"
        subtitle={`${regions.length} regions await your divine attention`}
        icon="🗺️"
      />

      {regions.length === 0 ? (
        <EmptyState
          title="World Loading"
          message="The world is being shaped by divine hands. Regions will appear as creation unfolds."
          icon="🌍"
          actionButton={{
            label: "Refresh World",
            onClick: refetchRegions,
          }}
        />
      ) : (
        <>
          <RegionGrid
            regions={regions}
            selectedRegion={selectedRegion}
            onSelectRegion={selectRegion}
          />

          {/* Show region details below the grid when a region is selected */}
          {selectedRegion && <RegionDetailWrapper region={selectedRegion} />}
        </>
      )}
    </PageLayout>
  );
};

export default WorldMapPage;
