# Database Initialization Scripts

This directory contains the refactored database initialization system for Mytherra. The original monolithic `initializeDatabase.php` file has been split into focused, maintainable components.

## File Structure

### Main Script
- **`initializeDatabase.php`** - Main orchestration script that coordinates all initialization steps

### Component Classes
- **`EnvironmentManager.php`** - Handles environment variable loading and validation
- **`DatabaseSchemaManager.php`** - Manages database schema creation and table dependencies
- **`GameDataSeeder.php`** - Handles seeding of all game data (regions, heroes, events, etc.)
- **`GameConfigurationManager.php`** - Manages game configuration initialization

## Usage

To run the complete database initialization:

```bash
php scripts/initializeDatabase.php
```

## Architecture Benefits

### Single Responsibility Principle
Each class has a single, well-defined responsibility:
- `EnvironmentManager` - Environment setup only
- `DatabaseSchemaManager` - Schema creation only  
- `GameDataSeeder` - Data seeding only
- `GameConfigurationManager` - Configuration setup only

### Improved Error Handling
- Better error isolation and reporting
- Stack traces included for debugging
- Environment validation before database operations

### Maintainability
- Easier to test individual components
- Clearer code organization
- Reduced coupling between concerns
- Better code reusability

### Debugging
- Isolated components make debugging easier
- Clear step-by-step execution flow
- Enhanced error messages with context

## Component Details

### EnvironmentManager
- Loads `.env` file
- Validates required environment variables
- Provides clear error messages for missing configuration

### DatabaseSchemaManager
- Handles database creation and clearing
- Manages table creation order based on dependencies
- Separates lookup tables from entity tables
- Loads all required model files

### GameDataSeeder
- Seeds evolution parameters
- Seeds betting system configuration
- Seeds game entities (regions, settlements, heroes, events)
- Initializes core game state and player
- Seeds buildings and divine bets
- Provides detailed progress feedback

### GameConfigurationManager
- Initializes game configurations through ConfigurationManager
- Handles configuration-related errors

## Error Handling

The new system provides better error handling:
- Environment validation before database operations
- Try-catch blocks around each major operation
- Detailed error messages with stack traces
- Graceful failure with appropriate exit codes

## Future Enhancements

The modular structure makes it easy to add:
- Database migration support
- Selective data seeding (e.g., only heroes)
- Configuration validation
- Database backup before initialization
- Progress tracking and resumable operations
