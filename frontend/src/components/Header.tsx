import React from 'react';
import { GameStatus } from '../api/apiService';
import UserInfo from './UserInfo';
import { useAuth } from '../contexts/AuthContext';

interface HeaderProps {
  gameStatus: GameStatus | null;
}

const Header: React.FC<HeaderProps> = ({ gameStatus }) => {
  const { user } = useAuth();

  return (
    <header className="bg-gray-800 border-b border-gray-700">
      <div className="container mx-auto px-4 py-6">
        <div className="flex items-center justify-between">
          <div className="text-center flex-1">
            <h1 className="text-4xl md:text-5xl font-bold text-yellow-400">Mytherra</h1>
            {gameStatus && (
              <div className="text-lg md:text-xl text-gray-300 mt-2">
                <span className="mr-6">Current Year: {gameStatus.currentYear}</span>
                {/* Show server divine favor or user's favor if available */}
                <span>Divine Favor: {user?.divine_favor || gameStatus.divineFavor}</span>
              </div>
            )}
          </div>
          
          <div className="flex items-center">
            <UserInfo />
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
