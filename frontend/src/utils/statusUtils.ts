/**
 * Utility functions for handling various status-related logic in the Mytherra UI
 */

/**
 * Returns a border styling class based on a hero's status
 * @param heroStatus The hero's status string
 * @param isAlive Whether the hero is alive
 * @returns Tailwind CSS border class string
 */
export const getCardBorderStyle = (
  heroStatus: string | undefined,
  isAlive: boolean | undefined
): string => {
  if (heroStatus === 'undead') return 'border-l-4 border-purple-800 bg-opacity-90';
  if (heroStatus === 'ascended') return 'border-l-4 border-yellow-600 bg-opacity-90';
  if (isAlive === false) return 'border-l-4 border-red-800 bg-opacity-70';
  return '';
};

/**
 * Returns a human-readable alignment label based on alignment stats
 * @param alignment Character alignment object with good and chaotic values
 * @returns String representation of the character's alignment
 */
export const getAlignmentLabel = (
  alignment: { good: number; chaotic: number } | undefined
): string | null => {
  if (!alignment) return null;
  
  // Good vs Evil axis
  let morality = 'Neutral';
  if (alignment.good >= 75) morality = 'Good';
  else if (alignment.good >= 60) morality = 'Good';
  else if (alignment.good <= 25) morality = 'Evil';
  else if (alignment.good <= 40) morality = 'Evil';
  
  // Law vs Chaos axis
  let order = 'Neutral';
  if (alignment.chaotic >= 75) order = 'Chaotic';
  else if (alignment.chaotic >= 60) order = 'Chaotic';
  else if (alignment.chaotic <= 25) order = 'Lawful';
  else if (alignment.chaotic <= 40) order = 'Lawful';
  
  // If both axes are neutral, just say "True Neutral"
  if (morality === 'Neutral' && order === 'Neutral') return 'True Neutral';
  
  return `${order} ${morality}`;
};
