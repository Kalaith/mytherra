import React from 'react';
import { Hero } from '../../entities/hero';
import HeroCard from '../HeroCard';

interface RegionHeroesListProps {
  heroes: Hero[];
  loading: boolean;
  onSelectHero?: (hero: Hero) => void;
}

const RegionHeroesList: React.FC<RegionHeroesListProps> = ({ heroes, loading, onSelectHero }) => {
  if (loading) {
    return (
      <div className="text-center py-8 text-gray-400">
        Loading heroes...
      </div>
    );
  }

  const livingHeroes = heroes.filter(hero => hero.isAlive !== false);

  if (livingHeroes.length === 0) {
    return (
      <div className="text-center py-8 text-gray-400">
        No living heroes found in this region
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
      {livingHeroes.map(hero => (
        <HeroCard
          key={hero.id}
          hero={hero}
          isSelected={false}
          onSelectHero={(selectedHero) => onSelectHero?.(selectedHero!)}
        />
      ))}
    </div>
  );
};

export default RegionHeroesList;
