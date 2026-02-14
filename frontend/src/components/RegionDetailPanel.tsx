import React, { useState } from 'react';
import { Region } from '../entities/region';
import { Settlement } from '../entities/settlement';
import { Landmark } from '../entities/landmark';
import { Hero } from '../entities/hero';
import RegionTabNav, { RegionTabType } from './RegionTabs/RegionTabNav';
import RegionHeader from './RegionTabs/RegionHeader';
import RegionOverviewTab from './RegionTabs/RegionOverviewTab';
import RegionSettlementsTab from './RegionTabs/RegionSettlementsTab';
import RegionLandmarksTab from './RegionTabs/RegionLandmarksTab';
import RegionHeroesList from './RegionTabs/RegionHeroesList';

interface RegionDetailPanelProps {
  region: Region;
  settlements: Settlement[];
  landmarks: Landmark[];
  heroes: Hero[];
  loading?: boolean;
  onSelectSettlement?: (settlement: Settlement) => void;
  onSelectLandmark?: (landmark: Landmark) => void;
  onSelectHero?: (hero: Hero) => void;
}

const RegionDetailPanel: React.FC<RegionDetailPanelProps> = ({
  region,
  settlements,
  landmarks,
  heroes,
  loading = false,
  onSelectSettlement,
  onSelectLandmark,
  onSelectHero,
}) => {
  const [activeTab, setActiveTab] = useState<RegionTabType>('overview');

  // Helper functions for regional data
  const getStatusColor = (status: string) => {
    switch (status) {
      case 'peaceful':
        return 'text-green-400';
      case 'corrupt':
        return 'text-yellow-400';
      case 'warring':
        return 'text-red-400';
      case 'abandoned':
        return 'text-gray-400';
      default:
        return 'text-gray-300';
    }
  };

  const getSettlementSummary = () => {
    const counts = {
      city: settlements.filter(s => s.type === 'city').length,
      town: settlements.filter(s => s.type === 'town').length,
      village: settlements.filter(s => s.type === 'village').length,
      hamlet: settlements.filter(s => s.type === 'hamlet').length,
    };
    return counts;
  };

  const getTotalPopulation = () => {
    return settlements.reduce((sum, settlement) => sum + settlement.population, 0);
  };

  const settlementCounts = getSettlementSummary();
  const totalPopulation = getTotalPopulation();
  const livingHeroesCount = heroes.filter(h => h.isAlive !== false).length;

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl">
      {/* Region Header */}
      <RegionHeader region={region} getStatusColor={getStatusColor} />

      {/* Tab Navigation */}
      <RegionTabNav
        activeTab={activeTab}
        setActiveTab={setActiveTab}
        settlementsCount={settlements.length}
        landmarksCount={landmarks.length}
        heroesCount={livingHeroesCount}
        loading={loading}
      />

      {/* Tab Content */}
      {activeTab === 'overview' && (
        <RegionOverviewTab
          region={region}
          settlementsCount={settlements.length}
          landmarksCount={landmarks.length}
          heroesCount={heroes.length}
          totalPopulation={totalPopulation}
          loading={loading}
          livingHeroesCount={livingHeroesCount}
        />
      )}

      {activeTab === 'settlements' && (
        <RegionSettlementsTab
          settlements={settlements}
          onSelectSettlement={onSelectSettlement}
          settlementCounts={settlementCounts}
          totalPopulation={totalPopulation}
        />
      )}

      {activeTab === 'landmarks' && (
        <RegionLandmarksTab landmarks={landmarks} onSelectLandmark={onSelectLandmark} />
      )}

      {activeTab === 'heroes' && (
        <RegionHeroesList heroes={heroes} loading={loading} onSelectHero={onSelectHero} />
      )}
    </div>
  );
};

export default RegionDetailPanel;
