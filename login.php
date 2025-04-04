<?php
session_start();
include("config.php");

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if user exists in 'users' table
    $query_user = "SELECT * FROM users WHERE email = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("s", $email);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows == 1) {
        $row = $result_user->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION["user"] = $email;
            header("Location: user_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid email or password!";
        }
    } else {
        // Check if user is the default admin
        if ($email === "admin1234@gmail.com" && $password === "admin1234") {
            $_SESSION["admin"] = $email;
            header("Location: admin_dashboard.php");
            exit();
        }
    
        // Check if user exists in 'admin' table
        $query_admin = "SELECT * FROM admin WHERE username = ?";
        $stmt_admin = $conn->prepare($query_admin);
        
        if ($stmt_admin) {
            $stmt_admin->bind_param("s", $email);
            $stmt_admin->execute();
            $result_admin = $stmt_admin->get_result();
    
            if ($result_admin->num_rows == 1) {
                $admin_row = $result_admin->fetch_assoc();
                
                // Verify the hashed password
                if (password_verify($password, $admin_row['password'])) {
                    $_SESSION["admin"] = $email;
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $error_message = "Invalid email or password!";
                }
            } else {
                $error_message = "Invalid email or password!";
            }
        } else {
            $error_message = "Database error. Please try again later.";
        }
    }
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plaza Shoppings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Background Image */
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        /* Fixed Navbar */
        .navbar {
            background: #000;
            padding: 15px;
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        /* App Name Styling */
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

        /* Content Container */
        .content-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 100px;
            padding-bottom: 60px;
        }

        /* Transparent Login Box */
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h3 {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 10px;
            padding: 10px;
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
                <li class="nav-item"><a class="nav-link" href="about.php">ℹ️ About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">📞 Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">🔑 Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">📝 Register</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Main Content -->
<div class="content-container">
    <div class="login-container">
        <h3>Plaza Shoppings</h3>
        <p class="text-muted">Login to continue</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email / Admin Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3 text-muted">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
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
                    <li><a href="about.php" class="text-light">ℹ️ About Us</a></li>
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

</body>
</html>
