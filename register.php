<?php
include("config.php");

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $fullname = trim($_POST["fullname"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);

    if (!empty($email) && !empty($password)) {
        // Check if email already exists (using prepared statement)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error_message = "Email is already in use. Try another.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, fullname, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $hashed_password, $fullname, $address, $phone);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful! You can now log in.";
            } else {
                $error_message = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Plaza Shoppings</title>
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

        /* Logo & Branding */
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

        /* Register Box */
.register-container {
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 500px; /* Increase this value */
    text-align: center;
}


        .register-container h3 {
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
    <div class="register-container">
        <h3>Plaza Shoppings</h3>
        <p class="text-muted">Create your account</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="name" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
            </div>
            <div class="mb-3">
                <textarea class="form-control" name="address" placeholder="Address" required></textarea>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="phone" placeholder="Phone Number" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="mt-3 text-muted">
            Already have an account? <a href="login.php">Login here</a>
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
