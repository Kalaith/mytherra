// F:\WebDevelopment\Mytherra\frontend\src\components\SettlementPanel.tsx
import React, { useState } from 'react';
import { useRegions } from '../contexts/useRegionContext';
import { Settlement } from '../entities/settlement';
import SettlementList from './SettlementList';
import { Region } from '../entities/region';

interface SettlementPanelProps {
  selectedRegion: Region | null;
}

const SettlementPanel: React.FC<SettlementPanelProps> = ({ selectedRegion }) => {
  const { settlements, isLoading, error, getSettlementsByRegion } = useRegions();
  const [selectedSettlement, setSelectedSettlement] = useState<Settlement | null>(null);

  // Filter settlements by selected region or show all
  const displayedSettlements = selectedRegion
    ? getSettlementsByRegion(selectedRegion.id)
    : settlements;

  return (
    <div className="space-y-4">
      {selectedRegion && (
        <div className="bg-gray-700 p-3 rounded-lg">
          <h3 className="text-lg font-semibold text-yellow-400">
            Settlements in {selectedRegion.name}
          </h3>
          <p className="text-sm text-gray-300">
            {getSettlementsByRegion(selectedRegion.id).length} settlements found
          </p>
        </div>
      )}

      <SettlementList
        settlements={displayedSettlements}
        selectedSettlement={selectedSettlement}
        onSelectSettlement={setSelectedSettlement}
        isLoading={isLoading}
        error={error}
      />
    </div>
  );
};

export default SettlementPanel;
