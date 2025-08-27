import React from 'react';
import StatsCard from './StatsCard';

interface EventsStatsProps {
  heroEventsCount: number;
  worldEventsCount: number;
  systemEventsCount: number;
}

const EventsStats: React.FC<EventsStatsProps> = ({
  heroEventsCount,
  worldEventsCount,
  systemEventsCount
}) => {
  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <StatsCard
        title="Hero Actions"
        value={heroEventsCount}
        description="Recent hero deeds and adventures"
        color="yellow"
        icon="⚔️"
      />
      <StatsCard
        title="World Events"
        value={worldEventsCount}
        description="Regional happenings and changes"
        color="blue"
        icon="🌍"
      />
      <StatsCard
        title="System Events"
        value={systemEventsCount}
        description="Divine interventions and system messages"
        color="purple"
        icon="🔮"
      />
    </div>
  );
};

export default EventsStats;
