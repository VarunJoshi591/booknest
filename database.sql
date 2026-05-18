-- ============================================================
--  BookNest Database Schema
--  Run this file once to set up the full database.
--  Compatible with MySQL 5.7+  /  MariaDB 10+
-- ============================================================

CREATE DATABASE IF NOT EXISTS booknest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booknest;

-- ── USERS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  full_name   VARCHAR(120)        NOT NULL,
  email       VARCHAR(180)        NOT NULL UNIQUE,
  password    VARCHAR(255)        NOT NULL,          -- bcrypt hash
  role        ENUM('user','admin') DEFAULT 'user',
  created_at  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
);

-- ── BOOKS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS books (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(220)  NOT NULL,
  author      VARCHAR(120)  NOT NULL,
  genre       VARCHAR(80)   NOT NULL,
  price       DECIMAL(10,2) NOT NULL,
  rating      TINYINT       DEFAULT 4,
  pages       INT           DEFAULT 0,
  cover_color VARCHAR(20)   DEFAULT '#2c3e50',
  image_url   VARCHAR(500)  DEFAULT NULL,
  description TEXT,
  stock       INT           DEFAULT 50,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- ── CART ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cart (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT  NOT NULL,
  book_id    INT  NOT NULL,
  qty        INT  NOT NULL DEFAULT 1,
  added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id)  ON DELETE CASCADE,
  UNIQUE KEY  uq_user_book (user_id, book_id)
);

-- ── ORDERS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  user_id      INT            NOT NULL,
  total_amount DECIMAL(10,2)  NOT NULL,
  shipping     DECIMAL(10,2)  DEFAULT 0,
  status       ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'pending',
  payment_status ENUM('pending','completed','failed','refunded') DEFAULT 'pending',
  payment_method VARCHAR(50)   DEFAULT NULL,
  payment_id   VARCHAR(100)   DEFAULT NULL,
  placed_at    TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  paid_at      TIMESTAMP      NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── ORDER ITEMS ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  order_id  INT           NOT NULL,
  book_id   INT           NOT NULL,
  qty       INT           NOT NULL,
  price     DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id)  REFERENCES books(id)  ON DELETE CASCADE
);

-- ── CONTACT MESSAGES ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS contact_messages (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(180) NOT NULL,
  subject    VARCHAR(100) NOT NULL,
  message    TEXT         NOT NULL,
  sent_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ── REVIEWS ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS reviews (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  book_id    INT NOT NULL,
  rating     TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  comment    TEXT,
  date       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- ============================================================
--  SEED DATA
-- ============================================================

INSERT INTO books (title, author, genre, price, rating, pages, cover_color, image_url, description, stock) VALUES
('The Alchemist',                 'Paulo Coelho',          'Fiction',     299,  5, 197, '#8e44ad', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1654371463i/18144590.jpg', 'A magical story of a shepherd boy who dreams of treasure beyond his homeland. A modern classic about following your dreams and listening to your heart.', 100),
('Atomic Habits',                 'James Clear',           'Self-Help',   499,  5, 319, '#2980b9', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1535115320i/40121378.jpg', 'An easy and proven way to build good habits and break bad ones. Tiny changes, remarkable results — discover how 1% improvements lead to extraordinary outcomes.', 85),
('Harry Potter and the Sorcerers Stone', 'J.K. Rowling',  'Fantasy',     350,  5, 309, '#8B0000', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1474154022i/3.jpg', 'The boy who lived. Harry Potter discovers he is a wizard on his 11th birthday and joins Hogwarts School of Witchcraft and Wizardry.', 120),
('A Brief History of Time',       'Stephen Hawking',       'Science',     399,  4, 212, '#16a085', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1333578746i/3869.jpg', 'A landmark volume in science writing — Hawking takes the reader on an exploration of the universe and explains it in simple terms for a general audience.', 60),
('Sapiens',                       'Yuval Noah Harari',     'History',     599,  5, 443, '#d35400', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1420585954i/23692271.jpg', 'A brief history of humankind from the Stone Age through the 21st century. One of the most thought-provoking books of our time.', 75),
('Clean Code',                    'Robert C. Martin',      'Technology',  799,  4, 464, '#27ae60', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1436202607i/3735293.jpg', 'A handbook of agile software craftsmanship. Learn to write clean, readable, and maintainable code. A must-read for every programmer.', 40),
('The Great Gatsby',              'F. Scott Fitzgerald',   'Fiction',     249,  4, 180, '#2c3e50', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1490528560i/4671.jpg', 'Set in the summer of 1922, the novel tells of Jay Gatsbys obsessive love for the beautiful Daisy Buchanan — a story of the American Dream and its corruption.', 90),
('Thinking Fast and Slow',        'Daniel Kahneman',       'Non-Fiction', 549,  5, 499, '#c0392b', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1317793965i/11468377.jpg', 'Nobel Prize winner Daniel Kahneman takes us on a journey through the mind and explains the two systems that drive the way we think.', 55),
('Rich Dad Poor Dad',             'Robert Kiyosaki',       'Self-Help',   349,  4, 336, '#e67e22', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1388211242i/69571.jpg', 'What the rich teach their kids about money that the poor and middle class do not. A personal finance classic that challenges conventional thinking.', 80),
('1984',                          'George Orwell',         'Fiction',     199,  5, 328, '#555555', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1657781256i/61439040.jpg', 'A chilling vision of a totalitarian future where the government controls everything — even thoughts. One of the most influential novels ever written.', 110),
('The Lean Startup',              'Eric Ries',             'Technology',  449,  4, 299, '#1abc9c', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1333576876i/10127019.jpg', 'How todays entrepreneurs use continuous innovation to create radically successful businesses. A methodology that has transformed business worldwide.', 45),
('Wings of Fire',                 'A.P.J. Abdul Kalam',   'Non-Fiction', 179,  5, 196, '#f39c12', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1295670969i/634583.jpg', 'The inspiring autobiography of one of Indias greatest scientists and presidents. A story of determination, passion, and the power of dreams.', 150),
('The Kite Runner',               'Khaled Hosseini',       'Fiction',     329,  5, 371, '#8B4513', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1579036753i/77203.jpg', 'A powerful story of friendship, betrayal, and redemption set against the backdrop of Afghanistan. A haunting tale that explores the price of betrayal and the possibility of atonement.', 95),
('Steve Jobs',                    'Walter Isaacson',       'Biography',   599,  4, 656, '#000000', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1511288482i/11084145.jpg', 'The exclusive biography of Steve Jobs based on more than forty interviews with Jobs conducted over two years. An intimate portrait of the brilliant, driven man behind Apple.', 70),
('The Psychology of Money',       'Morgan Housel',         'Finance',     399,  5, 252, '#2E8B57', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1581527774i/41881472.jpg', 'Timeless lessons on wealth, greed, and happiness. How we think about money and what we do with it affects every aspect of our lives in ways we might not expect.', 85),
('Educated',                      'Tara Westover',         'Memoir',      449,  5, 334, '#4B0082', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1506026635i/35133922.jpg', 'A memoir about a young girl who, kept out of school, leaves her survivalist family and goes on to earn a PhD from Cambridge University. A story about the transformative power of education.', 65);

-- Default admin user  (password: booknest123)
INSERT INTO users (full_name, email, password, role) VALUES
('Admin User', 'admin@booknest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
