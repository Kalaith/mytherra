/**
 * Utility functions for date formatting
 */

/**
 * Format a date value consistently across the application
 * @param dateValue A date string or Date object
 * @param options Optional Intl.DateTimeFormatOptions
 * @returns Formatted date string
 */
export const formatDate = (
  dateValue: string | Date | number,
  options?: Intl.DateTimeFormatOptions
): string => {
  const date = new Date(dateValue);
  return date.toLocaleString(undefined, options);
};
