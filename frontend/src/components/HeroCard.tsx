import React from 'react';
import { Hero } from '../entities/hero';
import { useRegions } from '../contexts/RegionContext';
import { getCardBorderStyle, getAlignmentLabel } from '../utils/statusUtils';
import { getAlignmentColor } from '../utils/colorUtils';

interface HeroCardProps {
  hero: Hero;
  isSelected: boolean;
  onSelectHero: (hero: Hero | null) => void;
}

const HeroCard: React.FC<HeroCardProps> = ({ hero, isSelected, onSelectHero }) => {
  // Use the region context to get region data
  const { getRegionName } = useRegions();
  // Helper functions have been moved to utility files
  
  return (
    <div
      key={hero.id}
      className={`p-4 bg-gray-700 rounded-md shadow-md hover:shadow-lg transition-all duration-200 ease-in-out cursor-pointer
                 ${isSelected ? 'ring-4 ring-yellow-400 scale-105' : 'hover:ring-2 hover:ring-blue-400'}
                 ${getCardBorderStyle(hero.status, hero.isAlive)}`}
      onClick={() => onSelectHero(isSelected ? null : hero)}
      title={`Click to select ${hero.name}`}
    ><div className="flex justify-between items-start">
        <h3 className="text-xl font-semibold mb-1 truncate" title={hero.name}>{hero.name}</h3>
        {/* Status badges - only show for non-standard living states */}
        {hero.isAlive === false && !hero.status && (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-800 text-red-100">
            Deceased
          </span>
        )}
        {/* Special status badges for specific conditions */}
        {hero.status === 'deceased' && (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-800 text-red-100">
            Deceased
          </span>
        )}
        {hero.status === 'undead' && (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-800 text-purple-100">
            Undead
          </span>
        )}
        {hero.status === 'ascended' && (
          <span className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-600 text-yellow-100">
            Ascended
          </span>
        )}
      </div>      <p className="text-sm capitalize text-blue-300">Role: {hero.role}</p>
      {hero.level !== undefined && (
        <p className="text-sm text-yellow-400">Level: {hero.level}</p>
      )}      {hero.age !== undefined && (
        <p className="text-sm text-gray-300">Age: {hero.age}</p>      )}
      {/* Death reason (if applicable) */}
      {hero.isAlive === false && hero.deathReason && (
        <p className="text-xs text-red-400 mt-1">Cause of death: {hero.deathReason}</p>
      )}
      {/* Special status descriptions */}
      {hero.status === 'undead' && (
        <p className="text-xs text-purple-400 mt-1">Reanimated after death</p>
      )}      {hero.status === 'ascended' && (
        <p className="text-xs text-yellow-400 mt-1">Transcended mortal form</p>
      )}
      <p className="text-sm text-gray-300 mt-1">
        Region: {getRegionName(hero.regionId) || hero.regionId}
      </p>
        {/* Alignment display (when available) */}
      {hero.alignment && (
        <div className="mt-2 mb-1">
          <p className={`text-sm font-medium ${getAlignmentColor(hero.alignment)}`}>
            Alignment: {getAlignmentLabel(hero.alignment)}
          </p>
          {isSelected && hero.alignment.lastChange && (
            <p className="text-xs text-gray-400 italic">
              {hero.alignment.lastChange}
            </p>
          )}
          
          {/* Only show alignment bars when selected */}
          {isSelected && (
            <div className="mt-1 space-y-1">
              <div className="flex items-center">
                <span className="text-xs w-14 text-gray-300">Good:</span>
                <div className="w-full bg-gray-600 rounded-full h-1.5">
                  <div 
                    className="bg-green-500 h-1.5 rounded-full" 
                    style={{ width: `${hero.alignment.good}%` }}
                    title={`Good: ${hero.alignment.good}`}
                  ></div>
                </div>
              </div>
              <div className="flex items-center">
                <span className="text-xs w-14 text-gray-300">Chaotic:</span>
                <div className="w-full bg-gray-600 rounded-full h-1.5">
                  <div 
                    className="bg-yellow-500 h-1.5 rounded-full" 
                    style={{ width: `${hero.alignment.chaotic}%` }}
                    title={`Chaotic: ${hero.alignment.chaotic}`}
                  ></div>
                </div>
              </div>
            </div>
          )}
        </div>
      )}
      
      {/* Personality traits (when available) */}
      {hero.personalityTraits && hero.personalityTraits.length > 0 && (
        <div className="mt-2">
          <p className="text-xs font-semibold text-gray-300">Personality:</p>
          <div className="flex flex-wrap gap-1 mt-1">
            {hero.personalityTraits.map((trait, index) => (
              <span 
                key={index} 
                className="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-600 text-gray-200"
              >
                {trait}
              </span>
            ))}
          </div>
        </div>
      )}
      
      <p className="text-sm text-gray-400 mt-2 italic">{hero.description}</p>
      {hero.feats && hero.feats.length > 0 && (
        <div className="mt-3">
          <h4 className="text-xs font-semibold text-gray-300">Feats:</h4>
          <ul className="list-disc list-inside pl-1 text-xs text-gray-400">
            {hero.feats.map((feat, index) => (
              <li key={index}>{feat}</li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default HeroCard;
