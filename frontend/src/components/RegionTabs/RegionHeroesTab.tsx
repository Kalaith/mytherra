import React from "react";
import { Hero } from "../../entities/hero";
import RegionHeroesList from "./RegionHeroesList";

interface RegionHeroesTabProps {
  heroes: Hero[];
  loading: boolean;
  onSelectHero?: (hero: Hero) => void;
}

const RegionHeroesTab: React.FC<RegionHeroesTabProps> = ({
  heroes,
  loading,
  onSelectHero,
}) => {
  return (
    <div>
      <RegionHeroesList
        heroes={heroes}
        loading={loading}
        onSelectHero={onSelectHero}
      />
    </div>
  );
};

export default RegionHeroesTab;
