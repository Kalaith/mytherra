// F:\WebDevelopment\Mytherra\frontend\src\components\LandmarkPanel.tsx
import React, { useState } from 'react';
import { useRegions } from '../contexts/useRegionContext';
import { Landmark } from '../entities/landmark';
import LandmarkList from './LandmarkList';
import { Region } from '../entities/region';

interface LandmarkPanelProps {
  selectedRegion: Region | null;
}

const LandmarkPanel: React.FC<LandmarkPanelProps> = ({ selectedRegion }) => {
  const { landmarks, isLoading, error, getLandmarksByRegion } = useRegions();
  const [selectedLandmark, setSelectedLandmark] = useState<Landmark | null>(null);

  // Filter landmarks by selected region or show all
  const displayedLandmarks = selectedRegion 
    ? getLandmarksByRegion(selectedRegion.id)
    : landmarks;

  return (
    <div className="space-y-4">
      {selectedRegion && (
        <div className="bg-gray-700 p-3 rounded-lg">
          <h3 className="text-lg font-semibold text-yellow-400">
            Landmarks in {selectedRegion.name}
          </h3>
          <p className="text-sm text-gray-300">
            {getLandmarksByRegion(selectedRegion.id).length} landmarks found
          </p>
        </div>
      )}
      
      <LandmarkList
        landmarks={displayedLandmarks}
        selectedLandmark={selectedLandmark}
        onSelectLandmark={setSelectedLandmark}
        isLoading={isLoading}
        error={error}
      />
    </div>
  );
};

export default LandmarkPanel;
