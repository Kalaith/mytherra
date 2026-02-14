// F:\WebDevelopment\Mytherra\frontend\src\components\LandmarkList.tsx
import React from 'react';
import { Landmark } from '../entities/landmark';

interface LandmarkListProps {
  landmarks: Landmark[];
  selectedLandmark: Landmark | null;
  onSelectLandmark: (landmark: Landmark | null) => void;
  isLoading?: boolean;
  error?: string | null;
}

const LandmarkList: React.FC<LandmarkListProps> = ({
  landmarks,
  selectedLandmark,
  onSelectLandmark,
  isLoading,
  error,
}) => {
  if (isLoading) {
    return <div className="text-center p-4">Loading landmarks...</div>;
  }

  if (error) {
    return <div className="text-center p-4 text-red-500">Error loading landmarks: {error}</div>;
  }

  if (landmarks.length === 0) {
    return <div className="text-center p-4">No landmarks found.</div>;
  }

  const getStatusColor = (status: Landmark['status']) => {
    switch (status) {
      case 'pristine':
        return 'text-green-400';
      case 'weathered':
        return 'text-yellow-400';
      case 'corrupted':
        return 'text-red-400';
      case 'blessed':
        return 'text-blue-400';
      case 'haunted':
        return 'text-purple-400';
      case 'active':
        return 'text-orange-400';
      default:
        return 'text-gray-300';
    }
  };

  const getTypeIcon = (type: Landmark['type']) => {
    switch (type) {
      case 'temple':
        return '⛩️';
      case 'ruin':
        return '🏛️';
      case 'forest':
        return '🌲';
      case 'mountain':
        return '⛰️';
      case 'river':
        return '🏞️';
      case 'monument':
        return '🗿';
      case 'dungeon':
        return '🕳️';
      case 'tower':
        return '🗼';
      case 'battlefield':
        return '⚔️';
      case 'grove':
        return '🌳';
      default:
        return '📍';
    }
  };

  const getDangerLevel = (level: number) => {
    if (level >= 80) return { text: 'Extreme', color: 'text-red-500' };
    if (level >= 60) return { text: 'High', color: 'text-orange-500' };
    if (level >= 40) return { text: 'Moderate', color: 'text-yellow-500' };
    if (level >= 20) return { text: 'Low', color: 'text-green-500' };
    return { text: 'Safe', color: 'text-blue-500' };
  };

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl">
      <h2 className="text-2xl font-bold mb-4">Landmarks ({landmarks.length})</h2>
      <div className="space-y-2 max-h-96 overflow-y-auto">
        {landmarks.map(landmark => {
          const dangerInfo = getDangerLevel(landmark.dangerLevel);

          return (
            <div
              key={landmark.id}
              className={`p-3 rounded cursor-pointer transition-colors ${
                selectedLandmark?.id === landmark.id
                  ? 'bg-blue-600 border-2 border-blue-400'
                  : 'bg-gray-700 hover:bg-gray-600'
              }`}
              onClick={() =>
                onSelectLandmark(selectedLandmark?.id === landmark.id ? null : landmark)
              }
            >
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-2">
                  <span className="text-lg">{getTypeIcon(landmark.type)}</span>
                  <div>
                    <h3 className="font-semibold">{landmark.name}</h3>
                    <p className="text-sm text-gray-400 capitalize">{landmark.type}</p>
                  </div>
                </div>
                <div className="text-right">
                  <p className={`text-sm font-medium ${getStatusColor(landmark.status)}`}>
                    {landmark.status}
                  </p>
                  <p className={`text-xs ${dangerInfo.color}`}>Danger: {dangerInfo.text}</p>
                </div>
              </div>

              <div className="mt-2 grid grid-cols-2 gap-2 text-sm">
                <div>
                  <span className="text-gray-400">Magic:</span> {landmark.magicLevel}%
                </div>
                <div>
                  <span className="text-gray-400">Danger:</span> {landmark.dangerLevel}%
                </div>
              </div>

              {landmark.traits.length > 0 && (
                <div className="mt-2">
                  <div className="flex flex-wrap gap-1">
                    {landmark.traits.map(trait => (
                      <span key={trait} className="px-2 py-1 bg-purple-500 text-xs rounded-full">
                        {trait}
                      </span>
                    ))}
                  </div>
                </div>
              )}
            </div>
          );
        })}
      </div>

      {selectedLandmark && (
        <div className="mt-4 p-3 bg-gray-700 rounded">
          <h3 className="font-semibold mb-2">Landmark Details</h3>
          <p className="text-sm text-gray-300 mb-2">{selectedLandmark.description}</p>
          <div className="grid grid-cols-2 gap-2 text-sm">
            {selectedLandmark.discoveredYear && (
              <div>
                <span className="text-gray-400">Discovered:</span> Year{' '}
                {selectedLandmark.discoveredYear}
              </div>
            )}
            {selectedLandmark.lastVisitedYear && (
              <div>
                <span className="text-gray-400">Last Visited:</span> Year{' '}
                {selectedLandmark.lastVisitedYear}
              </div>
            )}
            <div className="col-span-2">
              <span className="text-gray-400">Events:</span>{' '}
              {selectedLandmark.associatedEvents.length} recorded
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default LandmarkList;
