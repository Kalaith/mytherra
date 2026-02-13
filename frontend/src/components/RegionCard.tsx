import React from "react";
import { Region } from "../entities/region";
import { renderProgressBar } from "../utils/uiUtils.tsx";

interface RegionCardProps {
  region: Region;
  isSelected: boolean;
  onSelect: () => void;
  settlementCount: number;
  landmarkCount: number;
}

const RegionCard: React.FC<RegionCardProps> = ({
  region,
  isSelected,
  onSelect,
  settlementCount,
  landmarkCount,
}) => {
  return (
    <div
      className={`p-4 rounded-md shadow-md hover:shadow-lg transition-all duration-200 ease-in-out cursor-pointer 
                 ${isSelected ? "ring-4 ring-yellow-400 scale-105" : "hover:ring-2 hover:ring-blue-400"}`}
      style={{ backgroundColor: region.color || "#7f8c8d" }}
      onClick={onSelect}
      title={`Click to select ${region.name}`}
    >
      <h3 className="text-xl font-semibold mb-1 truncate" title={region.name}>
        {region.name}
      </h3>
      <p className="text-sm capitalize">Status: {region.status}</p>

      {/* Settlement and Landmark counts */}
      <div className="flex justify-between text-xs mt-1 mb-2">
        <span>🏘️ {settlementCount} settlements</span>
        <span>🏛️ {landmarkCount} landmarks</span>
      </div>
      {/* Regional Stats Progress Bars */}
      <div className="mt-2">
        {renderProgressBar("Prosperity", region.prosperity, "bg-green-500")}
        {renderProgressBar("Chaos", region.chaos, "bg-red-500")}
        {renderProgressBar("Magic", region.magicAffinity, "bg-purple-500")}
      </div>
    </div>
  );
};

export default RegionCard;
