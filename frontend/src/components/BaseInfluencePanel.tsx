import React, { type ReactNode } from 'react';
import { useInfluenceActions } from '../hooks/useInfluenceActions';

interface BaseInfluencePanelProps {
  currentDivineFavor: number;
  onActionSuccess: () => void;
  title?: string;
  children: ReactNode;
}

const BaseInfluencePanel: React.FC<BaseInfluencePanelProps> = ({
  currentDivineFavor,
  onActionSuccess,
  title = 'Divine Influence',
  children,
}) => {
  const { actionMessage } = useInfluenceActions(currentDivineFavor, onActionSuccess);

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl mt-6">
      <h2 className="text-2xl font-bold mb-4 text-center">{title}</h2>
      {actionMessage && (
        <p
          className={`text-center mb-3 p-2 rounded ${
            actionMessage.startsWith('Failed') ? 'bg-red-700' : 'bg-green-700'
          }`}
        >
          {actionMessage}
        </p>
      )}
      {children}
    </div>
  );
};

export default BaseInfluencePanel;

