import React from "react";
import { Settlement } from "../../entities/settlement";
import { getSettlementIcon } from "../../utils/regionUtils";

interface SettlementListProps {
  settlements: Settlement[];
  onSelectSettlement?: (settlement: Settlement) => void;
}

const SettlementList: React.FC<SettlementListProps> = ({
  settlements,
  onSelectSettlement,
}) => {
  if (settlements.length === 0) {
    return (
      <div className="text-center py-8 text-gray-400">
        No settlements found in this region
      </div>
    );
  }

  return (
    <div className="space-y-1">
      {settlements.map((settlement) => (
        <SettlementItem
          key={settlement.id}
          settlement={settlement}
          onSelectSettlement={onSelectSettlement}
        />
      ))}
    </div>
  );
};

interface SettlementItemProps {
  settlement: Settlement;
  onSelectSettlement?: (settlement: Settlement) => void;
}

const SettlementItem: React.FC<SettlementItemProps> = ({
  settlement,
  onSelectSettlement,
}) => {
  // Using the imported getSettlementIcon function from regionUtils

  return (
    <div
      className="flex justify-between items-center p-2 bg-gray-600 rounded cursor-pointer hover:bg-gray-500 transition-colors"
      onClick={() => onSelectSettlement?.(settlement)}
    >
      <div className="flex items-center space-x-2">
        <span className="text-sm">{getSettlementIcon(settlement.type)}</span>
        <span className="text-sm font-medium">{settlement.name}</span>
      </div>
      <div className="flex items-center">
        <div className="mx-2 text-xs">
          <span className="text-green-400">{settlement.prosperity}% </span>
          <span className="text-gray-400">|</span>
          <span className="ml-1 text-blue-400">
            {settlement.population.toLocaleString()}
          </span>
        </div>
        <div className="bg-gray-800 px-2 py-1 rounded text-xs capitalize">
          {settlement.status}
        </div>
      </div>
    </div>
  );
};

export default SettlementList;
