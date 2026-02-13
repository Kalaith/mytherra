import React, { ReactNode } from "react";
import type { GameStatus } from "../api/apiService";
import BaseLayout from "./BaseLayout";
import EventLog from "./EventLog";

interface PageLayoutProps {
  gameStatus: GameStatus | null;
  isLoading?: boolean;
  error?: string | null;
  loadingMessage?: string;
  errorPrefix?: string;
  showEventLog?: boolean;
  selectedRegionId?: string;
  selectedHeroId?: string;
  sidebarContent?: ReactNode;
  topContent?: ReactNode;
  children: ReactNode;
}

const PageLayout: React.FC<PageLayoutProps> = ({
  gameStatus,
  isLoading,
  error,
  loadingMessage,
  errorPrefix,
  showEventLog = false,
  selectedRegionId,
  selectedHeroId,
  sidebarContent,
  topContent,
  children,
}) => {
  return (
    <BaseLayout
      gameStatus={gameStatus}
      isLoading={isLoading}
      error={error}
      loadingMessage={loadingMessage}
      errorPrefix={errorPrefix}
    >
      {/* Top content section (optional) */}
      {topContent && <div className="mb-6">{topContent}</div>}

      {/* Main content area */}
      {showEventLog || sidebarContent ? (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Primary Content */}
          <div className="lg:col-span-2 space-y-6">{children}</div>{" "}
          {/* Side Panel */}
          <div className="lg:col-span-1 space-y-6">
            {showEventLog && (
              <EventLog
                selectedRegionId={selectedRegionId}
                selectedHeroId={selectedHeroId}
              />
            )}
            {sidebarContent}
          </div>
        </div>
      ) : (
        // Full width layout when no sidebar
        <div className="space-y-6">{children}</div>
      )}
    </BaseLayout>
  );
};

export default PageLayout;
