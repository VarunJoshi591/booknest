<?php
/**
 * BookNest - College Level Bookstore Project
 * Main Frontend (index.php)
 * 
 * Features:
 * - Simple Grid Layout
 * - Search & Filter
 * - Cart Management
 * - User Login/Logout
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="Logo.png">
    <title>BookNest - Online Bookstore</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            background: #f4f7f6;
            color: #333;
            font-weight: 400;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        nav {
            background: #f1f3f5;
            color: #212529;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #dee2e6;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .logo:hover {
            opacity: 0.9;
        }

        .logo img {
            height: 45px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .brand-text {
            font-size: 24px;
            font-weight: 800;
            color: #212529;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
        }

        .brand-text span {
            color: #3498db;
            font-weight: 400;
        }

        .nav-links a {
            color: #212529;
            text-decoration: none;
            margin-left: 20px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: -0.2px;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #3498db;
            text-decoration: none;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            min-height: 70vh;
        }

        .hero {
            background: white;
            padding: 50px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #ddd;
            margin-bottom: 30px;
        }

        .hero h1 {
            color: #2c3e50;
            margin-top: 0;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .hero p {
            font-weight: 400;
            letter-spacing: -0.2px;
        }

        h2 {
            color: #2c3e50;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .book-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            text-align: center;
            transition: transform 0.2s;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cover {
            height: 160px;
            background: #34495e;
            border-radius: 6px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 50px;
            font-weight: bold;
        }

        .book-card h3 {
            margin: 10px 0 5px 0;
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .book-card p {
            color: #666;
            font-size: 14px;
            margin: 0 0 15px 0;
            font-weight: 400;
        }

        .price {
            font-weight: 700;
            font-size: 20px;
            color: #e67e22;
            margin-bottom: 15px;
        }

        .btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
            letter-spacing: -0.2px;
        }

        .btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }

        .btn-danger {
            background: #e74c3c;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }

        .btn-danger:hover {
            background: #c0392b;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.4);
        }

        .btn-success {
            background: #27ae60;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }

        .btn-success:hover {
            background: #229954;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        }

        .form-container {
            max-width: 400px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .form-container h2 {
            text-align: center;
            margin-top: 0;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            margin: 5px 0;
            border: none;
            border-radius: 25px;
            box-sizing: border-box;
            font-size: 16px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            font-weight: 400;
            background: #f8f9fa;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            letter-spacing: -0.2px;
        }

        input:focus {
            outline: none;
            background: white;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
            transform: translateY(-1px);
        }

        .cart-table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .cart-table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
        }

        .cart-summary {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
            text-align: right;
            border: 1px solid #ddd;
        }

        .success {
            color: #27ae60;
            background: #eafaf1;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .error {
            color: #e74c3c;
            background: #fdedec;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: 500;
        }

        .page {
            display: none;
        }

        .active {
            display: block;
        }

        .hidden {
            display: none !important;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .payment-form input {
            margin-bottom: 10px;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            border-radius: 8px;
            padding: 20px;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 15px; right: 15px;
            cursor: pointer;
            font-size: 24px;
            font-weight: bold;
            color: #7f8c8d;
        }
        .close-btn:hover { color: #2c3e50; }
        
        /* Star Rating */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 5px;
            font-size: 24px;
        }
        .star-rating input { display: none; }
        .star-rating label {
            color: #ccc;
            cursor: pointer;
        }
        .star-rating :checked ~ label { color: #f39c12; }
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #f39c12; }
        
        .review-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .review-item:last-child { border-bottom: none; }
        .review-stars { color: #f39c12; font-size: 16px; }
    </style>
</head>
<body>

    <nav>
        <a href="#" class="logo" onclick="navigateTo('home')">
            <img src="Logo.png" alt="BookNest Logo">
            <span class="brand-text">Book<span>Nest</span></span>
        </a>
        <div class="nav-links">
            <a onclick="navigateTo('home')">Home</a>
            <a onclick="navigateTo('catalog')">Books</a>
            <span id="guest-nav">
                <a onclick="navigateTo('login')">Login</a>
                <a onclick="navigateTo('register')">Register</a>
            </span>
            <span id="user-nav" class="hidden">
                <a onclick="navigateTo('profile')">Profile</a>
                <a onclick="navigateTo('orders')">Orders</a>
                <a onclick="logout()">Logout</a>
            </span>
            <a onclick="navigateTo('cart')">Cart (<span id="cart-count">0</span>)</a>
        </div>
    </nav>

    <div class="container">
        <!-- Home Page -->
        <div id="page-home" class="page active">
            <div class="hero">
                <h1>Welcome to BookNest</h1>
                <p>Your one-stop shop for all your reading needs. Simple, reliable, and academic.</p>
                <br>
                <button class="btn" onclick="navigateTo('catalog')">Browse Collection</button>
            </div>
            <h2>New Arrivals</h2>
            <br>
            <div id="home-books" class="book-grid">
                <!-- Books will be loaded here via JS -->
            </div>
        </div>

        <!-- Catalog Page -->
        <div id="page-catalog" class="page">
            <h2>Our Complete Catalog</h2>
            <br>
            <div id="catalog-books" class="book-grid">
                <!-- All books will be loaded here -->
            </div>
        </div>

        <!-- Login Page -->
        <div id="page-login" class="page">
            <div class="form-container">
                <h2>Login</h2>
                <div id="login-msg"></div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="login-email" placeholder="example@mail.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="login-password" placeholder="********">
                </div>
                <button class="btn" style="width: 100%" onclick="login()">Login</button>
                <p style="margin-top: 1rem; font-size: 0.8rem; text-align: center;">New here? <a onclick="navigateTo('register')">Register now</a></p>
            </div>
        </div>

        <!-- Register Page -->
        <div id="page-register" class="page">
            <div class="form-container">
                <h2>Create Account</h2>
                <div id="register-msg"></div>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="reg-name" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="reg-email" placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="reg-password" placeholder="Min 6 characters">
                </div>
                <button class="btn" style="width: 100%" onclick="register()">Sign Up</button>
            </div>
        </div>

        <!-- Cart Page -->
        <div id="page-cart" class="page">
            <h2>Your Shopping Cart</h2>
            <div id="cart-content">
                <!-- Cart items table will be here -->
            </div>
        </div>

        <!-- Profile Page -->
        <div id="page-profile" class="page">
            <div class="form-container">
                <h2>My Profile</h2>
                <div id="profile-content">
                    <!-- Profile info will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Orders Page -->
        <div id="page-orders" class="page">
            <h2>My Order History</h2>
            <div id="orders-content">
                <!-- Orders will be loaded here -->
            </div>
        </div>

        <!-- Payment Page -->
        <div id="page-payment" class="page">
            <div class="form-container" style="max-width: 500px;">
                <h2>Complete Payment</h2>
                <div id="payment-info" style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <!-- Order details will be shown here -->
                </div>
                
                <div style="margin-bottom: 20px;">
                    <h3 style="margin-bottom: 15px;">Choose Payment Method</h3>
                    <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                        <button class="btn" id="card-tab" onclick="switchPaymentMethod('card')" style="flex: 1;">💳 Card</button>
                        <button class="btn" id="upi-tab" onclick="switchPaymentMethod('upi')" style="flex: 1; background: #6c757d;">📱 UPI</button>
                    </div>
                </div>

                <!-- Card Payment Form -->
                <div id="card-form" class="payment-form">
                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" id="card-number" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Expiry Date</label>
                            <input type="text" id="card-expiry" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>CVV</label>
                            <input type="text" id="card-cvv" placeholder="123" maxlength="4">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Cardholder Name</label>
                        <input type="text" id="card-name" placeholder="John Doe">
                    </div>
                </div>

                <!-- UPI Payment Form -->
                <div id="upi-form" class="payment-form" style="display: none;">
                    <div class="form-group">
                        <label>UPI ID</label>
                        <input type="text" id="upi-id" placeholder="yourname@paytm">
                    </div>
                    <div style="background: #e3f2fd; padding: 15px; border-radius: 10px; margin: 15px 0; font-size: 14px;">
                        <strong>Popular UPI Apps:</strong><br>
                        • Google Pay: @okaxis, @okhdfcbank<br>
                        • PhonePe: @ybl<br>
                        • Paytm: @paytm<br>
                        • BHIM: @upi
                    </div>
                </div>

                <button class="btn btn-success" onclick="processPayment()" style="width: 100%; margin-top: 20px; padding: 15px;">
                    💰 Pay Now
                </button>
                
                <div id="payment-processing" style="display: none; text-align: center; margin-top: 20px;">
                    <div style="display: inline-block; width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                    <p>Processing payment...</p>
                </div>
            </div>
        </div>

        <!-- Book Details Modal -->
        <div id="book-modal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-btn" onclick="closeBookModal()">&times;</span>
                <div id="modal-book-info"></div>
                
                <hr style="border:0; border-top:1px solid #ddd; margin: 20px 0;">
                
                <h3>Reviews & Ratings</h3>
                <div id="reviews-list"></div>
                
                <div id="add-review-section" style="margin-top: 20px; background: #f8f9fa; padding: 15px; border-radius: 8px; display: none;">
                    <h4>Write a Review</h4>
                    <div id="review-msg"></div>
                    <form id="review-form" onsubmit="submitReview(event)">
                        <input type="hidden" id="review-book-id">
                        <div class="star-rating" style="margin-bottom: 10px;">
                            <input type="radio" id="star5" name="rating" value="5"><label for="star5">★</label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
                        </div>
                        <textarea id="review-comment" rows="3" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;" placeholder="Write your comment here..."></textarea>
                        <button type="submit" class="btn" style="margin-top: 10px;">Submit Review</button>
                    </form>
                </div>
                <div id="login-to-review" style="margin-top: 20px; text-align: center; color: #7f8c8d;">
                    <a onclick="closeBookModal(); navigateTo('login')" style="color: #3498db; cursor: pointer;">Login</a> to write a review.
                </div>
            </div>
        </div>
    </div>

    <!-- Frontend logic to handle dynamic data from PHP Backend -->
    <script>
        const API_URL = 'api/';

        // Simple Router
        function navigateTo(pageId) {
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.getElementById('page-' + pageId).classList.add('active');
            
            if (pageId === 'home') loadBooks('home-books', 4);
            if (pageId === 'catalog') loadBooks('catalog-books');
            if (pageId === 'cart') loadCart();
            if (pageId === 'profile') loadProfile();
            if (pageId === 'orders') loadOrders();
            if (pageId === 'payment') loadPaymentPage();
        }

        // Fetch Books from PHP API
        async function loadBooks(containerId, limit = null) {
            const container = document.getElementById(containerId);
            container.innerHTML = '<p>Loading books...</p>';
            
            try {
                const response = await fetch('api/books.php');
                const result = await response.json();
                
                console.log('Books API response:', result); // Debug log
                
                if (result.success) {
                    let books = result.data;
                    if (limit) books = books.slice(0, limit);
                    
                    if (books.length === 0) {
                        container.innerHTML = '<p>No books available.</p>';
                        return;
                    }

                    container.innerHTML = books.map(book => `
                        <div class="book-card">
                            ${book.image_url ? 
                                `<img src="${book.image_url}" alt="${book.title}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">` : 
                                ''
                            }
                            <div class="cover" style="background-color: ${book.cover_color || '#34495e'}; ${book.image_url ? 'display: none;' : ''}">${book.title.charAt(0)}</div>
                            <h3>${book.title}</h3>
                            <p>by ${book.author}</p>
                            <div class="price">₹${book.price}</div>
                            <button class="btn" style="width: 100%; margin-bottom: 5px;" onclick="addToCart(${book.id})" type="button">Add to Cart</button>
                            <button class="btn btn-success" style="width: 100%;" onclick="showBookDetails(${book.id})" type="button">Reviews & Details</button>
                        </div>
                    `).join('');
                    
                    console.log('Books loaded successfully:', books.length); // Debug log
                } else {
                    container.innerHTML = '<p class="error">Error: ' + result.message + '</p>';
                }
            } catch (err) {
                console.error('Books loading error:', err); // Debug log
                container.innerHTML = '<p class="error">Error loading books. Please check console for details.</p>';
            }
        }

        // Cart Logic - Clean and Simple
        async function addToCart(bookId) {
            console.log('Adding book to cart:', bookId);
            
            try {
                const response = await fetch('api/cart.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ book_id: parseInt(bookId), qty: 1 })
                });
                
                console.log('Response status:', response.status);
                
                const result = await response.json();
                console.log('Add to cart response:', result);
                
                if (result.success) {
                    showNotification(result.message, 'success');
                    await updateCartCount();
                } else {
                    showNotification('Error: ' + result.message, 'error');
                }
            } catch (err) {
                console.error('Cart error:', err);
                showNotification('Error adding to cart. Please try again.', 'error');
            }
        }
        
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 1000;
                font-weight: 500;
                max-width: 300px;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            
            if (!document.getElementById('notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.3s ease reverse';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        async function updateCartCount() {
            try {
                const response = await fetch('api/cart.php');
                const result = await response.json();
                console.log('Cart count response:', result);
                
                if (result.success) {
                    const count = result.data.count || 0;
                    document.getElementById('cart-count').textContent = count;
                    console.log('Cart count updated:', count);
                } else {
                    console.error('Cart count error:', result.message);
                    document.getElementById('cart-count').textContent = '0';
                }
            } catch (err) {
                console.error('Error updating cart count:', err);
                document.getElementById('cart-count').textContent = '0';
            }
        }

        async function loadCart() {
            const container = document.getElementById('cart-content');
            container.innerHTML = '<p>Loading cart...</p>';
            
            try {
                const response = await fetch('api/cart.php');
                const result = await response.json();
                
                if (!result.success) {
                    container.innerHTML = '<p class="error">Error loading cart: ' + result.message + '</p>';
                    return;
                }
                
                const data = result.data;
                
                if (!data.items || data.items.length === 0) {
                    container.innerHTML = '<div style="text-align: center; padding: 40px;"><p>Your cart is empty</p><button class="btn" onclick="navigateTo(\'catalog\')">Start Shopping</button></div>';
                    return;
                }

                let html = `
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.items.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.title}<br><small>by ${item.author}</small></td>
                            <td>₹${item.price}</td>
                            <td>${item.qty}</td>
                            <td>₹${(item.price * item.qty).toFixed(2)}</td>
                            <td><button class="btn btn-danger" onclick="removeFromCart(${item.id})">Remove</button></td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>
                    </table>
                    <div class="cart-summary">
                        <p><strong>Subtotal: ₹${data.subtotal}</strong></p>
                        <p>Shipping: ₹${data.shipping}</p>
                        <h3>Total: ₹${data.total}</h3>
                        <button class="btn btn-success" onclick="placeOrder()" style="margin-top: 15px;">Place Order</button>
                    </div>
                `;
                
                container.innerHTML = html;
            } catch (err) {
                console.error('Error loading cart:', err);
                container.innerHTML = '<p class="error">Error loading cart</p>';
            }
        }

        // Auth Logic
        async function login() {
            const email = document.getElementById('login-email').value;
            const pass = document.getElementById('login-password').value;
            const msg = document.getElementById('login-msg');

            const response = await fetch(API_URL + 'auth.php?action=login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password: pass })
            });
            const result = await response.json();
            
            if (result.success) {
                msg.innerHTML = '<p class="success">Logged in successfully!</p>';
                checkAuth();
                setTimeout(() => navigateTo('home'), 1000);
            } else {
                msg.innerHTML = `<p class="error">${result.message}</p>`;
            }
        }

        async function register() {
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const pass = document.getElementById('reg-password').value;
            const msg = document.getElementById('register-msg');

            const response = await fetch(API_URL + 'auth.php?action=register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ full_name: name, email, password: pass })
            });
            const result = await response.json();
            
            if (result.success) {
                msg.innerHTML = '<p class="success">Account created! You can now login.</p>';
                setTimeout(() => navigateTo('login'), 1500);
            } else {
                msg.innerHTML = `<p class="error">${result.message}</p>`;
            }
        }

        async function checkAuth() {
            const response = await fetch(API_URL + 'auth.php?action=me');
            const result = await response.json();
            
            if (result.success) {
                document.getElementById('guest-nav').classList.add('hidden');
                document.getElementById('user-nav').classList.remove('hidden');
            } else {
                document.getElementById('guest-nav').classList.remove('hidden');
                document.getElementById('user-nav').classList.add('hidden');
            }
        }

        async function logout() {
            await fetch(API_URL + 'auth.php?action=logout', { method: 'POST' });
            checkAuth();
            navigateTo('home');
        }
        
        // Reviews and Book Details
        async function showBookDetails(bookId) {
            document.getElementById('book-modal').style.display = 'flex';
            document.getElementById('modal-book-info').innerHTML = '<p>Loading...</p>';
            document.getElementById('reviews-list').innerHTML = '';
            
            try {
                // Fetch book details
                const bookRes = await fetch('api/books.php?id=' + bookId);
                const bookData = await bookRes.json();
                
                if (bookData.success) {
                    const book = bookData.data;
                    document.getElementById('modal-book-info').innerHTML = `
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                ${book.image_url ? `<img src="${book.image_url}" style="width: 100%; max-width: 150px; border-radius: 5px;">` : `<div style="width: 100px; height: 150px; background: ${book.cover_color || '#333'}; display: flex; align-items:center; justify-content:center; color:white; font-size: 30px;">${book.title.charAt(0)}</div>`}
                            </div>
                            <div style="flex: 3;">
                                <h2 style="margin-top: 0;">${book.title}</h2>
                                <p><strong>Author:</strong> ${book.author}</p>
                                <p><strong>Genre:</strong> ${book.genre}</p>
                                <p><strong>Price:</strong> <span style="color: #e67e22; font-weight: bold;">₹${book.price}</span></p>
                                <p>${book.description || 'No description available.'}</p>
                                <button class="btn" onclick="addToCart(${book.id})">Add to Cart</button>
                            </div>
                        </div>
                    `;
                    document.getElementById('review-book-id').value = book.id;
                    
                    // Fetch reviews
                    loadReviews(bookId);
                    
                    // Check auth for review form
                    const authRes = await fetch('api/auth.php?action=me');
                    const authData = await authRes.json();
                    
                    if (authData.success) {
                        document.getElementById('add-review-section').style.display = 'block';
                        document.getElementById('login-to-review').style.display = 'none';
                    } else {
                        document.getElementById('add-review-section').style.display = 'none';
                        document.getElementById('login-to-review').style.display = 'block';
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }
        
        function closeBookModal() {
            document.getElementById('book-modal').style.display = 'none';
        }
        
        async function loadReviews(bookId) {
            const container = document.getElementById('reviews-list');
            container.innerHTML = '<p>Loading reviews...</p>';
            
            try {
                const res = await fetch('api/reviews.php?book_id=' + bookId);
                const data = await res.json();
                
                if (data.success) {
                    if (data.data.length === 0) {
                        container.innerHTML = '<p style="color:#7f8c8d;">No reviews yet. Be the first to review!</p>';
                        return;
                    }
                    
                    let html = '';
                    data.data.forEach(r => {
                        const stars = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
                        html += `
                            <div class="review-item">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <strong>${r.user_name}</strong>
                                    <span class="review-stars">${stars}</span>
                                </div>
                                <p style="margin: 0; color: #555;">${r.comment || ''}</p>
                                <small style="color: #999;">${new Date(r.date).toLocaleDateString()}</small>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                }
            } catch (err) {
                container.innerHTML = '<p class="error">Failed to load reviews.</p>';
            }
        }
        
        async function submitReview(e) {
            e.preventDefault();
            const bookId = document.getElementById('review-book-id').value;
            const ratingEl = document.querySelector('input[name="rating"]:checked');
            const comment = document.getElementById('review-comment').value;
            const msg = document.getElementById('review-msg');
            
            if (!ratingEl) {
                msg.innerHTML = '<p class="error" style="padding: 5px; margin: 0 0 10px 0;">Please select a star rating.</p>';
                return;
            }
            
            try {
                const res = await fetch('api/reviews.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ book_id: parseInt(bookId), rating: parseInt(ratingEl.value), comment })
                });
                const data = await res.json();
                
                if (data.success) {
                    msg.innerHTML = '<p class="success" style="padding: 5px; margin: 0 0 10px 0;">Review added successfully!</p>';
                    document.getElementById('review-form').reset();
                    loadReviews(bookId);
                    setTimeout(() => { msg.innerHTML = ''; }, 3000);
                } else {
                    msg.innerHTML = '<p class="error" style="padding: 5px; margin: 0 0 10px 0;">' + data.message + '</p>';
                }
            } catch (err) {
                msg.innerHTML = '<p class="error" style="padding: 5px; margin: 0 0 10px 0;">Failed to submit review.</p>';
            }
        }
    // Profile Logic
        async function loadProfile() {
            const container = document.getElementById('profile-content');
            const response = await fetch(API_URL + 'auth.php?action=me');
            const result = await response.json();
            
            if (result.success && result.data) {
                const user = result.data;
                container.innerHTML = `
                    <div style="text-align: center; margin-bottom: 30px;">
                        <div style="width: 80px; height: 80px; background: #3498db; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: bold;">
                            ${user.full_name.charAt(0).toUpperCase()}
                        </div>
                        <h3 style="margin: 0; color: #2c3e50;">${user.full_name}</h3>
                        <p style="color: #7f8c8d; margin: 5px 0;">${user.email}</p>
                        <p style="color: #95a5a6; font-size: 14px;">Member since ${new Date(user.created_at).toLocaleDateString()}</p>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 15px; margin-bottom: 20px;">
                        <h4 style="margin-top: 0; color: #2c3e50;">Account Information</h4>
                        <p><strong>Full Name:</strong> ${user.full_name}</p>
                        <p><strong>Email:</strong> ${user.email}</p>
                        <p><strong>Account Type:</strong> ${user.role || 'Customer'}</p>
                        <p><strong>Status:</strong> <span style="color: #27ae60;">Active</span></p>
                    </div>
                    
                    <div style="text-align: center;">
                        <button class="btn" onclick="navigateTo('cart')" style="margin-right: 10px;">View Cart</button>
                        <button class="btn btn-success" onclick="navigateTo('orders')">Order History</button>
                    </div>
                `;
            } else {
                container.innerHTML = '<p class="error">Please login to view your profile.</p>';
            }
        }

        async function loadOrders() {
            const container = document.getElementById('orders-content');
            container.innerHTML = '<p>Loading orders...</p>';
            
            try {
                const response = await fetch(API_URL + 'orders.php');
                const result = await response.json();
                
                console.log('Orders API response:', result); // Debug log
                
                if (!result.success) {
                    if (response.status === 401) {
                        container.innerHTML = '<div style="background: white; padding: 40px; text-align: center; border-radius: 15px;"><p class="error">Please login to view your order history.</p><button class="btn" onclick="navigateTo(\'login\')">Login</button></div>';
                        return;
                    }
                    container.innerHTML = `<p class="error">${result.message}</p>`;
                    return;
                }
                
                if (result.data && result.data.length > 0) {
                    let html = '';
                    result.data.forEach(order => {
                        const paymentStatusColor = order.payment_status === 'completed' ? '#27ae60' : 
                                          order.payment_status === 'failed' ? '#e74c3c' : '#f39c12';
                        const paymentStatusText = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1);
                        
                        const orderStatusColor = order.status === 'delivered' ? '#27ae60' :
                                                 order.status === 'shipped' ? '#8e44ad' :
                                                 order.status === 'confirmed' ? '#3498db' :
                                                 order.status === 'cancelled' ? '#e74c3c' : '#f39c12';
                        const orderStatusText = order.status.charAt(0).toUpperCase() + order.status.slice(1);
                        
                        html += `
                            <div style="background: white; padding: 20px; margin-bottom: 20px; border-radius: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <h3 style="margin: 0; color: #2c3e50; font-weight: 600;">Order #${order.id}</h3>
                                    <div>
                                        <span style="background: ${paymentStatusColor}; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 500; margin-right: 10px;">Payment: ${paymentStatusText}</span>
                                        <span style="background: ${orderStatusColor}; color: white; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 500;">Order: ${orderStatusText}</span>
                                    </div>
                                </div>
                                <p style="color: #7f8c8d; margin: 5px 0;">Placed on: ${new Date(order.placed_at).toLocaleDateString()}</p>
                                ${order.paid_at ? `<p style="color: #27ae60; margin: 5px 0;">Paid on: ${new Date(order.paid_at).toLocaleDateString()}</p>` : ''}
                                ${order.payment_method ? `<p style="color: #7f8c8d; margin: 5px 0;">Payment Method: ${order.payment_method.toUpperCase()}</p>` : ''}
                                ${order.payment_id ? `<p style="color: #7f8c8d; margin: 5px 0; font-family: monospace;">Payment ID: ${order.payment_id}</p>` : ''}
                                <p style="color: #2c3e50; font-weight: 600; font-size: 18px; margin: 10px 0;">Total: ₹${order.total_amount}</p>
                                
                                <div style="margin-top: 15px;">
                                    <h4 style="color: #2c3e50; font-weight: 600; margin-bottom: 10px;">Items:</h4>
                                    ${order.items && order.items.length > 0 ? order.items.map(item => `
                                        <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee;">
                                            <span>${item.title}</span>
                                            <span>Qty: ${item.qty} × ₹${item.price} = ₹${item.qty * item.price}</span>
                                        </div>
                                    `).join('') : '<p>No items found</p>'}
                                </div>
                                
                                ${(order.payment_status === 'pending' || order.payment_status === 'failed') ? `
                                    <div style="margin-top: 15px; text-align: center;">
                                        <button class="btn btn-success" onclick="currentOrderId=${order.id}; navigateTo('payment');">Complete Payment</button>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div style="background: white; padding: 40px; text-align: center; border-radius: 15px;"><p>No orders found. Start shopping to see your order history!</p><button class="btn" onclick="navigateTo(\'catalog\')">Browse Books</button></div>';
                }
            } catch (err) {
                console.error('Orders loading error:', err); // Debug log
                container.innerHTML = '<p class="error">Error loading orders. Please try again.</p>';
            }
        }

        async function removeFromCart(bookId) {
            try {
                const response = await fetch('api/cart.php?action=remove', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ book_id: bookId })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    await loadCart();
                    await updateCartCount();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (err) {
                console.error('Remove from cart error:', err);
                alert('Error removing item. Please try again.');
            }
        }

        let currentOrderId = null;
        let currentPaymentMethod = 'card';

        // Payment System
        function switchPaymentMethod(method) {
            currentPaymentMethod = method;
            
            // Update tab styles
            document.getElementById('card-tab').style.background = method === 'card' ? '#3498db' : '#6c757d';
            document.getElementById('upi-tab').style.background = method === 'upi' ? '#3498db' : '#6c757d';
            
            // Show/hide forms
            document.getElementById('card-form').style.display = method === 'card' ? 'block' : 'none';
            document.getElementById('upi-form').style.display = method === 'upi' ? 'block' : 'none';
        }

        function loadPaymentPage() {
            if (!currentOrderId) {
                document.getElementById('payment-info').innerHTML = '<p class="error">No order found for payment.</p>';
                return;
            }
            
            fetch('api/orders.php?id=' + currentOrderId)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const order = result.data;
                        document.getElementById('payment-info').innerHTML = `
                            <h4 style="margin-top: 0; color: #2c3e50;">Order Summary</h4>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Order #${order.id}</span>
                                <span style="font-weight: 600;">₹${order.total_amount}</span>
                            </div>
                            <div style="font-size: 14px; color: #666;">
                                <p style="margin: 5px 0;">${order.items.length} item(s)</p>
                                <p style="margin: 5px 0;">Status: ${order.status}</p>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error('Error loading order:', err);
                });
        }

        async function processPayment() {
            const processingDiv = document.getElementById('payment-processing');
            const payButton = document.querySelector('button[onclick="processPayment()"]');
            
            processingDiv.style.display = 'block';
            payButton.disabled = true;
            payButton.textContent = 'Processing...';
            
            let paymentData = {};
            let isValid = true;
            
            if (currentPaymentMethod === 'card') {
                const cardNumber = document.getElementById('card-number').value.replace(/\s/g, '');
                const expiry = document.getElementById('card-expiry').value;
                const cvv = document.getElementById('card-cvv').value;
                const name = document.getElementById('card-name').value;
                
                if (!cardNumber || cardNumber.length < 16) {
                    showPaymentError('Please enter a valid 16-digit card number');
                    isValid = false;
                } else if (!expiry || !expiry.match(/^\d{2}\/\d{2}$/)) {
                    showPaymentError('Please enter expiry date in MM/YY format');
                    isValid = false;
                } else if (!cvv || cvv.length < 3) {
                    showPaymentError('Please enter a valid CVV');
                    isValid = false;
                } else if (!name || name.trim().length < 2) {
                    showPaymentError('Please enter cardholder name');
                    isValid = false;
                }
                
                paymentData = { card_number: cardNumber, expiry, cvv, name };
            } else if (currentPaymentMethod === 'upi') {
                const upiId = document.getElementById('upi-id').value;
                
                if (!upiId || !upiId.includes('@')) {
                    showPaymentError('Please enter a valid UPI ID');
                    isValid = false;
                }
                
                paymentData = { upi_id: upiId };
            } else if (currentPaymentMethod === 'cod') {
                paymentData = { cod: true };
            }
            
            if (!isValid) {
                processingDiv.style.display = 'none';
                payButton.disabled = false;
                payButton.textContent = '💰 Pay Now';
                return;
            }
            
            try {
                if (!currentOrderId) {
                    showPaymentError('No active order found. Please go back to your cart and try again.');
                    return;
                }

                const response = await fetch('api/payment.php?action=process', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        order_id: currentOrderId,
                        payment_method: currentPaymentMethod,
                        payment_data: paymentData
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Hide spinner before loading receipt UI
                    processingDiv.style.display = 'none';
                    await showPaymentReceipt(result.data);
                } else {
                    showPaymentError('Payment Failed: ' + result.message);
                }
            } catch (err) {
                console.error('Payment error:', err);
                showPaymentError('Payment processing error. Please try again.');
            } finally {
                processingDiv.style.display = 'none';
                payButton.disabled = false;
                payButton.textContent = '💰 Pay Now';
            }
        }
        
        function showPaymentError(message) {
            const errorDiv = document.getElementById('payment-error') || createPaymentErrorDiv();
            errorDiv.innerHTML = `<p style="color: #e74c3c; background: #fdedec; padding: 10px; border-radius: 5px; margin: 10px 0;">${message}</p>`;
            setTimeout(() => {
                if (errorDiv.parentNode) {
                    errorDiv.innerHTML = '';
                }
            }, 5000);
        }
        
        function createPaymentErrorDiv() {
            const errorDiv = document.createElement('div');
            errorDiv.id = 'payment-error';
            const processingDiv = document.getElementById('payment-processing');
            if (processingDiv && processingDiv.parentNode) {
                processingDiv.parentNode.insertBefore(errorDiv, processingDiv);
            } else {
                // Fallback to appending to body or another container if processingDiv is not found
                document.querySelector('.form-container').appendChild(errorDiv);
            }
            return errorDiv;
        }
        
        async function showPaymentReceipt(paymentData) {
            const orderId = paymentData.order_id || currentOrderId;
            if (!orderId) {
                console.error('No order ID available for receipt');
                showPaymentError('Payment successful, but order details are missing. Please check Order History.');
                return;
            }

            try {
                console.log('Fetching receipt for order:', orderId);
                const response = await fetch('api/orders.php?id=' + orderId);
                const text = await response.text();
                
                let result;
                try {
                    result = JSON.parse(text);
                } catch (e) {
                    console.error('Server returned non-JSON response:', text);
                    throw new Error('The server returned an invalid response format.');
                }
                
                if (result.success) {
                    const order = result.data;
                    if (!order || !order.items) {
                        throw new Error('Order details are incomplete.');
                    }
                    
                    showNotification('Payment processed successfully!', 'success');
                    displayReceipt(order, paymentData);
                } else {
                    console.error('API Error:', result.message);
                    showPaymentError('Payment confirmed, but details could not be loaded: ' + result.message);
                }
            } catch (err) {
                console.error('Receipt loading error:', err);
                showPaymentError('Payment successful! (ID: ' + (paymentData.payment_id || 'N/A') + ') but receipt failed to load. Please check My Orders.');
            }
        }
        
        function displayReceipt(order, paymentData) {
            const receiptHtml = `
                <div style="max-width: 500px; margin: 20px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                    <div style="text-align: center; color: #27ae60; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                        <div style="font-size: 64px; margin-bottom: 10px;">✓</div>
                        <h2 style="margin: 0; color: #27ae60;">Payment Successful!</h2>
                        <p style="margin: 5px 0; color: #666;">Thank you for your purchase</p>
                    </div>

                    <div style="text-align: center; margin-bottom: 30px;">
                        <h3 style="color: #2c3e50; margin: 0; font-size: 24px;">📚 BookNest</h3>
                        <p style="color: #666; margin: 5px 0;">Official Payment Receipt</p>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Receipt ID:</span>
                            <span style="font-weight: bold; font-family: monospace;">${paymentData.payment_id}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Order ID:</span>
                            <span style="font-weight: bold;">#${order.id}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Date:</span>
                            <span>${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Payment Method:</span>
                            <span style="text-transform: uppercase; font-weight: 500;">${paymentData.method}</span>
                        </div>
                        ${paymentData.method === 'card' ? `
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span style="color: #666;">Card:</span>
                            <span>****-****-****-${paymentData.card_last4}</span>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; margin-bottom: 20px;">
                        <h4 style="margin: 0 0 15px 0; color: #2c3e50;">Items Purchased:</h4>
                        ${order.items.map(item => `
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span>${item.title} x ${item.qty}</span>
                                <span style="font-family: monospace;">₹${(item.qty * item.price).toFixed(2)}</span>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div style="margin-bottom: 30px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>Subtotal:</span>
                            <span style="font-family: monospace;">₹${(parseFloat(order.total_amount) - (parseFloat(order.shipping) || 0)).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                            <span>Shipping:</span>
                            <span style="font-family: monospace;">₹${(parseFloat(order.shipping) || 0).toFixed(2)}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 20px; color: #27ae60; border-top: 2px solid #eee; padding-top: 15px; margin-top: 10px;">
                            <span>Total Paid:</span>
                            <span style="font-family: monospace;">₹${parseFloat(order.total_amount).toFixed(2)}</span>
                        </div>
                    </div>
                    
                    <div style="text-align: center; gap: 15px; display: flex; justify-content: center;">
                        <button class="btn" onclick="printReceipt()" style="flex: 1;">🖨️ Print Receipt</button>
                        <button class="btn btn-success" onclick="navigateTo('orders'); updateCartCount();" style="flex: 1;">📂 View Orders</button>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999;">
                        <p>BookNest - Your Online Bookstore</p>
                        <p>Thank you for choosing us!</p>
                    </div>
                </div>
            `;
            
            document.getElementById('page-payment').innerHTML = receiptHtml;
        }
        
        function printReceipt() {
            window.print();
        }

        // Format card number input
        document.addEventListener('DOMContentLoaded', function() {
            const cardNumberInput = document.getElementById('card-number');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
                    let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
                    if (formattedValue.length <= 19) {
                        e.target.value = formattedValue;
                    }
                });
            }
            
            const expiryInput = document.getElementById('card-expiry');
            if (expiryInput) {
                expiryInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }
        });

        async function placeOrder() {
            if (!confirm('Proceed to payment?')) return;
            
            try {
                const response = await fetch('api/orders.php?action=place', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                });
                const result = await response.json();
                
                console.log('Place order response:', result);
                
                if (result.success) {
                    currentOrderId = result.data.order_id;
                    navigateTo('payment');
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (err) {
                console.error('Place order error:', err);
                alert('Error placing order. Please try again.');
            }
        }

        // Initialize
        window.onload = () => {
            loadBooks('home-books', 4);
            updateCartCount();
            checkAuth();
        };
    </script>
</body>
</html>