/**
 * Utility functions for handling color-related logic in the Mytherra UI
 */

/**
 * Returns the appropriate text color class for a confidence level
 * @param confidence The confidence level string
 * @returns Tailwind CSS color class for text
 */
export const getConfidenceColor = (confidence: string): string => {
  switch (confidence) {
    case 'long_shot': return 'text-red-400';
    case 'possible': return 'text-yellow-400';
    case 'likely': return 'text-green-400';
    case 'near_certain': return 'text-blue-400';
    default: return 'text-gray-400';
  }
};

/**
 * Returns the appropriate text color class for a bet status
 * @param status The betting status string
 * @returns Tailwind CSS color class for text
 */
export const getBetStatusColor = (status: string): string => {
  switch (status) {
    case 'active': return 'text-blue-400';
    case 'won': return 'text-green-400';
    case 'lost': return 'text-red-400';
    case 'expired': return 'text-gray-400';
    default: return 'text-gray-400';
  }
};

/**
 * Returns the appropriate text color class based on character alignment
 * @param alignment The character's alignment object
 * @returns Tailwind CSS color class for text
 */
export const getAlignmentColor = (alignment: { good: number; chaotic: number } | undefined): string => {
  if (!alignment) return 'text-gray-400';
  
  // Colors based on good vs evil primarily
  if (alignment.good >= 75) return 'text-green-400';
  if (alignment.good <= 25) return 'text-red-400';
  if (alignment.chaotic >= 75) return 'text-yellow-400';
  if (alignment.chaotic <= 25) return 'text-blue-400';
  
  return 'text-gray-400'; // Neutral
};

/**
 * Helper function to determine button state classes
 * @param baseClass Base button class string
 * @param isDisabled Whether the button should be disabled
 * @param isLoading Whether the button is in a loading state
 * @returns Complete class string for button
 */
export const getButtonStateClass = (
  baseClass: string, 
  isDisabled: boolean, 
  isLoading: boolean
): string => {
  let finalClass = baseClass;
  
  if (isDisabled) {
    finalClass += ' opacity-50 cursor-not-allowed';
  } else if (isLoading) {
    finalClass += ' opacity-75 cursor-wait';
  }
  
  return finalClass;
};
