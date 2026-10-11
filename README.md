# Flora

<p align="center">
  <img src="public/images/flora_logo2.jpg" alt="Flora logo" width="180">
</p>

<h3 align="center">Flowers for every moment.</h3>

<p align="center">
  An online flower shop experience for discovering arrangements, managing a cart, and placing orders.
</p>

<p align="center">
  <a href="https://github.com/akmalscript/pbp-flora"><img src="https://img.shields.io/badge/Project-Flora-9b5de5?style=for-the-badge" alt="Flora project"></a>
  <a href="https://laravel.com/docs/12.x"><img src="https://img.shields.io/badge/Laravel-12-ff2d20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.2%2B-777bb4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2 or newer"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-2a9d8f?style=for-the-badge" alt="MIT license"></a>
</p>

<p align="center">
  <a href="#about">About</a> ·
  <a href="#features">Features</a> ·
  <a href="#screenshots">Screenshots</a> ·
  <a href="#getting-started">Getting started</a>
</p>

## About

Flora is a full-stack flower shop web application built with Laravel. It brings together a customer storefront and an admin workspace, with product discovery, cart and checkout flows, order management, and a floral visual identity.

This project was created to explore the end-to-end experience of running a small online shop—from presenting a product catalog to processing a customer order.

## Screenshots

<p align="center">
  <img src="public/images/home.jpg" alt="Flora storefront homepage" width="820">
</p>

## Features

### Customer experience

- Browse the storefront and view product details
- Register, sign in, and manage an account profile
- Add products to a cart and adjust item quantities
- Apply coupons and choose a shipping method at checkout
- Submit payments through the Midtrans Snap integration
- Review order history and order details

### Store administration

- Manage the product catalog and categories
- Review orders and update their status
- Access role-protected admin pages

### Interface

- Responsive Blade views styled with Tailwind CSS
- Alpine.js and JavaScript-powered interactions
- Locally hosted brand and flower imagery

## Tech Stack

| Area | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Templates | Laravel Blade |
| Styling | Tailwind CSS, PostCSS |
| Frontend behavior | Alpine.js, JavaScript |
| Build tooling | Vite, Laravel Vite Plugin |
| Database | SQLite by default; Laravel migrations |
| Payments | Midtrans Snap |
| Testing | Pest, PHPUnit |

## Getting Started

### Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/akmalscript/pbp-flora.git
   cd pbp-flora
   ```

2. Install dependencies:

   ```bash
   composer install
   npm install
   ```

3. Configure the local environment and generate an application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, create the environment file with:

   ```powershell
   Copy-Item .env.example .env
   ```

4. Configure the database in `.env`. To use SQLite, set:

   ```env
   DB_CONNECTION=sqlite
   ```

   Create the SQLite database file if it does not already exist, then apply migrations:

   ```bash
   php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
   php artisan migrate
   ```

5. Add Midtrans credentials to your local `.env` when testing checkout:

   ```env
   MIDTRANS_SERVER_KEY=your-sandbox-server-key
   MIDTRANS_CLIENT_KEY=your-sandbox-client-key
   MIDTRANS_PRODUCTION=false
   ```

   Never commit `.env` or secret keys.

6. Start the app and Vite in separate terminals:

   ```bash
   php artisan serve
   npm run dev
   ```

   Visit [http://localhost:8000](http://localhost:8000).

## Useful Commands

```bash
# Build frontend assets for production
npm run build

# Run the test suite
php artisan test

# Run Laravel's combined local development workflow
composer run dev
```

## Project Structure

```text
app/
├── Http/                 Controllers, middleware, and requests
├── Models/               Eloquent models
└── Services/             External service integrations
database/                 Migrations, factories, and seeders
public/images/            Brand and product imagery
resources/
├── css/                  Application stylesheets
├── js/                   JavaScript entry points
└── views/                Blade pages, layouts, and components
routes/                   Web and authentication routes
tests/                    Feature and unit tests
```

## Project Links

- **Repository:** [akmalscript/pbp-flora](https://github.com/akmalscript/pbp-flora)
- **Live demo:** Not currently configured
- **Contributors:** [GitHub contributors](https://github.com/akmalscript/pbp-flora/graphs/contributors)

## License

This project declares the MIT License in `composer.json`.
