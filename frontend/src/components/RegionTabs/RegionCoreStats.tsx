import React from 'react';
import { Region } from '../../entities/region';

interface RegionCoreStatsProps {
  region: Region;
}

const RegionCoreStats: React.FC<RegionCoreStatsProps> = ({ region }) => {
  return (
    <div className="grid grid-cols-3 gap-4 mb-4">
      <StatCard label="Prosperity" value={`${region.prosperity}%`} color="text-green-400" />
      <StatCard label="Chaos" value={`${region.chaos}%`} color="text-red-400" />
      <StatCard label="Magic Affinity" value={`${region.magicAffinity}%`} color="text-purple-400" />
    </div>
  );
};

interface StatCardProps {
  label: string;
  value: string;
  color: string;
}

const StatCard: React.FC<StatCardProps> = ({ label, value, color }) => (
  <div className="text-center p-3 bg-gray-700 rounded">
    <div className="text-sm text-gray-400">{label}</div>
    <div className={`text-xl font-bold ${color}`}>{value}</div>
  </div>
);

export default RegionCoreStats;
