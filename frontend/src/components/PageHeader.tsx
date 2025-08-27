import React from 'react';

interface PageHeaderProps {
  title: string;
  subtitle?: string;
  description?: string;
  icon?: string;
  className?: string;
}

const PageHeader: React.FC<PageHeaderProps> = ({
  title,
  subtitle,
  description,
  icon,
  className = ""
}) => {
  return (
    <div className={`mb-8 text-center ${className}`}>
      <h1 className="text-4xl font-bold mb-2 flex items-center justify-center">
        {icon && <span className="mr-3">{icon}</span>}
        {title}
      </h1>
      {subtitle && (
        <p className="text-xl text-gray-300 mb-2">
          {subtitle}
        </p>
      )}
      {description && (
        <p className="text-gray-400">
          {description}
        </p>
      )}
    </div>
  );
};

export default PageHeader;
