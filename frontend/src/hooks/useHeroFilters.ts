import { useState, useMemo } from 'react';
import { Hero } from '../entities/hero';

export type SortOption = 'power' | 'age' | 'name';

interface UseHeroFiltersProps {
  heroes: Hero[];
}

export const useHeroFilters = ({ heroes }: UseHeroFiltersProps) => {
  const [searchTerm, setSearchTerm] = useState('');
  const [showOnlyLiving, setShowOnlyLiving] = useState(false);
  const [sortBy, setSortBy] = useState<SortOption>('power');

  const filteredAndSortedHeroes = useMemo(() => {
    return heroes
      .filter(
        hero =>
          // Text search filter
          (hero.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            hero.role.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (hero.description &&
              hero.description.toLowerCase().includes(searchTerm.toLowerCase()))) &&
          // Living status filter - consider special statuses like "undead" as non-living
          (showOnlyLiving
            ? hero.isAlive !== false && hero.status !== 'undead' && hero.status !== 'deceased'
            : true)
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
        } else if (sortBy === 'age') {
          const ageA = a.age || 0;
          const ageB = b.age || 0;
          return ageB - ageA; // Older first
        } else if (sortBy === 'name') {
          return a.name.localeCompare(b.name); // Alphabetical by name
        }
        return 0;
      });
  }, [heroes, searchTerm, showOnlyLiving, sortBy]);

  return {
    searchTerm,
    setSearchTerm,
    showOnlyLiving,
    setShowOnlyLiving,
    sortBy,
    setSortBy,
    filteredAndSortedHeroes,
  };
};
