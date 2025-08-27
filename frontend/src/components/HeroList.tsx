// F:\WebDevelopment\Mytherra\frontend\src\components\HeroList.tsx
import React, { useState, useEffect, useMemo } from 'react';
import { Hero } from '../entities/hero';
import HeroCard from './HeroCard';
import { useRegions } from '../contexts/RegionContext';

interface HeroListProps {
  heroes: Hero[];
  selectedHero: Hero | null;
  onSelectHero: (hero: Hero | null) => void;
  isLoading?: boolean;
  error?: string | null;
}

const HeroList: React.FC<HeroListProps> = ({ heroes, selectedHero, onSelectHero, isLoading: isLoadingHeroes, error: heroError }) => {
  const [currentPage, setCurrentPage] = useState(1);
  const [searchTerm, setSearchTerm] = useState('');
  const [showOnlyLiving, setShowOnlyLiving] = useState(false);
  const [sortBy, setSortBy] = useState<'power' | 'age' | 'name'>('power');
  const heroesPerPage = 10;
  
  // Access region data
  const { isLoading: isLoadingRegions } = useRegions();
  
  // Combined loading and error states
  const isLoading = isLoadingHeroes || isLoadingRegions;
  const error = heroError;
  
  // Reset to first page when heroes change or filters change
  useEffect(() => {
    setCurrentPage(1);
  }, [heroes.length, searchTerm, showOnlyLiving, sortBy]);

  // Filter and sort heroes based on search term and Level
  const filteredAndSortedHeroes = useMemo(() => {
    return heroes      .filter(hero => 
        // Text search filter
        (hero.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
         hero.role.toLowerCase().includes(searchTerm.toLowerCase()) ||
         (hero.description && hero.description.toLowerCase().includes(searchTerm.toLowerCase()))) &&
        // Living status filter - consider special statuses like "undead" as non-living
        (showOnlyLiving ? (hero.isAlive !== false && hero.status !== 'undead' && hero.status !== 'deceased') : true)
      )
      .sort((a, b) => {
        // Sort by status priority (living first, then special statuses, then deceased)
        // Status priority: living > ascended > undead > deceased
        
        // Helper function to get numeric value for status priority
        const getStatusPriority = (hero: Hero): number => {
          if (hero.status === 'ascended') return 1;
          if (hero.status === 'undead') return 2;
          if (hero.status === 'deceased' || hero.isAlive === false) return 3;
          return 0; // Living
        };
        
        const priorityA = getStatusPriority(a);
        const priorityB = getStatusPriority(b);
        
        if (priorityA !== priorityB) {
          return priorityA - priorityB; // Lower priority number first
        }
        
        // Then sort by selected attribute
        if (sortBy === 'power') {
          const powerA = a.level || 0;
          const powerB = b.level || 0;
          return powerB - powerA; // Higher power first
        } 
        else if (sortBy === 'age') {
          const ageA = a.age || 0;
          const ageB = b.age || 0;
          return ageB - ageA; // Older first
        }
        else if (sortBy === 'name') {
          return a.name.localeCompare(b.name); // Alphabetical by name
        }
        return 0;
      });
  }, [heroes, searchTerm, showOnlyLiving, sortBy]);

  // Get current page of heroes
  const indexOfLastHero = currentPage * heroesPerPage;
  const indexOfFirstHero = indexOfLastHero - heroesPerPage;
  const currentHeroes = filteredAndSortedHeroes.slice(indexOfFirstHero, indexOfLastHero);
  const totalPages = Math.ceil(filteredAndSortedHeroes.length / heroesPerPage);

  // Change page
  const paginate = (pageNumber: number) => setCurrentPage(pageNumber);
  
  // Previous and next page buttons
  const goToPreviousPage = () => setCurrentPage(prev => Math.max(prev - 1, 1));
  const goToNextPage = () => setCurrentPage(prev => Math.min(prev + 1, totalPages));

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
      
      {/* Search and filter controls */}
      <div className="mb-4 space-y-2">
        {/* Search Box */}
        <input
          type="text"
          placeholder="Search heroes by name, role, or description..."
          className="w-full p-2 bg-gray-700 text-white border border-gray-600 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
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
            <select              value={sortBy}
              onChange={(e) => setSortBy(e.target.value as 'power' | 'age' | 'name')}
              className="bg-gray-700 text-white border border-gray-600 rounded p-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="power">Level</option>
              <option value="age">Age</option>
              <option value="name">Name</option>
            </select>
          </div>
        </div>
      </div>
      
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
        <div className="mt-6 flex justify-center items-center space-x-2">
          <button
            onClick={goToPreviousPage}
            disabled={currentPage === 1}
            className={`px-3 py-1 rounded ${
              currentPage === 1 
                ? 'bg-gray-600 cursor-not-allowed' 
                : 'bg-blue-600 hover:bg-blue-700'
            }`}
          >
            Previous
          </button>
            <div className="flex space-x-1">
            {totalPages <= 7 ? (
              // If there are 7 or fewer pages, show all page numbers
              Array.from({ length: totalPages }, (_, index) => (
                <button
                  key={index + 1}
                  onClick={() => paginate(index + 1)}
                  className={`px-3 py-1 rounded ${
                    currentPage === index + 1 
                      ? 'bg-blue-500' 
                      : 'bg-gray-700 hover:bg-gray-600'
                  }`}
                >
                  {index + 1}
                </button>
              ))
            ) : (
              // If there are more than 7 pages, show a limited set with ellipsis
              <>
                {/* First page */}
                <button
                  onClick={() => paginate(1)}
                  className={`px-3 py-1 rounded ${
                    currentPage === 1 ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
                  }`}
                >
                  1
                </button>
                
                {/* Ellipsis or second page */}
                {currentPage > 3 && (
                  <span className="px-3 py-1">...</span>
                )}
                
                {/* Pages around current page */}
                {Array.from({ length: 5 }, (_, i) => {
                  const pageNum = Math.max(2, Math.min(currentPage - 2 + i, totalPages - 1));
                  if (pageNum <= 1 || pageNum >= totalPages) return null;
                  if (pageNum < currentPage - 2 || pageNum > currentPage + 2) return null;
                  
                  return (
                    <button
                      key={pageNum}
                      onClick={() => paginate(pageNum)}
                      className={`px-3 py-1 rounded ${
                        currentPage === pageNum ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
                      }`}
                    >
                      {pageNum}
                    </button>
                  );
                })}
                
                {/* Ellipsis or second-to-last page */}
                {currentPage < totalPages - 2 && (
                  <span className="px-3 py-1">...</span>
                )}
                
                {/* Last page */}
                <button
                  onClick={() => paginate(totalPages)}
                  className={`px-3 py-1 rounded ${
                    currentPage === totalPages ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
                  }`}
                >
                  {totalPages}
                </button>
              </>
            )}
          </div>
          
          <button
            onClick={goToNextPage}
            disabled={currentPage === totalPages}
            className={`px-3 py-1 rounded ${
              currentPage === totalPages 
                ? 'bg-gray-600 cursor-not-allowed' 
                : 'bg-blue-600 hover:bg-blue-700'
            }`}
          >
            Next
          </button>
        </div>
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
