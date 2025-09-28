# Order Wendy's Diner

A modern restaurant ordering system built with Laravel and Vue.js, featuring online menu browsing, cart management, and integrated payment processing via Revolut.

## Overview

This application allows customers to:
- Browse the restaurant menu organized by categories
- Add items to their cart with quantity selection
- Proceed through a secure checkout process
- Make payments using Revolut's payment gateway

Admin users can:
- Manage products (add, edit, delete, bulk operations)
- Control product availability
- Access a protected dashboard

## Technology Stack

### Backend
- **PHP**: 8.2+
- **Laravel**: 12.x
- **Database**: MySQL (SQLite supported for development)
- **Testing**: Pest PHP
- **Queue**: Database driver
- **Cache**: Database driver

### Frontend
- **Vue.js**: 3.x with TypeScript
- **Inertia.js**: 2.x (SPA without API)
- **Build Tool**: Vite 6.x
- **Styling**: TailwindCSS 4.x
- **UI Components**: reka-ui, Lucide icons
- **State Management**: VueUse core utilities

### Payment Integration
- **Revolut Checkout**: For payment processing

### Development Tools
- **Code Quality**: ESLint, Prettier, Laravel Pint
- **Type Checking**: TypeScript, vue-tsc
- **Process Management**: Concurrently

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+ (or SQLite for development)
- Web server (Apache/Nginx) or use Laravel's built-in server

## Setup & Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd order-wendys-diner
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   - For SQLite (development):
     ```bash
     touch database/database.sqlite
     ```
   - For MySQL: Create database and update `.env` with credentials

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build frontend assets**
   ```bash
   npm run build
   ```

## Running the Application

### Development Mode
```bash
# Start all services (Laravel server, queue worker, logs, and Vite)
composer run dev
```

This command starts:
- Laravel development server on http://localhost:8000
- Queue worker for background jobs
- Laravel Pail for real-time logs
- Vite development server for hot reloading

### Alternative Development Commands
```bash
# Backend only
php artisan serve

# Frontend development server only
npm run dev

# Queue worker
php artisan queue:work

# Watch logs
php artisan pail
```

### Production Build
```bash
npm run build
php artisan optimize
```

### SSR Mode (Server-Side Rendering)
```bash
composer run dev:ssr
```

## Available Scripts

### Composer Scripts
- `composer run dev` - Start full development environment
- `composer run dev:ssr` - Start development with SSR
- `composer run test` - Run PHP tests

### NPM Scripts
- `npm run dev` - Start Vite development server
- `npm run build` - Build for production
- `npm run build:ssr` - Build with SSR support
- `npm run format` - Format code with Prettier
- `npm run format:check` - Check code formatting
- `npm run lint` - Lint and fix code with ESLint

### Artisan Commands
```bash
php artisan serve          # Start development server
php artisan migrate        # Run database migrations
php artisan queue:work     # Start queue worker
php artisan tinker         # Laravel REPL
```

## Environment Variables

### Required Variables
Copy from `.env.example` and configure:

```env
# Application
APP_NAME="Order Wendy's Diner"
APP_ENV=local|production
APP_KEY=                    # Generated with php artisan key:generate
APP_DEBUG=true|false
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql|sqlite
DB_HOST=127.0.0.1          # For MySQL
DB_PORT=3306               # For MySQL
DB_DATABASE=order_wendys_diner
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Revolut Payment (Required for checkout)
REVOLUT_MODE=sandbox|live
REVOLUT_SECRET_KEY=sk_...   # Revolut secret key
REVOLUT_PUBLIC_KEY=pk_...   # Revolut public key

# Mail (Optional - defaults to log driver)
MAIL_MAILER=smtp|log
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=hello@example.com

# Queue & Cache (Optional - defaults to database)
QUEUE_CONNECTION=database|redis|sync
CACHE_STORE=database|redis|file
```

### Payment Setup
To enable payments, you need:
1. Register with Revolut Merchant API
2. Get sandbox/live API keys
3. Configure `REVOLUT_SECRET_KEY` and `REVOLUT_PUBLIC_KEY` in `.env`

## Testing

```bash
# Run all tests
composer run test

# Run with coverage (if configured)
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/OrderTest.php
```

Tests are located in:
- `tests/Feature/` - Integration tests
- `tests/Unit/` - Unit tests

Framework: **Pest PHP** with Laravel plugin

## Project Structure

```
├── app/
│   ├── Http/Controllers/        # Application controllers
│   │   ├── OrderController.php  # Cart & checkout logic
│   │   └── Admin/              # Admin-only controllers
│   ├── Models/                 # Eloquent models
│   └── ...
├── config/                     # Laravel configuration
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/                    # Web-accessible files
├── resources/
│   ├── js/                    # Vue.js frontend
│   │   ├── components/        # Reusable Vue components
│   │   └── pages/            # Page components (Inertia)
│   └── css/                  # Stylesheets
├── routes/
│   ├── web.php               # Main application routes
│   ├── auth.php             # Authentication routes
│   └── settings.php         # Additional routes
├── tests/                   # Test files
├── composer.json            # PHP dependencies
├── package.json            # Node.js dependencies
├── vite.config.ts          # Vite configuration
└── tailwind.config.js      # TailwindCSS configuration
```

### Key Routes
- `/` - Homepage
- `/carte` - Menu/catalog page
- `/checkout/{id}` - Payment page
- `/dashboard` - Admin dashboard (requires authentication)

## TODO

- [ ] Add user authentication system documentation
- [ ] Document admin user creation process  
- [ ] Add API documentation for any endpoints
- [ ] Document deployment process
- [ ] Add database seeding instructions
- [ ] Document backup procedures
- [ ] Add monitoring and logging setup

## License

This project is licensed under the MIT License. See the `composer.json` file for details.

## Contributing

<!-- TODO: Add contributing guidelines -->

## Support

<!-- TODO: Add support information -->
