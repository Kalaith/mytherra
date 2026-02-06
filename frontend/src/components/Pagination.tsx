import React from 'react';

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  onPageChange: (page: number) => void;
  isLoading?: boolean;
  onRefresh?: () => void;
  showRefresh?: boolean;
}

const Pagination: React.FC<PaginationProps> = ({
  currentPage,
  totalPages,
  onPageChange,
  isLoading = false,
  onRefresh,
  showRefresh = true
}) => {
  const hasNextPage = currentPage < totalPages;
  const hasPreviousPage = currentPage > 1;

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

  const renderPageNumbers = () => {
    if (totalPages <= 7) {
      return Array.from({ length: totalPages }, (_, index) => (
        <button
          key={index + 1}
          onClick={() => onPageChange(index + 1)}
          className={`px-3 py-1 rounded ${currentPage === index + 1
              ? 'bg-blue-500'
              : 'bg-gray-700 hover:bg-gray-600'
            }`}
        >
          {index + 1}
        </button>
      ));
    }

    return (
      <>
        <button
          onClick={() => onPageChange(1)}
          className={`px-3 py-1 rounded ${currentPage === 1 ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
            }`}
        >
          1
        </button>

        {currentPage > 3 && <span className="px-3 py-1">...</span>}

        {Array.from({ length: 5 }, (_, i) => {
          const pageNum = Math.max(2, Math.min(currentPage - 2 + i, totalPages - 1));
          if (pageNum <= 1 || pageNum >= totalPages) return null;
          if (pageNum < currentPage - 2 || pageNum > currentPage + 2) return null;

          return (
            <button
              key={pageNum}
              onClick={() => onPageChange(pageNum)}
              className={`px-3 py-1 rounded ${currentPage === pageNum ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
                }`}
            >
              {pageNum}
            </button>
          );
        })}

        {currentPage < totalPages - 2 && <span className="px-3 py-1">...</span>}

        <button
          onClick={() => onPageChange(totalPages)}
          className={`px-3 py-1 rounded ${currentPage === totalPages ? 'bg-blue-500' : 'bg-gray-700 hover:bg-gray-600'
            }`}
        >
          {totalPages}
        </button>
      </>
    );
  };

  return (
    <div className="mt-8 space-y-4">
      {/* Main Pagination */}
      <div className="flex justify-center items-center space-x-2">
        <button
          onClick={handlePrevious}
          disabled={!hasPreviousPage || isLoading}
          className="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Previous
        </button>

        <div className="flex space-x-1">
          {renderPageNumbers()}
        </div>

        <button
          onClick={handleNext}
          disabled={!hasNextPage || isLoading}
          className="px-3 py-1 rounded bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Next
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
