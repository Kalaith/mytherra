# 🌌The world is destined to end — whether b- **Long-term Bets**: Influence cross-Era outcomes (e.g., "This hero's line will return to rule again")

---

## 🚀 Enhanced Features (PHP Backend)

The PHP backend migration brought significant enhancements and new features:

### 🏗️ **Expanded World Systems**
- **Buildings & Infrastructure**: Comprehensive building system with types, conditions, and special properties
- **Landmarks**: Discoverable landmarks that shape regional identity and provide bonuses
- **Resource Nodes**: Strategic resources that influence settlement growth and conflicts
- **Settlement Evolution**: Dynamic settlement growth with configurable evolution parameters

### 🎲 **Advanced Divine Betting**
- **Speculation Events**: Complex betting scenarios with multiple outcome types
- **Dynamic Odds**: Real-time odds calculation based on world state and divine influence
- **Bet Modifiers**: Contextual bonuses and penalties based on regional conditions
- **Temporal Betting**: Long-term bets that span multiple in-game years

### ⚙️ **Robust Game Loop**
- **Background Workers**: Dedicated processes for world simulation and bet resolution
- **Queue System**: Asynchronous job processing for complex game mechanics  
- **Configurable Timing**: Flexible tick rates and event scheduling
- **State Persistence**: Comprehensive game state tracking and recovery

### 🎯 **Enhanced Hero System**
- **Detailed Lifecycle**: Birth, growth, aging, death, and potential reincarnation
- **Alignment Dynamics**: Complex moral and ethical alignment tracking
- **Role Specialization**: Heroes develop specialized roles based on regional needs
- **Settlement Interactions**: Rich interaction system between heroes and settlements

### 📊 **Comprehensive Data Models**
- **40+ Database Models**: Detailed tracking of all game entities and relationships
- **Configuration Tables**: Flexible game mechanics through database-driven configuration
- **Status Tracking**: Granular state management for all entities
- **Historical Data**: Comprehensive logging for future Era continuation systems

---emonic invasion, magical apocalypse, or a hero's hubris. But every ending gives rise to a new **Era**, where echoes of the past shape the future.

> **🔄 Backend Migration Complete**: Mytherra has been successfully migrated from Node.js/TypeScript to PHP, featuring enhanced architecture with the Actions pattern, comprehensive models, and expanded game mechanics.

---therra

**Mytherra** is a **web-based god simulation game** where players share a single evolving world. As minor deities, players place divine bets on the fate of heroes and civilizations, subtly influencing outcomes to earn favor and glory.

The world is destined to end — whether by demonic invasion, magical apocalypse, or a hero’s hubris. But every ending gives rise to a new **Era**, where echoes of the past shape the future.

---

## 🌟 Concept Summary

You are one of many gods watching the world from beyond. You cannot command mortals, but you may bet on their rise, influence their fate, and shape the grand story — without ever writing it yourself.

- The world is shared by **all players**.
- **Bets** cost divine influence and reward insight and foresight.
- You **cannot directly control outcomes**, only tilt the odds in your favor.
- The **world ends** — always — leading to a **new Era** with fresh rules, heroes, and magic.

---

## 🔄 Era System

### 🌋 Inevitable Cataclysms
Each world culminates in a climactic event:
- Demonic conquest  
- A hero goes rogue and destroys everything  
- The magical fabric collapses  
- Divine war severs realms

These events conclude an **Era**.

### 🌱 Rebirth & Renewal
After cataclysm, the world enters a **new Era**:
- Rules evolve
- New factions, regions, and magic appear
- Player influence carries over through legacy

### 🌀 Legacy & Continuity
- **Reincarnating Heroes**: Some return with echoes of their former selves
- **Textual References**: Books, ruins, or prophecies recall past Eras
- **Era Logs**: Major events preserved for divine study
- **Long-term Bets**: Influence cross-Era outcomes (e.g., "This hero’s line will return to rule again")

---

## 🧱 Core Features

### 🗺️ Dynamic Regions
- Procedurally generated with unique names and traits
- Track **Prosperity**, **Chaos**, and **Magic Affinity**
- Regions evolve, decay, or transform across Eras

### 🧙 Emergent Heroes
- Spawn based on regional needs or crises
- Take on roles: warrior, scholar, prophet, tyrant
- May die, succeed, or ascend — and **some may reincarnate**

### 🔮 Divine Betting System
- Spend **Divine Influence** to place bets:
  - “This hero will reach level 100”
  - “That region will be corrupted within 10 years”
  - “This bloodline will return in the next Era”
- Influence the world through omens, guidance, or blessings
- Winning bets grant Divine Favor and unlock prestige

### ✨ Hidden Magical Systems
- Magic is modular, mysterious, and discovered organically
- Players can nudge heroes toward discovery
- Magic systems evolve with each Era

### 📜 Systemic Storytelling
- No pre-written plot — the world evolves based on internal logic and divine nudges
- Eras become chapters in a greater saga
- Past events leave permanent marks: cursed forests, ruined capitals, legendary tombs

---

## 💡 Design Goals

- **Shared World**: One living, persistent simulation for all players
- **Emergent Narrative**: Stories arise from systems, not scripts
- **Cyclical Structure**: Worlds always end — and begin again
- **Minimal Direct Control**: Players are gods, not puppeteers
- **Long-Term Engagement**: Legacy systems reward consistency and clever long bets
- **Expandable via Eras**: Each new Era introduces fresh mechanics, balance changes, or features

---

## 🧭 Design Philosophy: "Narrative Through Systems"

- 🧠 *Predict, not control.* You shape probability, not destiny.
- 🔁 *Endings matter.* Death is meaningful. Eras give closure and renewal.
- 🪄 *Magic is a mystery.* Heroes discover what even gods do not know — until it’s too late.
- 🧬 *Continuity breeds richness.* Echoes of past Eras give meaning to the present.
- 🧠 *Player stories emerge naturally.* No two timelines are ever the same.

---

## 📅 Roadmap Snapshot

- [x] Core region simulation
- [x] Procedural hero system  
- [x] Divine influence actions
- [x] **Backend migration to PHP** ✨
- [x] **Enhanced database schema with 40+ models**
- [x] **Actions pattern architecture implementation**  
- [x] **Building and landmark systems**
- [x] **Settlement evolution mechanics**
- [x] **Game loop service with background workers**
- [x] Betting and reward system (partial)
- [ ] Era-ending mechanics and reincarnation
- [ ] Multi-Era legacy tracking  
- [ ] New Era content expansion pipeline
- [ ] Frontend integration with new PHP APIs


## Technology Stack

### Backend
- **Runtime**: PHP 8.1+
- **Framework**: Slim Framework 4
- **Language**: PHP
- **Database**: MySQL with Eloquent ORM
- **Testing**: PHPUnit
- **Architecture**: Actions pattern with Repository pattern
- **Dependency Injection**: PHP-DI container
- **API**: RESTful JSON API with PSR-7 HTTP messages

### Frontend
- **Framework**: React
- **Language**: TypeScript
- **Styling**: Tailwind CSS
- **Build Tool**: Vite
- **State Management**: React Context API
- **Routing**: React Router
- **Testing**: Jest, React Testing Library
- **UI Components**: Custom components with Tailwind

## File Structure

```
mytherra/
├── frontend/
│   ├── public/
│   ├── src/
│   │   ├── App.tsx         # Main React app
│   │   ├── App.css         # Styling for map, heroes, regions
│   │   ├── index.tsx       # Entry point
│   │   ├── api/
│   │   │   ├── apiService.ts  # API services
│   │   │   └── mockDataService.ts # Mock data service
│   │   ├── components/
│   │   │   ├── EventLog.tsx  # World event log
│   │   │   ├── HeroCard.tsx  # Hero information display
│   │   │   ├── HeroList.tsx  # Lists all heroes
│   │   │   ├── InfluencePanel.tsx # Player action panel
│   │   │   └── WorldMap.tsx  # Interactive world map
│   │   ├── contexts/
│   │   │   └── RegionContext.tsx # Region data context
│   │   └── entities/
│   │       ├── event.ts     # Event data structures
│   │       ├── hero.ts      # Hero data structures
│   │       └── region.ts    # Region data structures
│   └── package.json        # React dependencies
├── backend/                 # PHP Backend
│   ├── public/
│   │   └── index.php        # Entry point
│   ├── src/
│   │   ├── Actions/         # Business logic layer
│   │   │   ├── BettingActions.php
│   │   │   ├── EventActions.php
│   │   │   ├── HeroActions.php
│   │   │   ├── InfluenceActions.php
│   │   │   ├── RegionActions.php
│   │   │   └── SettlementActions.php
│   │   ├── Controllers/     # HTTP request handlers
│   │   │   ├── BettingController.php
│   │   │   ├── BuildingController.php
│   │   │   ├── EventController.php
│   │   │   ├── HeroController.php
│   │   │   ├── InfluenceController.php
│   │   │   ├── LandmarkController.php
│   │   │   ├── RegionController.php
│   │   │   ├── SettlementController.php
│   │   │   └── StatusController.php
│   │   ├── Models/          # Database models (Eloquent)
│   │   │   ├── Building.php
│   │   │   ├── DivineBet.php
│   │   │   ├── GameEvent.php
│   │   │   ├── GameState.php
│   │   │   ├── Hero.php
│   │   │   ├── Landmark.php
│   │   │   ├── Region.php
│   │   │   └── Settlement.php
│   │   ├── Services/        # Core game services
│   │   │   ├── DivineBettingService.php
│   │   │   ├── DivineInfluenceService.php
│   │   │   ├── GameConfigService.php
│   │   │   ├── GameLoopService.php
│   │   │   └── HeroLifecycleService.php
│   │   ├── External/        # Repository pattern
│   │   ├── Routes/          # API routing
│   │   │   └── api.php      # Main API routes
│   │   ├── Utils/           # Utility classes
│   │   ├── Commands/        # Background workers
│   │   │   └── GameLoopWorker.php
│   │   └── scripts/         # Database and setup scripts
│   ├── composer.json        # PHP dependencies
│   └── phpunit.xml         # Testing configuration
├── bruno/                   # API testing collection
│   ├── admin/              # Administrative endpoints
│   ├── betting/            # Divine betting system tests  
│   ├── buildings/          # Building management tests
│   ├── events/             # Event system tests
│   ├── heroes/             # Hero management tests
│   ├── influence/          # Divine influence tests
│   ├── landmarks/          # Landmark discovery tests
│   ├── regions/            # Region management tests
│   └── settlements/        # Settlement evolution tests
└── README.md
```
│   │   │   ├── heroRoutes.ts
│   │   │   ├── index.ts
│   │   │   ├── influenceRoutes.ts
│   │   │   └── regionRoutes.ts
│   │   ├── scripts/         # Utility scripts
│   │   │   └── seedDb.ts    # Database initialization
│   │   └── utils/           # Utilities
│   │       ├── descriptionGenerator.ts
│   │       └── nameGenerator.ts
│   └── package.json        # Server dependencies
├── NEXTSTEPS.md            # Development roadmap
└── README.md
```

The backend follows a modern PHP architecture with clear separation of concerns:

### Core Components

1. **Routes (`Routes/`)**: Defines all API endpoints using Slim Framework.
   - `api.php`: All RESTful API endpoints for regions, heroes, events, betting, influence, and game status

2. **Controllers (`Controllers/`)**: Handle HTTP request processing and response generation.
   - `EventController.php`: Event lookup and creation
   - `StatusController.php`: Game state management  
   - `HeroController.php`: Hero retrieval and modification
   - `InfluenceController.php`: Player influence processing
   - `RegionController.php`: Region data handling
   - `BettingController.php`: Divine betting system
   - `BuildingController.php`: Building management
   - `LandmarkController.php`: Landmark discovery and management

3. **Actions (`Actions/`)**: Contains the business logic and state mutation functions.
   - `EventActions.php`: Event creation and processing
   - `HeroActions.php`: Hero mechanics and behavior  
   - `InfluenceActions.php`: Player divine influence logic
   - `RegionActions.php`: Region state and evolution
   - `BettingActions.php`: Divine betting mechanics
   - `SettlementActions.php`: Settlement evolution and management

4. **Models (`Models/`)**: Define the data structures using Eloquent ORM.
   - `GameEvent.php`: World event schema
   - `GameState.php`: Overall game state
   - `Hero.php`: Hero entity schema
   - `Region.php`: World region schema
   - `DivineBet.php`: Betting system schema
   - `Building.php`, `Settlement.php`, `Landmark.php`: World structure entities

5. **Services (`Services/`)**: Core game services and complex business logic.
   - `GameLoopService.php`: World simulation and time advancement
   - `DivineBettingService.php`: Betting system mechanics
   - `DivineInfluenceService.php`: Player influence calculations
   - `HeroLifecycleService.php`: Hero creation, aging, and death
   - `GameConfigService.php`: Game configuration management

6. **External (`External/`)**: Repository pattern for data access abstraction.

7. **Utils (`Utils/`)**: Helper functions and utilities for various game systems.

## How to Run

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/mytherra.git
    cd mytherra
    ```

2.  **Install Dependencies**
    
    **Frontend:**
    ```bash
    cd frontend && npm install
    ```
    
    **Backend:**
    ```bash
    cd backend && composer install
    ```

3.  **Environment Setup**
    ```bash
    cd backend
    cp .env.example .env
    # Edit .env with your database credentials
    ```

4.  **Initialize Database**
    ```bash
    cd backend
    php scripts/initializeDatabase.php
    ```

5.  **Run Backend**
    ```bash
    cd backend
    php -S localhost:8000 -t public
    ```

6.  **Run Frontend**
    ```bash
    cd frontend
    npm run dev
    ```
    
The backend will run on port 8000 by default, and the frontend will run on port 5173 with Vite's development server.

## Future Plans & Roadmap

*   **Magic Discovery System**
    * Node unlocking system for magical knowledge
    * Visible and hidden magic paths
    * Special simulation behaviors (flying cities, living weapons)
    * Link magic types to hero/region traits

*   **Enhanced Hero System**
    * Expand personality traits (curious, vengeful, ambitious)
    * Implement preferred quests system
    * Create reactive alignment system
    * Add hero item and artifact generation

*   **Improved World Visualization**
    * Replace square regions with hex/tile map
    * Add points of interest icons (temples, ruins, monuments)
    * Enhanced region animation and visual effects

*   **Content Expansion**
    * Expand culture-based name generation
    * Enhance region naming system with geographic influences
    * Implement dynamic lore hook generation
    * Add region-specific unique events

*   **Advanced Systems**
    * Simulation snapshot saving and sharing
    * Interactive timeline viewer for major world events
    * Advanced event filtering and categorization
    * Real-time simulation speed control

## Contribution Guidelines

Contributions are welcome! Please follow these steps:

1.  Fork the repository.
2.  Create a new branch for your feature or bug fix.
3.  Commit your changes with clear commit messages.
4.  Push your branch to your fork.
5.  Submit a pull request with a clear description of your changes.

## Development Guidelines

### Coding Standards

- PHP 8.1+ with strict typing enabled
- PSR-12 coding standard compliance
- Comprehensive type declarations for all functions and properties
- Modular code organization with clear separation of concerns
- Actions pattern for business logic separation from controllers
- Repository pattern for data access abstraction

### Security Practices

- All endpoints protected with middleware
- Input validation and sanitization using dedicated validation layers  
- Prepared statements for all database queries
- Secure handling of user credentials
- CORS configuration for cross-origin requests
- Comprehensive error handling with sanitized responses

### Testing Strategy

- Comprehensive test coverage using PHPUnit
- Unit tests for individual Actions and Services
- Integration tests for API endpoints
- Database testing with test fixtures
- End-to-end testing for critical user flows

## 📘 Example Lore Snippet

A scholar named Elvarwyn uncovered remnants of a forgotten star-language in the ruins of Cryspire. Her discovery led to the rise of celestial magic, forever changing the world's understanding of the skies. Centuries later, star cults worship her as the First Seeker.

## License

This project is licensed under the MIT License - see the individual component README files for details.

Part of the WebHatchery game collection.