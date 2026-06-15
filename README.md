# SupplyWise — Supply Chain Intelligence

A **Decision Support System (DSS)** for supplier evaluation and ranking built with Laravel, Livewire, and a custom Material Design 3 UI. Uses the SAW (Simple Additive Weighting) method to rank suppliers based on configurable weighted criteria.

---

## Features

- 🔐 Authentication via **Google OAuth** (Laravel Socialite) or email/password
- 📦 **Supplier management** — add, edit, delete, categorize suppliers
- ⚖️ **Criteria management** — define benefit/cost criteria with adjustable weights
- 📊 **Supplier value input** — assign scores per supplier per criterion
- 🏆 **Automatic ranking** — SAW-based weighted score computation
- 🎨 Material Design 3 UI with custom Tailwind theme

---

## Requirements

| Tool | Version |
|------|---------|
| PHP | ≥ 8.3 |
| Composer | ≥ 2.x |
| Node.js | ≥ 18.x |
| MySQL / MariaDB | ≥ 8.0 |

> **Recommended local stack:** [Laragon](https://laragon.org/) (Windows) or Laravel Herd (macOS/Linux)

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/SupplyWise.git
cd SupplyWise
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supplywise
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Set up the database

Create the database (e.g. in phpMyAdmin or your MySQL client), then run migrations:

```bash
php artisan migrate
```

### 6. Set up Google OAuth

Go to the [Google Cloud Console](https://console.cloud.google.com/) and create an OAuth 2.0 Client ID. Then add the credentials to your `.env`:

```env
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

> Make sure `http://localhost:8000/auth/google/callback` is listed as an **Authorized redirect URI** in your Google Cloud Console.

### 7. Start the development servers

```bash
# Start all services at once (Laravel + Vite)
composer run dev
```

Or start them individually:

```bash
php artisan serve   # Laravel dev server → http://localhost:8000
npm run dev         # Vite asset bundler (hot reload)
```

Open your browser at **http://localhost:8000**.

---

## One-command setup

If you just cloned the repo, you can run:

```bash
composer run setup
```

This will: install dependencies, copy `.env`, generate the app key, run migrations, and build assets.

> You still need to manually configure your DB credentials and Google OAuth keys in `.env` first.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 |
| UI Components | Livewire 3 + Livewire Volt |
| Auth | Laravel Breeze + Laravel Socialite (Google) |
| Styling | Tailwind CSS (CDN) with Material Design 3 theme |
| Database | MySQL |
| Build tool | Vite |

---

## Project Structure

```
app/
├── Http/Controllers/Auth/   # Google OAuth controller
├── Livewire/                # Full-page & embedded Livewire components
│   ├── AdminDashboard.php
│   ├── SupplierManager.php
│   ├── CriteriaManager.php
│   ├── ResultManager.php
│   └── SupplierValueManager.php
resources/views/
├── layouts/                 # App shell (head, sidebar, topbar, scripts)
├── livewire/                # Livewire component views
└── components/              # Blade UI components (sidebar, topbar, etc.)
routes/
├── web.php                  # Main app routes
└── auth.php                 # Auth routes (Breeze/Volt)
```

---

## License

MIT
