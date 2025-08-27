import React from 'react';

interface PaginationProps {
  currentPage: number;
  onPageChange: (page: number) => void;
  hasNextPage: boolean;
  hasPreviousPage: boolean;
  isLoading?: boolean;
  onRefresh?: () => void;
  showRefresh?: boolean;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  onPageChange,
  hasNextPage,
  hasPreviousPage,
  isLoading = false,
  onRefresh,
  showRefresh = true
}) => {
  const handlePrevious = () => {
    if (hasPreviousPage && !isLoading) {
      onPageChange(Math.max(1, currentPage - 1));
    }
  };

  const handleNext = () => {
    if (hasNextPage && !isLoading) {
      onPageChange(currentPage + 1);
    }
  };

  const handleRefresh = () => {
    if (onRefresh && !isLoading) {
      onRefresh();
    }
  };

  return (
    <div className="mt-8 space-y-4">
      {/* Main Pagination */}
      <div className="flex justify-center items-center space-x-4">
        <button 
          onClick={handlePrevious}
          disabled={!hasPreviousPage || isLoading}
          className="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Previous Page
        </button>
        <span className="text-gray-300 font-medium">
          Page {currentPage}
        </span>
        <button 
          onClick={handleNext}
          disabled={!hasNextPage || isLoading}
          className="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Next Page
        </button>
      </div>

      {/* Refresh Button */}
      {showRefresh && onRefresh && (
        <div className="flex justify-center">
          <button
            onClick={handleRefresh}
            disabled={isLoading}
            className="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {isLoading ? 'Loading...' : 'Refresh'}
          </button>
        </div>
      )}
    </div>
  );
};

export default Pagination;
