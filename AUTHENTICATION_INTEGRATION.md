# Mytherra Authentication Integration Plan

> **Objective**: Replace Mytherra's standalone authentication system with the centralized WebHatchery Authentication Portal

## Overview

This document outlines the integration plan to connect Mytherra with the WebHatchery Authentication Portal, establishing a single sign-on (SSO) experience across all WebHatchery applications. The auth portal will become the authoritative source for user authentication, while Mytherra will maintain its own user profiles linked to auth portal accounts.

## Current State Analysis

### Mytherra Authentication (Current)
- **Location**: `h:\WebHatchery\game_apps\mytherra\backend\`
- **System**: Standalone PHP authentication with JWT tokens
- **Database**: Local `users` table with Mytherra-specific data
- **Features**: Registration, login, session management within Mytherra context

### Auth Portal (Target)
- **Location**: `h:\WebHatchery\apps\auth\`
- **System**: Centralized authentication with role-based access control
- **Database**: Dedicated `auth_portal` database with comprehensive user management
- **Features**: Advanced role management, admin panel, secure JWT handling

## Integration Architecture

### Authentication Flow (Post-Integration)
```
1. User visits Mytherra → Check for valid auth token
2. No valid token → Redirect to Auth Portal (/login?redirect=mytherra)
3. User authenticates at Auth Portal → JWT token generated
4. Auth Portal redirects back → Mytherra receives token
5. Mytherra validates token → Creates/updates local user profile
6. User accesses Mytherra → Full game functionality with linked profile
```

### Data Relationship
```
Auth Portal User (Primary)          Mytherra User Profile (Secondary)
├── id (UUID/BIGINT)               ├── auth_user_id (Foreign Key)
├── email                          ├── display_name
├── username                       ├── divine_influence
├── password_hash                  ├── divine_favor
├── roles                          ├── betting_stats
└── profile_data                   └── game_preferences
```

## Implementation Steps

### Phase 1: Backend Integration

#### 1.1 Update Mytherra Database Schema
```sql
-- Add auth portal integration to existing users table
ALTER TABLE users ADD COLUMN auth_user_id BIGINT UNSIGNED NULL AFTER id;
ALTER TABLE users ADD COLUMN auth_email VARCHAR(255) NULL AFTER auth_user_id;
ALTER TABLE users ADD COLUMN auth_username VARCHAR(255) NULL AFTER auth_email;
ALTER TABLE users ADD INDEX idx_auth_user_id (auth_user_id);

-- Migration strategy: existing users will need to link accounts
-- New users will be automatically linked via auth portal
```

#### 1.2 Create Auth Portal Integration Service
**File**: `backend/src/Services/AuthPortalService.php`
```php
<?php
namespace Mytherra\Services;

class AuthPortalService {
    private string $authPortalBaseUrl;
    private string $jwtSecret;
    
    public function validateToken(string $token): ?array;
    public function getUserFromToken(string $token): ?array;
    public function createOrUpdateLocalUser(array $authUser): User;
    public function linkExistingUser(int $mytherraUserId, int $authUserId): bool;
}
```

#### 1.3 Update Authentication Middleware
**File**: `backend/src/Middleware/AuthenticationMiddleware.php`
```php
// Replace existing JWT validation with auth portal token validation
// No legacy fallback required; all authentication will use the auth portal
// Implement automatic user linking for new users
```

#### 1.4 Create Migration Controllers
**File**: `backend/src/Controllers/AuthMigrationController.php`
```php
// Endpoints for user account linking
// Migration status checking
// Batch user migration tools
```

### Phase 2: Frontend Integration

#### 2.1 Update Authentication Context
**File**: `frontend/src/contexts/AuthContext.tsx`
```typescript
// Remove local login/register functionality
// Add auth portal redirect logic
// Implement token reception and validation
// Handle user profile synchronization
```

#### 2.2 Create Auth Portal Integration Components
```typescript
// AuthPortalRedirect.tsx - Handles redirects to auth portal
// TokenReceiver.tsx - Processes tokens from auth portal
// AccountLinker.tsx - UI for linking existing accounts
// MigrationStatus.tsx - Shows migration progress
```

#### 2.3 Update Routing System
```typescript
// Replace login/register routes with auth portal redirects
// Add callback route for auth portal returns
// Implement protected route guards with new auth system
```

### Phase 3: Integration Implementation

#### 3.1 Environment Configuration
**Mytherra Backend `.env`**
```env
# Auth Portal Integration
AUTH_PORTAL_BASE_URL=http://localhost:8000
AUTH_PORTAL_API_URL=http://localhost:8000/api
AUTH_PORTAL_JWT_SECRET=shared_secret_key_between_systems
AUTH_PORTAL_REDIRECT_URL=http://localhost:5174/auth/callback

# Migration Settings
ENABLE_AUTH_MIGRATION=true
ALLOW_LOCAL_AUTH_FALLBACK=true
```

**Mytherra Frontend `.env`**
```env
# Auth Portal URLs
VITE_AUTH_PORTAL_URL=http://localhost:5173
VITE_AUTH_PORTAL_LOGIN_URL=http://localhost:5173/login
VITE_AUTH_PORTAL_REGISTER_URL=http://localhost:5173/register
```

#### 3.2 Auth Portal Configuration
**Auth Portal Backend `.env`**
```env
# Allowed redirect domains for Mytherra
ALLOWED_REDIRECT_DOMAINS=localhost:5174,mytherra.webhatchery.com

# Mytherra-specific settings
MYTHERRA_CALLBACK_URL=http://localhost:5174/auth/callback
```

### Phase 4: Migration Strategy
### Phase 4: Direct Cutover

Since the system is not yet live, all authentication will immediately use the auth portal. There is no need for phased migration, legacy fallback, or gradual rollout. All user accounts will be created and managed via the auth portal from the start.

### Phase 5: Enhanced Features

#### 5.1 Role-Based Game Access
```php
// Implement role-based features in Mytherra
// - Admin users get god-mode abilities
// - Moderators can manage problematic players
// - Premium users get enhanced betting limits
// - Beta testers access experimental features
```

#### 5.2 Cross-Application Benefits
```typescript
// Shared user profiles across WebHatchery apps
// Unified notification system
// Cross-app achievement tracking
// Centralized user preferences
```

## API Integration Specifications

### Auth Portal Endpoints (Used by Mytherra)
```
GET  /api/auth/validate-token    # Validate JWT token
GET  /api/auth/user/{id}         # Get user details by ID
POST /api/auth/link-app-user     # Link app-specific user data
GET  /api/admin/users            # Admin: Get all users (for admin panel)
```

### Mytherra Endpoints (New/Modified)
```
GET  /api/auth/callback          # Receive token from auth portal
POST /api/auth/link-account      # Link existing Mytherra account
GET  /api/auth/migration-status  # Check migration status
POST /api/auth/migrate-user      # Migrate user to auth portal
```

## Security Considerations

### Token Security
- **Shared JWT Secret**: Both systems use same secret for token validation
- **Token Expiration**: Consistent expiration policies across systems
- **Refresh Mechanism**: Implement token refresh without full re-authentication

### Data Protection
- **No Password Storage**: Mytherra never stores passwords (auth portal only)
- **Minimal Data Sync**: Only sync necessary user data between systems
- **Audit Logging**: Track all authentication and migration events

### Cross-Origin Security
- **CORS Configuration**: Proper cross-origin request handling
- **Redirect Validation**: Whitelist allowed redirect URLs
- **CSRF Protection**: Implement CSRF tokens for sensitive operations

## Database Migration Scripts

### 1. Schema Update Script
```sql
-- File: backend/scripts/001_add_auth_portal_integration.sql
ALTER TABLE users 
ADD COLUMN auth_user_id BIGINT UNSIGNED NULL AFTER id,
ADD COLUMN auth_email VARCHAR(255) NULL AFTER auth_user_id,
ADD COLUMN auth_username VARCHAR(255) NULL AFTER auth_email,
ADD COLUMN migration_status ENUM('pending', 'linked', 'migrated') DEFAULT 'pending',
ADD COLUMN migrated_at TIMESTAMP NULL,
ADD INDEX idx_auth_user_id (auth_user_id),
ADD INDEX idx_migration_status (migration_status);
```

### 2. Data Migration Script
```php
// File: backend/scripts/migrate-to-auth-portal.php
// 1. Validate all existing users
// 2. Create auth portal accounts for unmigrated users
// 3. Update Mytherra users table with auth_user_id
// 4. Verify data integrity
// 5. Generate migration report
```

## Testing Strategy

### Unit Tests
- **AuthPortalService**: Token validation, user creation, account linking
- **AuthenticationMiddleware**: Token processing, user authentication
- **Migration Scripts**: Data integrity, rollback procedures

### Integration Tests
- **Authentication Flow**: Complete login/logout cycle
- **Token Validation**: Cross-system token verification
- **Account Linking**: Existing user migration process

### User Acceptance Testing
- **Login Experience**: Seamless redirect and return flow
- **Game Continuity**: Preserved progress and preferences
- **Admin Functions**: Enhanced admin capabilities

## Rollback Plan

### Rollback Plan
If critical issues arise during initial deployment:

1. **Revert Frontend**: Restore local authentication components if needed
2. **Restore Middleware**: Use local JWT validation as a temporary fallback
3. **Data Preservation**: All game data remains intact
4. **User Communication**: Notify users of temporary service restoration

Since nothing is live yet, rollback is simply a matter of restoring the previous codebase before launch.

## Success Metrics

### Technical Metrics
- **Authentication Success Rate**: >99% successful logins
- **Response Time**: <2s for auth portal redirects
- **Token Validation**: <100ms token processing time
- **Migration Success**: >95% successful account migrations

### User Experience Metrics
- **User Satisfaction**: Post-migration user feedback
- **Support Tickets**: Reduced authentication-related issues
- **Session Duration**: Maintained or improved user engagement

## Timeline

### Week 1-2: Planning & Setup
- [ ] Environment configuration
- [ ] Database schema updates
- [ ] Initial service development

### Week 3-4: Backend Integration
- [ ] AuthPortalService implementation
- [ ] Middleware updates
- [ ] API endpoint creation

### Week 5-6: Frontend Integration
- [ ] Authentication flow updates
- [ ] Component development
- [ ] UI/UX implementation

### Week 7-8: Testing & Migration
- [ ] Comprehensive testing
- [ ] User migration tools
- [ ] Documentation completion

### Week 9-10: Deployment & Monitoring
- [ ] Gradual rollout
- [ ] User migration support
- [ ] Performance monitoring

## Post-Integration Benefits

### For Users
- **Single Sign-On**: One account for all WebHatchery applications
- **Enhanced Security**: Centralized, secure authentication system
- **Profile Continuity**: Consistent user experience across apps
- **Advanced Features**: Role-based access and premium features

### For Developers
- **Reduced Complexity**: No need to maintain separate auth systems
- **Consistent APIs**: Standardized authentication across applications
- **Centralized User Management**: Single admin interface for all users
- **Scalable Architecture**: Easy addition of new applications

### for WebHatchery Ecosystem
- **Unified User Base**: Complete view of user engagement across apps
- **Cross-App Analytics**: Comprehensive user behavior insights
- **Simplified Deployment**: Centralized authentication service
- **Enhanced Security**: Professional-grade security practices

## Conclusion

The integration of Mytherra with the WebHatchery Authentication Portal will establish a robust, scalable foundation for user authentication across the entire WebHatchery ecosystem. This migration will enhance security, improve user experience, and provide a solid foundation for future application development.

The phased approach ensures minimal disruption to existing users while providing clear benefits and enhanced functionality. Upon completion, users will enjoy seamless access to all WebHatchery applications with a single, secure account.

---

**Next Steps**: Review this plan with the development team and begin Phase 1 implementation with database schema updates and service development.
