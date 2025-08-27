import React, { useState } from 'react';
import { useAuth } from '../contexts/AuthContext';

const UserInfo: React.FC = () => {
  const { user, logout, isAdmin } = useAuth();
  const [showDropdown, setShowDropdown] = useState(false);

  if (!user) {
    return null;
  }

  const handleLogout = async () => {
    setShowDropdown(false);
    await logout();
  };

  return (
    <div className="relative">
      <button
        onClick={() => setShowDropdown(!showDropdown)}
        className="flex items-center space-x-2 bg-gray-800 hover:bg-gray-700 px-3 py-2 rounded-lg transition duration-200"
      >
        <div className="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
          <span className="text-white text-sm font-bold">
            {user.display_name?.charAt(0).toUpperCase() || user.username?.charAt(0).toUpperCase() || 'U'}
          </span>
        </div>
        <div className="text-left hidden sm:block">
          <p className="text-white text-sm font-medium">{user.display_name || user.username}</p>
          <p className="text-gray-400 text-xs">
            {user.divine_influence} Influence | {user.divine_favor} Favor
          </p>
        </div>
        <svg
          className={`w-4 h-4 text-gray-400 transition-transform ${showDropdown ? 'rotate-180' : ''}`}
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      {showDropdown && (
        <div className="absolute right-0 mt-2 w-64 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50">
          <div className="p-4 border-b border-gray-700">
            <p className="text-white font-medium">{user.display_name || user.username}</p>
            <p className="text-gray-400 text-sm">{user.email}</p>
            <div className="flex items-center space-x-4 mt-2">
              <span className="text-xs text-gray-400">
                Role: <span className="text-blue-400 font-medium">{user.role}</span>
              </span>
              {isAdmin() && (
                <span className="bg-red-600 text-white text-xs px-2 py-1 rounded">Admin</span>
              )}
            </div>
          </div>
          
          <div className="p-2">
            <div className="grid grid-cols-2 gap-2 mb-3">
              <div className="bg-gray-700 p-2 rounded text-center">
                <p className="text-blue-400 font-bold">{user.divine_influence}</p>
                <p className="text-xs text-gray-400">Divine Influence</p>
              </div>
              <div className="bg-gray-700 p-2 rounded text-center">
                <p className="text-green-400 font-bold">{user.divine_favor}</p>
                <p className="text-xs text-gray-400">Divine Favor</p>
              </div>
            </div>
            
            <button
              onClick={handleLogout}
              className="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-3 rounded transition duration-200"
            >
              Logout
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default UserInfo;
