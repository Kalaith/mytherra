// F:\WebDevelopment\Mytherra\frontend\src\components\SettlementList.tsx
import React from 'react';
import { Settlement } from '../entities/settlement';

interface SettlementListProps {
  settlements: Settlement[];
  selectedSettlement: Settlement | null;
  onSelectSettlement: (settlement: Settlement | null) => void;
  isLoading?: boolean;
  error?: string | null;
}

const SettlementList: React.FC<SettlementListProps> = ({ 
  settlements, 
  selectedSettlement, 
  onSelectSettlement, 
  isLoading, 
  error 
}) => {
  if (isLoading) {
    return <div className="text-center p-4">Loading settlements...</div>;
  }

  if (error) {
    return <div className="text-center p-4 text-red-500">Error loading settlements: {error}</div>;
  }

  if (settlements.length === 0) {
    return <div className="text-center p-4">No settlements found.</div>;
  }

  const getStatusColor = (status: Settlement['status']) => {
    switch (status) {
      case 'thriving': return 'text-green-400';
      case 'stable': return 'text-blue-400';
      case 'declining': return 'text-yellow-400';
      case 'abandoned': return 'text-gray-400';
      case 'ruined': return 'text-red-400';
      default: return 'text-gray-300';
    }
  };

  const getTypeIcon = (type: Settlement['type']) => {
    switch (type) {
      case 'city': return '🏰';
      case 'town': return '🏘️';
      case 'village': return '🏡';
      case 'hamlet': return '🏠';
      default: return '📍';
    }
  };

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl">
      <h2 className="text-2xl font-bold mb-4">Settlements ({settlements.length})</h2>
      <div className="space-y-2 max-h-96 overflow-y-auto">
        {settlements.map((settlement) => (
          <div
            key={settlement.id}
            className={`p-3 rounded cursor-pointer transition-colors ${
              selectedSettlement?.id === settlement.id 
                ? 'bg-blue-600 border-2 border-blue-400' 
                : 'bg-gray-700 hover:bg-gray-600'
            }`}
            onClick={() => onSelectSettlement(
              selectedSettlement?.id === settlement.id ? null : settlement
            )}
          >
            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <span className="text-lg">{getTypeIcon(settlement.type)}</span>
                <div>
                  <h3 className="font-semibold">{settlement.name}</h3>
                  <p className="text-sm text-gray-400 capitalize">
                    {settlement.type} • Population: {settlement.population.toLocaleString()}
                  </p>
                </div>
              </div>
              <div className="text-right">
                <p className={`text-sm font-medium ${getStatusColor(settlement.status)}`}>
                  {settlement.status}
                </p>
                <p className="text-xs text-gray-400">
                  Prosperity: {settlement.prosperity}%
                </p>
              </div>
            </div>
            
            {settlement.specializations.length > 0 && (
              <div className="mt-2">
                <div className="flex flex-wrap gap-1">
                  {settlement.specializations.map((spec) => (
                    <span 
                      key={spec}
                      className="px-2 py-1 bg-blue-500 text-xs rounded-full"
                    >
                      {spec}
                    </span>
                  ))}
                </div>
              </div>
            )}

            {settlement.traits.length > 0 && (
              <div className="mt-1">
                <div className="flex flex-wrap gap-1">
                  {settlement.traits.map((trait) => (
                    <span 
                      key={trait}
                      className="px-2 py-1 bg-purple-500 text-xs rounded-full"
                    >
                      {trait}
                    </span>
                  ))}
                </div>
              </div>
            )}
          </div>
        ))}
      </div>
      
      {selectedSettlement && (
        <div className="mt-4 p-3 bg-gray-700 rounded">
          <h3 className="font-semibold mb-2">Settlement Details</h3>
          <div className="grid grid-cols-2 gap-2 text-sm">
            <div>
              <span className="text-gray-400">Founded:</span> Year {selectedSettlement.foundedYear}
            </div>
            <div>
              <span className="text-gray-400">Defensibility:</span> {selectedSettlement.defensibility}%
            </div>
            {selectedSettlement.lastEventYear && (
              <div className="col-span-2">
                <span className="text-gray-400">Last Event:</span> Year {selectedSettlement.lastEventYear}
              </div>
            )}
          </div>
        </div>
      )}
    </div>
  );
};

export default SettlementList;
