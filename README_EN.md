# 📚 Bookstore Management System

A comprehensive web-based bookstore management system built with Laravel 10.49, featuring advanced catalog management, author database, and interactive rating system.

## ✨ Key Features

- 📖 **Complete Book Catalog** - Display thousands of books with multi-criteria search and filtering
- 🏆 **Author Leaderboard** - Rank authors by popularity, average ratings, and trending metrics
- ⭐ **Dynamic Rating System** - Book ratings with 1-10 scale and 24-hour cooldown per user
- 🔐 **Integrated Admin Panel** - Full dashboard for managing books, authors, and rating moderation
- ⚡ **Optimized Performance** - Designed to handle large datasets (100,000+ books, 500,000+ ratings)

## 📑 Table of Contents

- [Application Views](#-application-views)
- [Routing Structure](#-routing-structure)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Database Seeding](#-database-seeding)
- [Usage Guide](#-usage-guide)
- [Security](#-security)
- [Technology Stack](#-technology-stack)

## 🖼️ Application Views

### Public Pages
- **Homepage** - Landing page with highlights and main navigation
- **Book Catalog** - Grid/list view with advanced filtering and sorting
- **Book Details** - Comprehensive book information, rating statistics, and review form
- **Top Authors** - Author leaderboard with various performance metrics
- **Rating Form** - Interactive interface for submitting reviews (requires login)

### Admin Panel
- **Dashboard** - Application statistics overview and activity logs
- **Book Management** - CRUD operations with bulk actions
- **Author Management** - Author database with complete profiles
- **Rating Moderation** - Review and remove inappropriate ratings

## 🛣️ Routing Structure

### Public Routes (No Authentication Required)

```
GET  /                      → Redirect to homepage
GET  /home                  → Application homepage
GET  /books                 → Book catalog with filters
GET  /books/{id}            → Specific book details
GET  /authors               → Top authors leaderboard
GET  /login                 → Login page (users & admins)
GET  /register              → New user registration
```

### User Routes (Authentication Required)

```
GET     /dashboard          → User dashboard
GET     /profile            → User profile page
PATCH   /profile            → Update profile data
DELETE  /profile            → Delete user account
GET     /ratings/create     → Book rating form
```

### Admin Routes (Admin Role Required)

```
# Dashboard
GET  /admin/dashboard       → Admin dashboard with statistics

# Book Management
GET     /admin/books                → List all books
GET     /admin/books/create         → Add book form
POST    /admin/books                → Save new book
GET     /admin/books/{book}/edit    → Edit book form
PUT     /admin/books/{book}         → Update book
DELETE  /admin/books/{book}         → Delete book

# Author Management
GET     /admin/authors                  → List all authors
GET     /admin/authors/create           → Add author form
POST    /admin/authors                  → Save new author
GET     /admin/authors/{author}/edit    → Edit author form
PUT     /admin/authors/{author}         → Update author
DELETE  /admin/authors/{author}         → Delete author

# Rating Management
GET     /admin/ratings              → List all ratings
DELETE  /admin/ratings/{rating}     → Delete rating
```

### API Endpoints

```
GET   /api/books                  → Book list (JSON)
GET   /api/authors                → Author list (JSON)
POST  /api/ratings                → Submit new rating
POST  /api/books/{bookId}/rate    → Rate specific book
GET   /api/categories             → Category list (JSON)
```

## 📦 Installation

### System Requirements

- PHP 8.1 or higher
- Composer 2.x
- Database (MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.35+)
- Node.js 16+ & npm
- PHP Extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

### Installation Steps

**1. Clone Repository**
```bash
git clone <repository-url>
cd bookstore
```

**2. Install Backend Dependencies**
```bash
composer install
```

**3. Install Frontend Dependencies**
```bash
npm install
```

**4. Setup Environment File**
```bash
cp .env.example .env
```

**5. Generate Application Key**
```bash
php artisan key:generate
```

**6. Configure Database**

Edit the `.env` file and adjust your database settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookstore
DB_USERNAME=root
DB_PASSWORD=
```

**7. Run Database Migrations**
```bash
php artisan migrate
```

**8. (Optional) Seed Dummy Data**
```bash
php -d memory_limit=2G artisan db:seed
```

**9. Compile Frontend Assets**
```bash
# Development
npm run dev

# Production
npm run build
```

**10. Start Development Server**
```bash
php artisan serve
```

The application will run at `http://localhost:8000`

## ⚙️ Configuration

### Creating Admin User

Use Laravel Tinker to create an admin user:

```bash
php artisan tinker
```

Then run:

```php
App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@bookstore.com',
    'password' => bcrypt('admin123'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);
```

### Storage Link (For Image Uploads)

```bash
php artisan storage:link
```

### Cache Optimization (Production)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🌱 Database Seeding

The application provides seeders to generate large amounts of dummy data.

### Seeding Commands

```bash
# With 2GB memory allocation
php -d memory_limit=2G artisan db:seed

# Or specific seeders
php artisan db:seed --class=AuthorSeeder
php artisan db:seed --class=BookSeeder
php artisan db:seed --class=RatingSeeder
```

### Generated Data

| Model | Count | Description |
|-------|-------|-------------|
| Authors | 1,000 | Authors with complete biographies |
| Categories | 3,000 | Diverse book categories |
| Books | 100,000 | Books with author & category relations |
| Ratings | 500,000 | User ratings for books (1-10 scale) |

### ⚠️ Important Seeding Notes

- **Minimum RAM**: 2GB (use `-d memory_limit=2G` flag)
- **Processing Time**: 10-20 minutes depending on hardware specifications
- **Disk Space**: ~500MB for SQLite database, less for MySQL/PostgreSQL
- Seeding automatically creates aggregate statistics (book_statistics, author_statistics)

## 📖 Usage Guide

### For Visitors (Guest)

1. **Browse Book Catalog**
   - Access `/books` to view all books
   - Use search bar to find by title, author, ISBN, or publisher
   - Filter by author, publication year, or category
   - Sort by rating, popularity, or alphabetically

2. **View Book Details**
   - Click on book cards to see complete information
   - Check rating statistics and popularity trends
   - Read descriptions and publication details

3. **Explore Authors**
   - Visit `/authors` to see the leaderboard
   - Filter by Most Popular, Highest Rated, or Trending
   - Click authors to view all their books

### For Registered Users

1. **Registration/Login**
   - Create account at `/register`
   - Login via `/login`

2. **Submit Ratings**
   - Access `/ratings/create` or click "Rate This Book" button
   - Select book from dropdown (auto-complete)
   - Provide 1-10 rating using star selector
   - Submit (24-hour cooldown per book)

3. **Manage Profile**
   - Edit profile information at `/profile`
   - Change password
   - Delete account if needed

### For Admins

1. **Access Admin Panel**
   - Login with admin account
   - Auto-redirect to `/admin/dashboard`

2. **Book Management**
   - Add new books with complete form
   - Edit existing book information
   - Delete books (soft delete)
   - Assign multiple categories
   - Upload cover images (coming soon)

3. **Author Management**
   - CRUD operations on author database
   - Edit biographies and contact information
   - View performance statistics

4. **Rating Moderation**
   - Review all submitted ratings
   - Remove spam or inappropriate ratings
   - Filter by user or book

## 🔒 Security

### Authentication & Authorization

- **Laravel Breeze** for authentication scaffolding
- **Middleware Protection** on all sensitive routes
- **Role-based Access Control** (RBAC) via `is_admin` flag
- **CSRF Protection** automatic on all POST/PUT/DELETE forms

### Input Validation

- Server-side validation on all form submissions
- **Rate Limiting** on API endpoints (60 requests/minute)
- **SQL Injection Protection** via Eloquent ORM
- **XSS Prevention** via Blade templating auto-escape

### Best Practices

- Passwords hashed using bcrypt
- Session management with secure cookies
- Environment variables for credentials
- Regular security updates via Composer

## 🛠️ Technology Stack

### Backend Stack

- **Framework**: Laravel 10.49 (PHP 8.1+)
- **Database**: MySQL 8.0 / PostgreSQL / SQLite
- **ORM**: Eloquent
- **Authentication**: Laravel Breeze
- **API**: RESTful with JSON responses

### Frontend Stack

- **Templating**: Blade Templates
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js 3.x (reactive components)
- **Build Tool**: Vite
- **Icons**: Heroicons / Lucide Icons

### Database Schema

```
📊 Core Tables:
- users (users & admins)
- authors (book authors)
- categories (book categories)
- books (book catalog)
- book_category (pivot table)
- ratings (user ratings)

📈 Statistics Tables:
- book_statistics (rating aggregates per book)
- author_statistics (author performance aggregates)
```

### Package Dependencies

**Backend:**
- laravel/framework ^10.10
- laravel/breeze ^1.29
- doctrine/dbal (for migrations)

**Frontend:**
- alpinejs ^3.13
- tailwindcss ^3.4
- @tailwindcss/forms

