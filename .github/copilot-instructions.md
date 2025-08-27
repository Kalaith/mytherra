Do not run commands in the terminal, show the user and stop futher processing.
No containers, no docker-compose, no dockerfiles, no kubernetes.
## General Guidelines
- Do not run commands, end processing and ask user to run them
- Keep code simple, readable, and well-commented.
- Follow naming/style conventions and reuse code.
- Use linters/formatters; run and fix tests after changes.
- Store shared types in entities/, helpers in utils/.
- Use consistent file/folder names.
- Check folder existence before creation.
- Use environment variables for config.
- Test changes incrementally.
- Validate/sanitize inputs; use standard error formats.

## PHP Backend (Slim Framework)
- Actions and Controllers should not have any Types or Interfaces
- All SQL should be seperate from actions and controllers
- Use Actions pattern: separate business logic from controllers
- Controllers handle HTTP requests/responses, Actions handle business logic
- Follow PSR-4 autoloading and PSR-12 coding standards
- Use proper namespacing: App\Controllers, App\Actions, App\Models
- Implement proper error handling with try-catch blocks
- Use dependency injection container for database connections
- Return JSON responses with consistent error format
- Require auth on all endpoints via middleware
- Use prepared statements for database queries
- Implement pagination and filtering in Action classes
- Follow RESTful conventions for API endpoints

## React Frontend (TypeScript)
- Use strict typing. Avoid any; prefer unknown or define interfaces/types explicitly
- Use DTOs, input validation schemas, or utility types when appropriate
- Use compound components for complex UI
- Follow accessibility best practices
- Store types in src/types/, utilities in src/utils/
- Use React hooks and functional components
- Implement proper error boundaries and loading states
- Use consistent naming: PascalCase for components, camelCase for variables/functions
- Separate API calls into service files
- Use environment variables for API endpoints
- Implement proper form validation with libraries like Zod or Yup
