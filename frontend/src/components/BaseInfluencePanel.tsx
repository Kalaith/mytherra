import React, { useState, ReactNode } from 'react';
import { sendInfluenceAction, InfluenceActionPayload } from '../api/apiService';

interface BaseInfluencePanelProps {
  currentDivineFavor: number;
  onActionSuccess: () => void;
  title?: string;
  children: ReactNode;
}

export interface InfluenceAction {
  action: string;
  entityId: string;
  entityName: string;
  entityType: 'region' | 'hero';
}

export interface UseInfluenceActionsReturn {
  isLoadingAction: Record<string, boolean>;
  actionMessage: string | null;
  handleInfluenceAction: (action: string, entityId: string, entityName: string, entityType: 'region' | 'hero') => Promise<void>;
  getButtonClass: (baseClass: string, cost?: number, actionKey?: string) => string;
}

export const useInfluenceActions = (
  currentDivineFavor: number,
  onActionSuccess: () => void
): UseInfluenceActionsReturn => {
  const [isLoadingAction, setIsLoadingAction] = useState<Record<string, boolean>>({});
  const [actionMessage, setActionMessage] = useState<string | null>(null);
  const handleInfluenceAction = async (
    action: string,
    entityId: string,
    _entityName: string,
    entityType: 'region' | 'hero'
  ) => {
    const actionKey = `${action}-${entityId}`;

    setIsLoadingAction(prev => ({ ...prev, [actionKey]: true }));
    setActionMessage(null);

    const payload: InfluenceActionPayload = {
      action,
      entityId,
      entityType,
    };

    try {
      const response = await sendInfluenceAction(payload);
      console.log('Influence action response:', response);
      
      if (response && (response.success || response.message)) {
        setActionMessage(response.message || 'Action completed successfully!');
        onActionSuccess();
      } else {
        setActionMessage('Action completed!');
        onActionSuccess();
      }
    } catch (error: any) {
      console.error('Failed to send influence action:', error);
      
      if (error.response && error.response.data && error.response.data.message) {
        setActionMessage(`Failed: ${error.response.data.message}`);
      } else if (error.message) {
        setActionMessage(`Failed: ${error.message}`);
      } else {
        setActionMessage('Failed to perform action. Network error.');
      }
    }
    
    setIsLoadingAction(prev => ({ ...prev, [actionKey]: false }));
  };

  const getButtonClass = (baseClass: string, cost?: number, actionKey?: string) => {
    let finalClass = baseClass;
    if (cost !== undefined && currentDivineFavor < cost) {
      finalClass += ' opacity-50 cursor-not-allowed';
    } else if (actionKey && isLoadingAction[actionKey]) {
      finalClass += ' opacity-75 cursor-wait';
    }
    return finalClass;
  };

  return {
    isLoadingAction,
    actionMessage,
    handleInfluenceAction,
    getButtonClass
  };
};

const BaseInfluencePanel: React.FC<BaseInfluencePanelProps> = ({
  currentDivineFavor,
  onActionSuccess,
  title = "Divine Influence",
  children
}) => {
  const { actionMessage } = useInfluenceActions(currentDivineFavor, onActionSuccess);

  return (
    <div className="p-4 bg-gray-800 text-white rounded-lg shadow-xl mt-6">
      <h2 className="text-2xl font-bold mb-4 text-center">{title}</h2>
      {actionMessage && (
        <p className={`text-center mb-3 p-2 rounded ${actionMessage.startsWith('Failed') ? 'bg-red-700' : 'bg-green-700'}`}>
          {actionMessage}
        </p>
      )}
      {children}
    </div>
  );
};

export default BaseInfluencePanel;
