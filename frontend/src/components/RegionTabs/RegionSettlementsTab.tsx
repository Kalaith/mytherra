import React from 'react';
import { Settlement } from '../../entities/settlement';
import SettlementSummary from './SettlementSummary';
import SettlementList from './SettlementList';

interface RegionSettlementsTabProps {
  settlements: Settlement[];
  onSelectSettlement?: (settlement: Settlement) => void;
  settlementCounts: {
    city: number;
    town: number;
    village: number;
    hamlet: number;
  };
  totalPopulation: number;
}

const RegionSettlementsTab: React.FC<RegionSettlementsTabProps> = ({
  settlements,
  onSelectSettlement,
  settlementCounts,
  totalPopulation
}) => {
  return (
    <div>
      {/* Settlement Summary */}
      <SettlementSummary 
        settlementCounts={settlementCounts} 
        totalPopulation={totalPopulation} 
      />

      {/* Settlements List */}
      <SettlementList 
        settlements={settlements} 
        onSelectSettlement={onSelectSettlement} 
      />
    </div>
  );
};

export default RegionSettlementsTab;
