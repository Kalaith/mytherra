/**
 * Utility function to render a progress bar
 * @param label Label for the progress bar
 * @param value Value for the progress bar (0-100)
 * @param barColorClass Tailwind CSS class for the bar color
 * @returns React element for a progress bar
 */
export const renderProgressBar = (
  label: string,
  value: number,
  barColorClass: string,
): React.ReactElement => {
  return (
    <div className="mb-1">
      <div className="flex items-center">
        <span className="text-xs w-16">{label}:</span>
        <div className="w-full bg-gray-300 rounded-full h-2.5">
          <div
            className={`${barColorClass} h-2.5 rounded-full`}
            style={{ width: `${value}%` }}
            title={`${label}: ${value}`}
          ></div>
        </div>
      </div>
    </div>
  );
};
