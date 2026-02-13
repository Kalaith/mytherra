import React from "react";

interface SettlementSummaryProps {
  settlementCounts: {
    city: number;
    town: number;
    village: number;
    hamlet: number;
  };
  totalPopulation: number;
}

const SettlementSummary: React.FC<SettlementSummaryProps> = ({
  settlementCounts,
  totalPopulation,
}) => {
  return (
    <div className="mb-4 p-3 bg-gray-700 rounded">
      <h3 className="text-lg font-semibold mb-2">Settlement Summary</h3>
      <div className="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm mb-3">
        {settlementCounts.city > 0 && (
          <div className="flex items-center">
            <span className="mr-1">🏰</span>
            <span>{settlementCounts.city} Cities</span>
          </div>
        )}
        {settlementCounts.town > 0 && (
          <div className="flex items-center">
            <span className="mr-1">🏘️</span>
            <span>{settlementCounts.town} Towns</span>
          </div>
        )}
        {settlementCounts.village > 0 && (
          <div className="flex items-center">
            <span className="mr-1">🏡</span>
            <span>{settlementCounts.village} Villages</span>
          </div>
        )}
        {settlementCounts.hamlet > 0 && (
          <div className="flex items-center">
            <span className="mr-1">🏠</span>
            <span>{settlementCounts.hamlet} Hamlets</span>
          </div>
        )}
      </div>
      <div className="text-center p-2 bg-gray-600 rounded">
        <div className="text-sm text-gray-400">Total Population</div>
        <div className="text-xl font-bold text-blue-400">
          {totalPopulation.toLocaleString()}
        </div>
      </div>
    </div>
  );
};

export default SettlementSummary;
