// F:\WebDevelopment\Mytherra\frontend\src\components\RegionDetailWrapper.tsx
import React, { useState, useEffect } from "react";
import { useRegions } from "../contexts/useRegionContext";
import { Region } from "../entities/region";
import { Hero } from "../entities/hero";
import { getHeroes } from "../api/apiService";
import RegionDetailPanel from "./RegionDetailPanel";

interface RegionDetailWrapperProps {
  region: Region;
}

const RegionDetailWrapper: React.FC<RegionDetailWrapperProps> = ({
  region,
}) => {
  const { getSettlementsByRegion, getLandmarksByRegion } = useRegions();
  const [heroes, setHeroes] = useState<Hero[]>([]);
  const [loading, setLoading] = useState(true);

  const settlements = getSettlementsByRegion(region.id);
  const landmarks = getLandmarksByRegion(region.id);

  // Fetch heroes and filter by region
  useEffect(() => {
    const fetchHeroes = async () => {
      try {
        setLoading(true);
        const allHeroes = await getHeroes();
        const regionHeroes = allHeroes.filter(
          (hero) => hero.regionId === region.id,
        );
        setHeroes(regionHeroes);
      } catch (error) {
        console.error("Error fetching heroes:", error);
        setHeroes([]);
      } finally {
        setLoading(false);
      }
    };

    fetchHeroes();
  }, [region.id]);

  return (
    <RegionDetailPanel
      region={region}
      settlements={settlements}
      landmarks={landmarks}
      heroes={heroes}
      loading={loading}
    />
  );
};

export default RegionDetailWrapper;
