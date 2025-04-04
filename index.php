<?php
session_start();
include("config.php");

// Secure query to fetch products
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->get_result();

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Featured Products | Mall Shopping Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        /* Body Styling */
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* Transparent Container */
        .content-container {
            flex: 1;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            margin: 30px auto;
        }

        /* Navbar */
        .navbar {
            background: #000;
            padding: 15px;
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white !important;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 12px;
        }

        .navbar-nav .nav-link {
            font-size: 18px;
            font-weight: 500;
            padding: 8px 12px;
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f8d210 !important;
        }

        /* Highlight Text */
        .highlight-text {
            text-align: center;
            background: #ffcc00;
            color: #000;
            font-size: 22px;
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Product Grid - 4 Products per Row */
        .product-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }

        /* Responsive Adjustments */
        @media (max-width: 1024px) {
            .product-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .product-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .product-container {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: 2px solid #000;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            max-height: 180px;
            border-radius: 8px;
            object-fit: cover;
        }

        .product-name {
            font-size: 22px;
            font-weight: 700;
            margin-top: 10px;
        }

        .product-price {
            font-size: 20px;
            color: #ff6600;
            font-weight: bold;
            margin-top: 5px;
        }

        .btn-add-cart {
            background: #ff6600;
            color: white;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease-in-out;
            margin-top: 10px;
        }

        .btn-add-cart:hover {
            background: #cc5500;
            color: white;
        }
        

        /* Footer */
        .footer {
            background: #000;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="uploads/logo.png" alt="Mall Logo"> Plaza Shoppings
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about_us.php">ℹ️ About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_us.php">📞 Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">🔑 Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">📝 Register</a></li>
                
            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Highlight Text -->
<div class="highlight-text">✨ Your One-Stop Shopping Destination ✨</div>

<!-- 🔹 Main Content -->
<div class="content-container">
    <div class="container">
        <h2 class="text-center mb-4">🛍️ Featured Products</h2>
        <div class="product-container">
            <?php while ($row = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="uploads/<?= htmlspecialchars($row["image"]); ?>" alt="<?= htmlspecialchars($row["name"]); ?>">
                    <div class="product-name"><?= htmlspecialchars($row["name"]); ?></div>
                    <div class="product-price">$<?= number_format($row["price"], 2); ?></div>
                    <a href="login.php" class="btn btn-add-cart">🛒 Add to Cart</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- 🔹 Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Quick Links -->
            <div class="col-md-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-light">🏠 Home</a></li>
                    <li><a href="about_us.php" class="text-light">ℹ️ About Us</a></li>
                    <li><a href="contact.php" class="text-light">📞 Contact</a></li>
                    <li><a href="privacy.php" class="text-light">🔒 Privacy Policy</a></li>
                    <li><a href="terms.php" class="text-light">📜 Terms of Service</a></li>
                </ul>
            </div>

            <!-- Social Media -->
            <div class="col-md-3">
                <h5>Follow Us</h5>
                <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-light"><i class="fab fa-linkedin fa-lg"></i></a>
            </div>

            <!-- Contact Info & Map -->
            <div class="col-md-3">
                <h5>Contact Us</h5>
                <p>📍 Plaza Shopping, Main Street, City, Country</p>
                <p>📧 support@mallshopping.com</p>
                <p>📞 +123 456 7890</p>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.04335751793!2d-74.0060152!3d40.7127761!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7b5b4b7bcbdfebc!2sMall!5e0!3m2!1sen!2sus!4v1630000000000"
                    width="100%" height="100" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <!-- Newsletter Subscription -->
            <div class="col-md-3">
                <h5>Subscribe to our Newsletter</h5>
                <form>
                    <div class="mb-2">
                        <input type="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Subscribe</button>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-4">
            <p>© 2025 Mall Shopping Portal. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
