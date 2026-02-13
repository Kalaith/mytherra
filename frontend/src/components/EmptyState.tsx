import React from "react";

interface EmptyStateProps {
  title: string;
  message: string;
  icon?: string;
  actionButton?: {
    label: string;
    onClick: () => void;
  };
  className?: string;
}

const EmptyState: React.FC<EmptyStateProps> = ({
  title,
  message,
  icon,
  actionButton,
  className = "",
}) => {
  return (
    <div className={`text-center p-8 bg-gray-800 rounded-lg ${className}`}>
      {icon && <div className="text-4xl mb-4">{icon}</div>}
      <h3 className="text-xl font-semibold mb-2">{title}</h3>
      <p className="text-gray-300 mb-4">{message}</p>
      {actionButton && (
        <button
          onClick={actionButton.onClick}
          className="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors"
        >
          {actionButton.label}
        </button>
      )}
    </div>
  );
};

export default EmptyState;
