# Flora

<p align="center">
  <img src="public/images/flora_logo2.jpg" alt="Flora logo" width="220">
</p>

<p align="center">A floral storefront experience for discovering bouquets, vase arrangements, and other flower products.</p>

<p align="center">
  <a href="https://github.com/akmalscript/pbp-flora"><img src="https://img.shields.io/badge/repository-GitHub-181717?logo=github" alt="GitHub repository"></a>
  <a href="https://laravel.com/docs/12.x"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://www.php.net/releases/8.2/en.php"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.2 or newer"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green" alt="MIT license"></a>
</p>

## Overview

Flora is a Laravel-based flower shop website with a warm, product-focused interface. The project currently includes a branded home page, featured flower products, customer authentication, profile management, and Blade UI templates for product details, cart, order history, and order details.

## Preview

![Flora storefront preview](public/images/home.jpg)

The repository also includes additional local visual assets in [`public/images`](public/images), including the Flora logo, tulip, sunflower, and flower arrangement imagery.

## Features

- Branded Flora landing page with a hero section and featured products.
- Product-oriented storefront presentation for flower arrangements and decorations.
- Customer registration, login, logout, password reset, password confirmation, and email verification flows.
- Authenticated profile editing and password management.
- Blade templates for product details, cart summary, order history, and order details.
- Client-side cart interactions, quantity controls, sorting/filtering UI, and coupon-code feedback in the included templates.
- Responsive styling with Tailwind CSS utilities.
- Local flower imagery and brand assets served from `public/images`.

> **Current implementation note:** The cart, product, and order screens are present as UI templates with client-side/demo interactions. The current route files expose the home, authentication, dashboard, and profile flows; a public demo URL is not configured in this repository.

## Tech Stack

| Area | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Views | Blade templates |
| Frontend styling | Tailwind CSS, PostCSS, Autoprefixer |
| Frontend behavior | Alpine.js and vanilla JavaScript |
| Asset bundling | Vite with Laravel Vite Plugin |
| Database defaults | SQLite via Laravel migrations |
| Authentication | Laravel Breeze-style authentication scaffolding |
| Testing | Pest, PHPUnit, Laravel testing utilities |
| Development tools | Laravel Sail, Laravel Pint, Laravel Pail, Laravel Tinker |

## Demo and Repository

- **Repository:** [github.com/akmalscript/pbp-flora](https://github.com/akmalscript/pbp-flora)
- **Live demo:** Not specified in the repository at this time.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite, or another database supported by Laravel if you update the environment configuration

## Installation

1. Clone the repository and enter the project directory:

   ```bash
   git clone https://github.com/akmalscript/pbp-flora.git
   cd pbp-flora
   ```

2. Install PHP and JavaScript dependencies:

   ```bash
   composer install
   npm install
   ```

3. Create the local environment file and application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp`.

4. Configure the database in `.env`. The project is configured for SQLite by default:

   ```env
   DB_CONNECTION=sqlite
   ```

   Create the SQLite file if it does not exist, then run migrations:

   ```bash
   php artisan migrate
   ```

5. Start the frontend asset server and Laravel development server in separate terminals:

   ```bash
   npm run dev
   php artisan serve
   ```

   Open [http://localhost:8000](http://localhost:8000) in your browser.

## Useful Commands

```bash
# Compile production assets
npm run build

# Run the test suite
php artisan test

# Run the full local development workflow
composer run dev
```

## Project Structure

```text
app/
├── Http/                 Controllers, requests, and authentication logic
├── Models/               Eloquent models
└── View/Components/      Blade layout components
database/                 Migrations, factories, and seeders
public/
└── images/               Flora logos and flower imagery
resources/
├── css/                  Application stylesheets
├── js/                   JavaScript entry points
└── views/                Blade layouts, pages, components, and auth screens
routes/                   Web, authentication, and console routes
tests/                    Feature and unit tests
```

## Testing

Run the automated tests with:

```bash
php artisan test
```

The test suite includes HTTP smoke tests and authentication/profile coverage under `tests/Feature`, alongside unit-test scaffolding under `tests/Unit`.

## Contributing

Contributions are welcome. For a clean contribution workflow:

1. Create a focused branch from the current default branch.
2. Keep changes scoped and follow the existing Laravel/PHP conventions.
3. Run `php artisan test` before opening a pull request.
4. Include a concise description of the user-facing or technical change.

## Contributors

The repository does not currently include a maintained contributor list. Please see the [GitHub contributors page](https://github.com/akmalscript/pbp-flora/graphs/contributors) for the latest repository activity.

## License

This project declares the **MIT License** in `composer.json`. A standalone `LICENSE` file is not currently included in the repository.

## Acknowledgements

- [Laravel](https://laravel.com/) for the application framework.
- [Tailwind CSS](https://tailwindcss.com/) for utility-first styling.
- [Vite](https://vite.dev/) for frontend asset development and bundling.
