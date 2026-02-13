import React from "react";
import { Region } from "../entities/region";
import { useRegions } from "../contexts/useRegionContext";
import RegionCard from "./RegionCard";

interface RegionGridProps {
  regions: Region[];
  selectedRegion: Region | null;
  onSelectRegion: (region: Region | null) => void;
}

const RegionGrid: React.FC<RegionGridProps> = ({
  regions,
  selectedRegion,
  onSelectRegion,
}) => {
  const { getSettlementsByRegion, getLandmarksByRegion } = useRegions();

  if (regions.length === 0) {
    return <div className="text-center p-4">No regions to display.</div>;
  }

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl">
      <h2 className="text-2xl font-bold mb-4 text-center">
        World Map (Click a region to select)
      </h2>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {regions.map((region) => (
          <RegionCard
            key={region.id}
            region={region}
            isSelected={selectedRegion?.id === region.id}
            onSelect={() => onSelectRegion(region)}
            settlementCount={getSettlementsByRegion(region.id).length}
            landmarkCount={getLandmarksByRegion(region.id).length}
          />
        ))}
      </div>
    </div>
  );
};

export default RegionGrid;
