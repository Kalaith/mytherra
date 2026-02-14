import React from 'react';
import { Region } from '../../entities/region';

interface RegionHeaderProps {
  region: Region;
  getStatusColor: (status: string) => string;
}

const RegionHeader: React.FC<RegionHeaderProps> = ({ region, getStatusColor }) => {
  return (
    <div className="flex justify-between items-start mb-4">
      <div>
        <h2 className="text-2xl font-bold" style={{ color: region.color }}>
          {region.name}
        </h2>
        <p className={`text-lg font-medium ${getStatusColor(region.status)}`}>
          Status: {region.status}
        </p>
      </div>
      {region.divineResonance && (
        <div className="text-right">
          <div className="text-sm text-gray-400">Divine Resonance</div>
          <div className="text-lg font-bold text-purple-400">{region.divineResonance}%</div>
        </div>
      )}
    </div>
  );
};

export default RegionHeader;
