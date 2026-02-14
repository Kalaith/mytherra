import React from 'react';
import { SortOption } from '../hooks/useHeroFilters';

interface HeroFiltersProps {
  searchTerm: string;
  setSearchTerm: (term: string) => void;
  showOnlyLiving: boolean;
  setShowOnlyLiving: (show: boolean | ((prev: boolean) => boolean)) => void;
  sortBy: SortOption;
  setSortBy: (sort: SortOption) => void;
}

const HeroFilters: React.FC<HeroFiltersProps> = ({
  searchTerm,
  setSearchTerm,
  showOnlyLiving,
  setShowOnlyLiving,
  sortBy,
  setSortBy,
}) => {
  return (
    <div className="mb-4 space-y-2">
      {/* Search Box */}
      <input
        type="text"
        placeholder="Search heroes by name, role, or description..."
        className="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
        value={searchTerm}
        onChange={e => setSearchTerm(e.target.value)}
      />

      {/* Filter and Sort Controls Row */}
      <div className="flex flex-wrap items-center justify-between gap-2">
        {/* Living/Dead Filter */}
        <div className="flex items-center">
          <label className="inline-flex items-center cursor-pointer">
            <input
              type="checkbox"
              checked={showOnlyLiving}
              onChange={() => setShowOnlyLiving(prev => !prev)}
              className="sr-only peer"
            />
            <div className="relative w-11 h-6 bg-gray-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            <span className="ml-3 text-sm font-medium text-gray-300">Show only living heroes</span>
          </label>
        </div>

        {/* Sort Options */}
        <div className="flex items-center space-x-2">
          <span className="text-sm text-gray-300">Sort by:</span>
          <select
            value={sortBy}
            onChange={e => setSortBy(e.target.value as SortOption)}
            className="bg-gray-700 text-white border border-gray-600 rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="power">Level</option>
            <option value="age">Age</option>
            <option value="name">Name</option>
          </select>
        </div>
      </div>
    </div>
  );
};

export default HeroFilters;
