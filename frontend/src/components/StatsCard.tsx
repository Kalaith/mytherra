import React from 'react';

interface StatsCardProps {
  title: string;
  value: number | string;
  description: string;
  color: 'yellow' | 'blue' | 'purple' | 'green' | 'red' | 'gray';
  icon?: string;
}

const StatsCard: React.FC<StatsCardProps> = ({ 
  title, 
  value, 
  description, 
  color, 
  icon 
}) => {
  const getColorClasses = (color: string) => {
    const colorMap = {
      yellow: 'bg-yellow-900 bg-opacity-50 border-yellow-600 text-yellow-300',
      blue: 'bg-blue-900 bg-opacity-50 border-blue-600 text-blue-300',
      purple: 'bg-purple-900 bg-opacity-50 border-purple-600 text-purple-300',
      green: 'bg-green-900 bg-opacity-50 border-green-600 text-green-300',
      red: 'bg-red-900 bg-opacity-50 border-red-600 text-red-300',
      gray: 'bg-gray-900 bg-opacity-50 border-gray-600 text-gray-300'
    };
    return colorMap[color as keyof typeof colorMap] || colorMap.gray;
  };

  const getValueClasses = (color: string) => {
    const colorMap = {
      yellow: 'text-yellow-100',
      blue: 'text-blue-100',
      purple: 'text-purple-100',
      green: 'text-green-100',
      red: 'text-red-100',
      gray: 'text-gray-100'
    };
    return colorMap[color as keyof typeof colorMap] || colorMap.gray;
  };

  const getDescriptionClasses = (color: string) => {
    const colorMap = {
      yellow: 'text-yellow-200',
      blue: 'text-blue-200',
      purple: 'text-purple-200',
      green: 'text-green-200',
      red: 'text-red-200',
      gray: 'text-gray-200'
    };
    return colorMap[color as keyof typeof colorMap] || colorMap.gray;
  };

  return (
    <div className={`p-4 rounded-lg border ${getColorClasses(color)}`}>
      <h3 className={`text-lg font-semibold mb-2 flex items-center ${getColorClasses(color)}`}>
        {icon && <span className="mr-2">{icon}</span>}
        {title}
      </h3>
      <p className={`text-2xl font-bold ${getValueClasses(color)}`}>{value}</p>
      <p className={`text-sm ${getDescriptionClasses(color)}`}>{description}</p>
    </div>
  );
};

export default StatsCard;
