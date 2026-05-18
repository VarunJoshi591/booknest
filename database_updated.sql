-- ============================================================
--  BookNest Database Schema (Updated with Payment System)
--  Run this file to set up the complete database with payment tracking
--  Compatible with MySQL 5.7+  /  MariaDB 10+
-- ============================================================

DROP DATABASE IF EXISTS booknest;
CREATE DATABASE booknest CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booknest;

-- ── USERS ──────────────────────────────────────────────────
CREATE TABLE users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  full_name   VARCHAR(120)        NOT NULL,
  email       VARCHAR(180)        NOT NULL UNIQUE,
  password    VARCHAR(255)        NOT NULL,          -- bcrypt hash
  role        ENUM('user','admin') DEFAULT 'user',
  created_at  TIMESTAMP           DEFAULT CURRENT_TIMESTAMP
);

-- ── BOOKS ──────────────────────────────────────────────────
CREATE TABLE books (
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
CREATE TABLE cart (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT  NOT NULL,
  book_id    INT  NOT NULL,
  qty        INT  NOT NULL DEFAULT 1,
  added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (book_id) REFERENCES books(id)  ON DELETE CASCADE,
  UNIQUE KEY  uq_user_book (user_id, book_id)
);

-- ── ORDERS (Updated with Payment Fields) ───────────────────
CREATE TABLE orders (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  user_id        INT            NOT NULL,
  total_amount   DECIMAL(10,2)  NOT NULL,
  shipping       DECIMAL(10,2)  DEFAULT 0,
  status         ENUM('pending','confirmed','shipped','delivered','cancelled') DEFAULT 'confirmed',
  payment_status ENUM('pending','completed','failed','refunded') DEFAULT 'completed',
  payment_method VARCHAR(50)    DEFAULT 'card',
  payment_id     VARCHAR(100)   DEFAULT NULL,
  placed_at      TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  paid_at        TIMESTAMP      DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── ORDER ITEMS ────────────────────────────────────────────
CREATE TABLE order_items (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  order_id  INT           NOT NULL,
  book_id   INT           NOT NULL,
  qty       INT           NOT NULL,
  price     DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (book_id)  REFERENCES books(id)  ON DELETE CASCADE
);

-- ── CONTACT MESSAGES ───────────────────────────────────────
CREATE TABLE contact_messages (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(120) NOT NULL,
  email      VARCHAR(180) NOT NULL,
  subject    VARCHAR(100) NOT NULL,
  message    TEXT         NOT NULL,
  sent_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- ── REVIEWS ────────────────────────────────────────────────
CREATE TABLE reviews (
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
('Educated',                      'Tara Westover',         'Memoir',      449,  5, 334, '#4B0082', 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1506026635i/35133922.jpg', 'A memoir about a young girl who, kept out of school, leaves her survivalist family and goes on to earn a PhD from Cambridge University. A story about the transformative power of education.', 65),

-- Romantic / Love Novels
('Pride and Prejudice',           'Jane Austen',           'Romance',     199,  5, 279, '#e84393', 'https://books.google.com/books/content?id=s1gVAAAAYAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'A classic novel of manners, upbringing, morality, education, and marriage in the society of the landed gentry of the British Regency.', 120),
('The Notebook',                  'Nicholas Sparks',       'Romance',     249,  4, 214, '#fd79a8', 'https://books.google.com/books/content?id=GoI7PgAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'A story of two teenagers from opposite sides of the tracks who fall in love during one summer.', 80),
('Outlander',                     'Diana Gabaldon',        'Romance',     399,  5, 850, '#6c5ce7', 'https://books.google.com/books/content?id=7iF6BgAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A time-traveling romance about a World War II nurse who is transported to 1743 Scotland.', 95),
('Me Before You',                 'Jojo Moyes',            'Romance',     299,  4, 369, '#00b894', 'https://books.google.com/books/content?id=bue8EQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A young woman takes a job caring for a wealthy, young banker left paralyzed from an accident.', 110),
('The Fault in Our Stars',        'John Green',            'Romance',     349,  5, 313, '#0984e3', 'https://books.google.com/books/content?id=Qk8n0olOX5MC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Two teenage cancer patients begin a life-affirming journey to visit a reclusive author in Amsterdam.', 150),
('Romeo and Juliet',              'William Shakespeare',   'Romance',     149,  4, 320, '#d63031', 'https://books.google.com/books/content?id=pprVEAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'The classic tragedy of two star-crossed lovers whose deaths ultimately reconcile their feuding families.', 200),
('It Ends with Us',               'Colleen Hoover',        'Romance',     450,  5, 385, '#e17055', 'https://books.google.com/books/content?id=UnIQEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Sometimes the one who loves you is the one who hurts you the most. A brave and heartbreaking novel.', 130),
('Twilight',                      'Stephenie Meyer',       'Romance',     399,  3, 498, '#2d3436', 'https://books.google.com/books/content?id=lGjFtMRqp_YC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'About a teenager who risks everything when she falls in love with a vampire.', 90),
('The Time Travelers Wife',       'Audrey Niffenegger',    'Romance',     299,  4, 536, '#00cec9', '', 'A love story about a man with a genetic disorder that causes him to time travel unpredictably.', 75),
('The Duke and I',                'Julia Quinn',           'Romance',     350,  4, 384, '#fdcb6e', 'https://books.google.com/books/content?id=-mlBcC_JXv8C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'The first book in the Bridgerton series, focusing on Daphne Bridgerton and the Duke of Hastings.', 85),

-- Mystery Books
('Gone Girl',                     'Gillian Flynn',         'Mystery',     399,  4, 415, '#2c3e50', 'https://books.google.com/books/content?id=hxL2qWMAgv8C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'On his fifth wedding anniversary, Nick Dunnes wife Amy suddenly disappears, and he becomes the prime suspect.', 105),
('The Girl with the Dragon Tattoo','Stieg Larsson',        'Mystery',     449,  5, 465, '#bdc3c7', 'https://books.google.com/books/content?id=4FxNNFRnsZsC&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'A disgraced journalist and a tattooed computer hacker investigate the decades-old disappearance of a wealthy industrialists niece.', 95),
('And Then There Were None',      'Agatha Christie',       'Mystery',     299,  5, 274, '#34495e', 'https://books.google.com/books/content?id=gAD4EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Ten strangers are invited to an isolated island, where they are killed off one by one.', 115),
('The Da Vinci Code',             'Dan Brown',             'Mystery',     350,  4, 454, '#7f8c8d', 'https://books.google.com/books/content?id=6-pmDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A murder in the Louvre Museum reveals a sinister plot to uncover a secret that has been protected since the days of Christ.', 140),
('The Silent Patient',            'Alex Michaelides',      'Mystery',     399,  4, 336, '#95a5a6', 'https://books.google.com/books/content?id=a6NnDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A famous painters shoots her husband five times in the face and then never speaks another word.', 125),
('Big Little Lies',               'Liane Moriarty',        'Mystery',     349,  4, 460, '#16a085', 'https://books.google.com/books/content?id=xOaKAwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A murder mystery centered around three women and their children at a picturesque elementary school.', 80),
('Sharp Objects',                 'Gillian Flynn',         'Mystery',     299,  4, 254, '#8e44ad', 'https://books.google.com/books/content?id=4cNbvgEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'A reporter returns to her hometown to cover the murders of two preteen girls.', 70),
('Murder on the Orient Express',  'Agatha Christie',       'Mystery',     249,  5, 274, '#c0392b', 'https://books.google.com/books/content?id=mqDEqJMosnAC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Just after midnight, the famous Orient Express is stopped in its tracks by a snowdrift. By morning, a passenger is dead.', 135),
('The Girl on the Train',         'Paula Hawkins',         'Mystery',     350,  3, 395, '#d35400', 'https://books.google.com/books/content?id=BlZXvgEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'A divorced woman becomes entangled in a missing persons investigation that promises to send shockwaves throughout her life.', 90),
('In the Woods',                  'Tana French',           'Mystery',     399,  4, 429, '#27ae60', 'https://books.google.com/books/content?id=MidT_WA_lmYC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A detective investigates the murder of a twelve-year-old girl in the same woods where his two best friends vanished twenty years ago.', 60),

-- Motivational Books
('The 7 Habits of Highly Effective People', 'Stephen Covey','Motivational',499,  5, 381, '#f39c12', 'https://books.google.com/books/content?id=upUxaNWSaRIC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A holistic, integrated, principle-centered approach for solving personal and professional problems.', 150),
('Think and Grow Rich',           'Napoleon Hill',         'Motivational',199,  4, 238, '#e67e22', 'https://books.google.com/books/content?id=sJIJEQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'The most important financial book ever written. Examines the psychological power of thought and the brain.', 180),
('Mans Search for Meaning',       'Viktor E. Frankl',      'Motivational',249,  5, 165, '#3498db', '', 'A psychiatrist recounts his experiences in Auschwitz and explains his method for finding a reason to live.', 140),
('Awaken the Giant Within',       'Tony Robbins',          'Motivational',399,  4, 544, '#2ecc71', 'https://books.google.com/books/content?id=iPpyLpX0Y1sC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'How to take immediate control of your mental, emotional, physical and financial destiny.', 110),
('How to Win Friends and Influence People', 'Dale Carnegie','Motivational',199,  5, 288, '#9b59b6', 'https://books.google.com/books/content?id=1rW-QpIAs8UC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'You can go after the job you want...and get it! You can take the job you have...and improve it!', 200),
('The Power of Now',              'Eckhart Tolle',         'Motivational',299,  4, 229, '#1abc9c', 'https://books.google.com/books/content?id=NFQ7DwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'A guide to spiritual enlightenment that emphasizes the importance of living in the present moment.', 95),
('Mindset',                       'Carol S. Dweck',        'Motivational',349,  4, 320, '#f1c40f', 'https://books.google.com/books/content?id=YDhjE39j-t0C&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'Shows how success in school, work, sports, the arts, and almost every area of human endeavor can be dramatically influenced by how we think.', 120),
('Daring Greatly',                'Brené Brown',           'Motivational',399,  5, 287, '#e74c3c', 'https://books.google.com/books/content?id=cyRDrMMX8WoC&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'How the courage to be vulnerable transforms the way we live, love, parent, and lead.', 85),
('Cant Hurt Me',                  'David Goggins',         'Motivational',449,  5, 364, '#34495e', '', 'Master your mind and defy the odds. A story of overcoming severe adversity to become a US Armed Forces icon.', 160),
('Start with Why',                'Simon Sinek',           'Motivational',399,  4, 256, '#2980b9', 'https://books.google.com/books/content?id=r2yCRUxo0EYC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'How great leaders inspire everyone to take action by starting with WHY.', 100);

-- Default admin user (password: booknest123)
INSERT INTO users (full_name, email, password, role) VALUES
('Admin User', 'admin@booknest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample orders with payment data
INSERT INTO orders (user_id, total_amount, shipping, status, payment_status, payment_method, payment_id) VALUES
(1, 648, 0, 'confirmed', 'completed', 'card', 'PAY_DEMO_001_1234567890'),
(1, 398, 49, 'shipped', 'completed', 'upi', 'PAY_DEMO_002_1234567891');

-- Sample order items
INSERT INTO order_items (order_id, book_id, qty, price) VALUES
(1, 1, 1, 299),
(1, 2, 1, 349),
(2, 3, 1, 349);