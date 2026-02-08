import { useContext } from 'react';
import { RegionContext, type RegionContextType } from './regionContext';

export const useRegions = (): RegionContextType => {
  return useContext(RegionContext);
};

