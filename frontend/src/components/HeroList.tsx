import React, { useState, useEffect } from 'react';
import { Hero } from '../entities/hero';
import HeroCard from './HeroCard';
import { useRegions } from '../contexts/RegionContext';
import Pagination from './Pagination';
import HeroFilters from './HeroFilters';
import { useHeroFilters } from '../hooks/useHeroFilters';

interface HeroListProps {
  heroes: Hero[];
  selectedHero: Hero | null;
  onSelectHero: (hero: Hero | null) => void;
  isLoading?: boolean;
  error?: string | null;
}

const HeroList: React.FC<HeroListProps> = ({ heroes, selectedHero, onSelectHero, isLoading: isLoadingHeroes, error: heroError }) => {
  const [currentPage, setCurrentPage] = useState(1);
  const heroesPerPage = 10;

  // Access region data
  const { isLoading: isLoadingRegions } = useRegions();

  // Custom hook for filtering and sorting
  const {
    searchTerm,
    setSearchTerm,
    showOnlyLiving,
    setShowOnlyLiving,
    sortBy,
    setSortBy,
    filteredAndSortedHeroes
  } = useHeroFilters({ heroes });

  // Combined loading and error states
  const isLoading = isLoadingHeroes || isLoadingRegions;
  const error = heroError;

  // Reset to first page when heroes change or filters change
  useEffect(() => {
    setCurrentPage(1);
  }, [heroes.length, searchTerm, showOnlyLiving, sortBy]);

  // Get current page of heroes
  const indexOfLastHero = currentPage * heroesPerPage;
  const indexOfFirstHero = indexOfLastHero - heroesPerPage;
  const currentHeroes = filteredAndSortedHeroes.slice(indexOfFirstHero, indexOfLastHero);
  const totalPages = Math.ceil(filteredAndSortedHeroes.length / heroesPerPage);

  // Change page
  const paginate = (pageNumber: number) => setCurrentPage(pageNumber);

  if (isLoading) {
    return <div className="text-center p-4">Loading heroes...</div>;
  }

  if (error) {
    return <div className="text-center p-4 text-red-500">Error loading heroes: {error}</div>;
  }

  if (heroes.length === 0) {
    return <div className="text-center p-4">No heroes have emerged yet.</div>;
  }

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl mt-6">
      <h2 className="text-2xl font-bold mb-2 text-center">Emergent Heroes (Click to select)</h2>

      {/* Hero count stats */}
      <div className="flex justify-center mb-4 gap-4 text-sm">
        <div className="flex items-center">
          <span className="w-3 h-3 rounded-full bg-green-600 mr-2"></span>
          <span>{heroes.filter(h => h.isAlive !== false).length} Living</span>
        </div>
        <div className="flex items-center">
          <span className="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
          <span>{heroes.filter(h => h.isAlive === false).length} Deceased</span>
        </div>
        <div className="flex items-center">
          <span className="w-3 h-3 rounded-full bg-gray-400 mr-2"></span>
          <span>{heroes.length} Total</span>
        </div>
      </div>

      <HeroFilters
        searchTerm={searchTerm}
        setSearchTerm={setSearchTerm}
        showOnlyLiving={showOnlyLiving}
        setShowOnlyLiving={setShowOnlyLiving}
        sortBy={sortBy}
        setSortBy={setSortBy}
      />

      {/* Display message if no heroes match search */}
      {currentHeroes.length === 0 && (
        <div className="text-center p-4">No heroes match your search criteria.</div>
      )}

      {/* Heroes Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {currentHeroes.map((hero) => {
          const isSelected = selectedHero?.id === hero.id;
          return (
            <HeroCard
              key={hero.id}
              hero={hero}
              isSelected={isSelected}
              onSelectHero={onSelectHero}
            />
          );
        })}
      </div>

      {/* Pagination */}
      {filteredAndSortedHeroes.length > heroesPerPage && (
        <Pagination
          currentPage={currentPage}
          totalPages={totalPages}
          onPageChange={paginate}
          showRefresh={false}
        />
      )}

      {/* Showing stats */}
      <div className="mt-3 text-center text-sm text-gray-400">
        {filteredAndSortedHeroes.length > 0 ? (
          <>
            Showing {indexOfFirstHero + 1}-{Math.min(indexOfLastHero, filteredAndSortedHeroes.length)} of {filteredAndSortedHeroes.length} heroes
            {searchTerm && ` (filtered from ${heroes.length})`}
            {showOnlyLiving && ` (showing only living heroes)`}
          </>
        ) : (
          <>
            No heroes match your criteria.
            {(searchTerm || showOnlyLiving) && (
              <button
                className="ml-2 text-blue-400 hover:underline"
                onClick={() => {
                  setSearchTerm('');
                  setShowOnlyLiving(false);
                }}
              >
                Clear filters
              </button>
            )}
          </>
        )}
      </div>
    </div>
  );
};

export default HeroList;
