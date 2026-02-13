import React from "react";
import { Region } from "../../entities/region";

interface RegionInfluenceCostsProps {
  region: Region;
}

const RegionInfluenceCosts: React.FC<RegionInfluenceCostsProps> = ({
  region,
}) => {
  if (!region.influenceActionCosts) return null;

  return (
    <div className="p-3 bg-gray-700 rounded">
      <h3 className="text-lg font-semibold mb-2">Divine Influence Costs</h3>
      <div className="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
        {region.influenceActionCosts.blessRegion && (
          <div className="flex justify-between">
            <span>Bless Region:</span>
            <span className="text-yellow-400">
              {region.influenceActionCosts.blessRegion}
            </span>
          </div>
        )}
        {region.influenceActionCosts.corruptRegion && (
          <div className="flex justify-between">
            <span>Corrupt Region:</span>
            <span className="text-yellow-400">
              {region.influenceActionCosts.corruptRegion}
            </span>
          </div>
        )}
        {region.influenceActionCosts.guideResearch && (
          <div className="flex justify-between">
            <span>Guide Research:</span>
            <span className="text-yellow-400">
              {region.influenceActionCosts.guideResearch}
            </span>
          </div>
        )}
      </div>
    </div>
  );
};

export default RegionInfluenceCosts;
