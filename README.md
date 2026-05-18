# 📚 BookNest — PHP + MySQL Bookstore Project

A complete college-level e-commerce project with a PHP backend, MySQL database, and a single-page frontend.

---

## 🗂 Project Structure

```
booknest/
├── index.php               ← Main frontend (single-page app)
├── styles.css              ← Separated CSS styling
├── database.sql            ← Run once to set up DB + seed data
├── includes/
│   └── config.php          ← DB credentials & helper functions
└── api/
    ├── auth.php            ← Login / Register / Logout / Session
    ├── books.php           ← Book listing, search, filter, sort
    ├── cart.php            ← Cart (DB for users, Session for guests)
    ├── orders.php          ← Place order, order history
    └── contact.php         ← Contact form submissions
```

---

## ⚙️ Setup Instructions

### 1. Requirements
- PHP 7.4 or higher (PHP 8.x recommended)
- MySQL 5.7+ or MariaDB 10+
- Apache / Nginx with PHP support (XAMPP / WAMP / Laragon work perfectly)

### 2. Import the Database
Open **phpMyAdmin** (or MySQL CLI) and run:
```sql
SOURCE /path/to/booknest/database.sql;
```
Or paste the content of `database.sql` into the phpMyAdmin SQL tab.

### 3. Configure DB Credentials
Edit `includes/config.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'booknest');
define('DB_USER', 'root');      // your MySQL username
define('DB_PASS', '');          // your MySQL password
```

### 4. Place Files in Web Root
Copy the `booknest/` folder to:
- **XAMPP**: `C:/xampp/htdocs/booknest/`
- **WAMP**: `C:/wamp64/www/booknest/`
- **Linux**: `/var/www/html/booknest/`

### 5. Open in Browser
```
http://localhost/booknest/
```

---

## 🚀 Features

| Feature              | Details                                              |
|----------------------|------------------------------------------------------|
| **User Auth**        | Register, Login, Logout with bcrypt password hashing |
| **Book Catalog**     | 16 books loaded from MySQL with real cover images   |
| **Search & Filter**  | Live search by title/author, filter by genre, sort   |
| **Shopping Cart**    | Session-based for guests, DB-based for logged-in users; guest cart merges on login |
| **Order Management** | Place orders, reduce stock, view order history       |
| **Receipt System**   | Order confirmation with detailed receipt             |
| **Contact Form**     | Messages saved to database                           |
| **Responsive UI**    | Works on mobile and desktop                          |
| **Admin Panel**      | Basic admin functionality (expandable)              |

---

## 🔌 API Reference

### Auth (`api/auth.php`)
| Method | Action        | Body                              | Description       |
|--------|---------------|-----------------------------------|-------------------|
| POST   | `?action=register` | `{full_name, email, password}` | Create account |
| POST   | `?action=login`    | `{email, password}`            | Login user     |
| POST   | `?action=logout`   | —                              | Logout         |
| GET    | `?action=me`       | —                              | Session info   |

### Books (`api/books.php`)
| Method | Params                            | Description           |
|--------|-----------------------------------|-----------------------|
| GET    | —                                 | All books             |
| GET    | `?id=5`                           | Single book           |
| GET    | `?search=harry`                   | Search                |
| GET    | `?genre=Fiction`                  | Filter by genre       |
| GET    | `?sort=price-asc` / `price-desc` / `rating` | Sort |

### Cart (`api/cart.php`)
| Method | Action          | Body                   | Description        |
|--------|-----------------|------------------------|--------------------| 
| GET    | —               | —                      | Get cart           |
| POST   | `?action=add`   | `{book_id, qty}`       | Add to cart        |
| POST   | `?action=update`| `{book_id, qty}`       | Change quantity    |
| POST   | `?action=remove`| `{book_id}`            | Remove item        |
| POST   | `?action=clear` | —                      | Empty cart         |

### Orders (`api/orders.php`) *(login required)*
| Method | Params / Action    | Description         |
|--------|--------------------|---------------------|
| POST   | `?action=place`    | Place order from cart |
| GET    | —                  | Order history       |
| GET    | `?id=12`           | Single order detail |

### Contact (`api/contact.php`)
| Method | Body                              | Description          |
|--------|-----------------------------------|----------------------|
| POST   | `{name, email, subject, message}` | Submit contact form  |

---

## 🛡 Security Features
- Passwords hashed with **bcrypt** (`password_hash`)
- **PDO prepared statements** — no SQL injection possible
- Input validation on all API endpoints
- Session-based authentication (no JWTs needed for college project)
- CORS headers for local development

---

## 📝 Database Tables
- `users` — registered accounts with roles
- `books` — product catalog with stock management
- `cart` — per-user cart items
- `orders` — placed orders with status tracking
- `order_items` — line items per order
- `contact_messages` — contact form submissions

---

## 📚 Sample Books Included

The database comes pre-loaded with 12 popular books:

1. **The Alchemist** by Paulo Coelho - ₹299
2. **Atomic Habits** by James Clear - ₹499
3. **Harry Potter & the Sorcerer's Stone** by J.K. Rowling - ₹350
4. **A Brief History of Time** by Stephen Hawking - ₹399
5. **Sapiens** by Yuval Noah Harari - ₹599
6. **Clean Code** by Robert C. Martin - ₹799
7. **The Great Gatsby** by F. Scott Fitzgerald - ₹249
8. **Thinking, Fast and Slow** by Daniel Kahneman - ₹549
9. **Rich Dad Poor Dad** by Robert Kiyosaki - ₹349
10. **1984** by George Orwell - ₹199
11. **The Lean Startup** by Eric Ries - ₹449
12. **Wings of Fire** by A.P.J. Abdul Kalam - ₹179

---

## 🎯 How to Use

### For New Users:
1. Click **Register** to create an account
2. Fill in your details (name, email, password 6+ chars)
3. Login with your credentials
4. Browse books and add to cart
5. Checkout to place orders

### For Testing:
- **Admin Login**: `admin@booknest.com` / `booknest123`
- Create regular user accounts through registration
- Guest users can browse and add to cart (merges on login)

---

## 🔧 Technical Architecture

- **Backend**: PHP 7.4+ with PDO for database operations
- **Database**: MySQL 5.7+ with proper foreign key relationships
- **Frontend**: Vanilla JavaScript with fetch API for AJAX calls
- **Styling**: Separated CSS file with clean, responsive design
- **Session Management**: PHP sessions for authentication
- **Security**: Bcrypt password hashing, prepared statements

---

## 📱 Pages & Functionality

### Public Pages
- **Home** - Welcome page with featured books
- **Catalog** - Full book collection with search/filter
- **Login** - User authentication
- **Register** - Create new account

### User Pages (Login Required)
- **Cart** - Shopping cart management
- **Profile** - User account details
- **Order History** - View past orders

### Admin Features
- User management
- Book inventory management
- Order tracking
- Contact message management

---

*Built with ❤️ for college project submission using PHP + MySQL*