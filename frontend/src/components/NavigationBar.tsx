// F:\WebDevelopment\Mytherra\frontend\src\components\NavigationBar.tsx
import React from 'react';
import { Link, useLocation } from 'react-router-dom';

const NavigationBar: React.FC = () => {
  const location = useLocation();
  const tabs = [
    { id: 'events', label: 'Events', icon: '📜', path: '/' },
    { id: 'world', label: 'World Map', icon: '🗺️', path: '/world-map' },
    { id: 'heroes', label: 'Heroes', icon: '⚔️', path: '/heroes' },
    { id: 'betting', label: 'Divine Betting', icon: '⚡', path: '/betting' },
    { id: 'dashboard', label: 'Statistics', icon: '📊', path: '/dashboard' },
  ] as const;

  const getActiveTab = (): string => {
    if (location.pathname.includes('/heroes')) return 'heroes';
    if (location.pathname.includes('/betting')) return 'betting';
    if (location.pathname.includes('/world-map')) return 'world';
    if (location.pathname.includes('/dashboard')) return 'dashboard';
    return 'events';
  };

  const activeTab = getActiveTab();

  return (
    <nav className="bg-gray-800 border-b border-gray-700 sticky top-0 z-50">
      <div className="container mx-auto px-4 py-3">
        <div className="flex justify-center space-x-1 md:space-x-4">
          {tabs.map(tab => (
            <Link
              key={tab.id}
              to={tab.path}
              className={`px-3 md:px-4 py-2 rounded-lg font-medium transition-all duration-200 flex items-center ${
                activeTab === tab.id
                  ? 'bg-yellow-500 text-gray-900 shadow-lg scale-105'
                  : 'bg-gray-700 text-gray-200 hover:bg-gray-600 hover:scale-102'
              }`}
            >
              <span className="mr-1 md:mr-2 text-lg">{tab.icon}</span>
              <span className="hidden sm:inline text-sm md:text-base">{tab.label}</span>
              <span className="sm:hidden text-xs">{tab.label.split(' ')[0]}</span>
            </Link>
          ))}
        </div>
      </div>
    </nav>
  );
};

export default NavigationBar;
