
import React from "react";
import BaseLayout from "../components/BaseLayout";
import PageHeader from "../components/PageHeader";
import EventsStats from "../components/EventsStats";
import EventSection from "../components/EventSection";
import EmptyState from "../components/EmptyState";
import Pagination from "../components/Pagination";
import { useGameStatus } from "../hooks/useGameStatus";
import { useEvents } from "../hooks/useEvents";

const EventsPage: React.FC = () => {
  const {
    gameStatus,
    isLoading: isLoadingGameStatus,
    error: gameStatusError,
  } = useGameStatus({
    autoRefresh: true,
    refreshInterval: 10000,
  });

  const {
    events,
    isLoading: isLoadingEvents,
    error: eventsError,
    currentPage,
    eventsPerPage,
    loadEventsPage,
    refetch,
    categorizedEvents,
  } = useEvents({
    autoRefresh: true,
    refreshInterval: 30000,
    eventsPerPage: 20,
  });

  const isLoading = isLoadingGameStatus || isLoadingEvents;
  const error = gameStatusError || eventsError;

  const { heroEvents, worldEvents, systemEvents } = categorizedEvents;

  return (
    <BaseLayout
      gameStatus={gameStatus}
      isLoading={isLoading}
      error={error}
      loadingMessage="Loading events..."
      errorPrefix="Error loading events"
    >
      {/* Page Header */}
      <PageHeader
        title="Chronicles of Mytherra"
        subtitle={`Year ${gameStatus?.currentYear || 1} - Witness the unfolding tales of heroes and kingdoms`}
        icon="📜"
      />

      {/* Quick Stats */}
      <EventsStats
        heroEventsCount={heroEvents.length}
        worldEventsCount={worldEvents.length}
        systemEventsCount={systemEvents.length}
      />

      {/* Events Feed */}
      <div className="space-y-6">
        <EventSection
          title="Hero Chronicles"
          icon="⚔️"
          events={heroEvents}
          borderColor="yellow"
          titleColor="yellow"
        />

        <EventSection
          title="World Events"
          icon="🌍"
          events={worldEvents}
          borderColor="blue"
          titleColor="blue"
        />

        <EventSection
          title="Divine & System Events"
          icon="🔮"
          events={systemEvents}
          borderColor="purple"
          titleColor="purple"
        />
      </div>

      {/* No Events Message */}
      {events.length === 0 && (
        <EmptyState
          title="The Chronicles Begin..."
          message="No events have been recorded yet. The world awaits its first heroes and legends."
          icon="📖"
          actionButton={{
            label: "Refresh Events",
            onClick: refetch,
          }}
        />
      )}

      {/* Pagination */}
      <Pagination
        currentPage={currentPage}
        onPageChange={loadEventsPage}
        hasNextPage={events.length >= eventsPerPage}
        hasPreviousPage={currentPage > 1}
        isLoading={isLoadingEvents}
        onRefresh={refetch}
        showRefresh={true}
      />
    </BaseLayout>
  );
};

export default EventsPage;

