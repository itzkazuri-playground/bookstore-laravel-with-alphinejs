# Bookstore Application

A Laravel application for managing a bookstore with features for book listings, author rankings, and book ratings.

## Features

- List of books with filter and search capabilities
- Top 20 most famous authors with multiple ranking systems
- Book rating system with 24-hour cooldown
- Efficient handling of large datasets (100,000+ books, 500,000+ ratings)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bookstore
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies (if needed)**
   ```bash
   npm install
   ```

4. **Set up environment variables**
   ```bash
   cp .env.example .env
   ```
   
   Update the .env file with your database configuration:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bookstore
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run database migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## Database Structure

The application uses the following tables:
- `users` - For admin authentication
- `authors` - Store author information
- `categories` - Book categories
- `books` - Book information with author relationships
- `book_category` - Pivot table for books and categories
- `ratings` - User ratings for books
- `book_statistics` - Pre-calculated book statistics for performance
- `author_statistics` - Pre-calculated author statistics for performance

## API Documentation

API documentation is available at `/api/documentation` after installation.

## Database Requirements

- MySQL database with proper configuration in `.env` file
- Minimum 2GB of RAM recommended for handling large datasets
- Sufficient disk space for the large dataset (100,000+ books, 500,000+ ratings)

## Seeded Data

The seeding process will create:
- 1,000 fake authors
- 3,000 fake categories
- 100,000 fake books
- 500,000 fake ratings

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
