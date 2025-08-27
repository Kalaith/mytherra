import React from 'react';
import { Landmark } from '../../entities/landmark';
import { getLandmarkIcon } from '../../utils/regionUtils';

interface LandmarkListProps {
  landmarks: Landmark[];
  onSelectLandmark?: (landmark: Landmark) => void;
}

const LandmarkList: React.FC<LandmarkListProps> = ({ landmarks, onSelectLandmark }) => {
  if (landmarks.length === 0) {
    return (
      <div className="text-center py-8 text-gray-400">
        No landmarks found in this region
      </div>
    );
  }

  return (
    <div className="space-y-1">
      {landmarks.map(landmark => (
        <LandmarkItem 
          key={landmark.id} 
          landmark={landmark} 
          onSelectLandmark={onSelectLandmark} 
        />
      ))}
    </div>
  );
};

interface LandmarkItemProps {
  landmark: Landmark;
  onSelectLandmark?: (landmark: Landmark) => void;
}

const LandmarkItem: React.FC<LandmarkItemProps> = ({ landmark, onSelectLandmark }) => {  // Using the imported getLandmarkIcon function from regionUtils

  return (
    <div
      className="flex justify-between items-center p-2 bg-gray-600 rounded cursor-pointer hover:bg-gray-500 transition-colors"
      onClick={() => onSelectLandmark?.(landmark)}
    >
      <div className="flex items-center space-x-2">
        <span className="text-sm">
          {getLandmarkIcon(landmark.type)}
        </span>
        <span className="text-sm font-medium">{landmark.name}</span>
      </div>
      <div className="flex items-center">
        <div className="mx-2 text-xs">
          <span className="text-purple-400 mr-2">Magic: {landmark.magicLevel}%</span>
          <span className="text-red-300">Danger: {landmark.dangerLevel}%</span>
        </div>
        <div className="bg-gray-800 px-2 py-1 rounded text-xs capitalize">
          {landmark.status}
        </div>
      </div>
    </div>
  );
};

export default LandmarkList;
