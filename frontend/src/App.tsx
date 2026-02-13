// src/App.tsx
import React from 'react';
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import EventsPage from './pages/EventsPage';
import WorldMapPage from './pages/WorldMapPage';
import HeroesPage from './pages/HeroesPage';
import BettingPage from './pages/BettingPage';
import { Dashboard } from './pages/Dashboard';
import { RegionProvider } from './contexts/RegionProvider';
import { AuthProvider } from './contexts/AuthProvider';
import ProtectedRoute from './components/ProtectedRoute';

const App: React.FC = () => {
  // Get base path from environment or determine from current location
  const getBasename = () => {
    // If we're in development mode, use root
    if (import.meta.env.DEV) {
      return '/';
    }

    // Check if we have a base path from Vite config
    const basePath = import.meta.env.BASE_URL;
    if (basePath && basePath !== '/') {
      return basePath.replace(/\/$/, ''); // Remove trailing slash for basename
    }

    return '/';
  };

  return (
    <AuthProvider>
      <RegionProvider>
        <Router basename={getBasename()}>
          <Routes>
            <Route path="/" element={
              <ProtectedRoute>
                <EventsPage />
              </ProtectedRoute>
            } />
            <Route path="/dashboard" element={
              <ProtectedRoute>
                <Dashboard />
              </ProtectedRoute>
            } />
            <Route path="/world-map" element={
              <ProtectedRoute>
                <WorldMapPage />
              </ProtectedRoute>
            } />
            <Route path="/heroes" element={
              <ProtectedRoute>
                <HeroesPage />
              </ProtectedRoute>
            } />
            <Route path="/betting" element={
              <ProtectedRoute>
                <BettingPage />
              </ProtectedRoute>
            } />
            <Route path="*" element={<Navigate to="/" />} />
          </Routes>
        </Router>
      </RegionProvider>
    </AuthProvider>
  );
};

export default App;
