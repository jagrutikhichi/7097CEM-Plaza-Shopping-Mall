<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "<script>alert('Invalid dining selection!'); window.location='dineout.php';</script>";
    exit();
}

$dining_id = intval($_GET["id"]);
$stmt = $conn->prepare("SELECT * FROM dining WHERE id = ?");
$stmt->bind_param("i", $dining_id);
$stmt->execute();
$result = $stmt->get_result();
$dining = $result->fetch_assoc();

if (!$dining) {
    echo "<script>alert('Dining option not found!'); window.location='dineout.php';</script>";
    exit();
}

$final_price = (!empty($dining["offer_price"]) && $dining["offer_price"] < $dining["price"]) ? $dining["offer_price"] : $dining["price"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION["user"];
    $guest_name = $_POST["guest_name"];
    $age = $_POST["age"];
    $mobile = $_POST["mobile"];
    $dining_date = $_POST["dining_date"];
    $quantity = $_POST["quantity"];
    $total_price = $final_price * $quantity;

    $stmt = $conn->prepare("INSERT INTO booking_dining (user_email, dining_id, guest_name, age, mobile, dining_date, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssdi", $user_email, $dining_id, $guest_name, $age, $mobile, $dining_date, $quantity, $total_price);

    if ($stmt->execute()) {
        echo "<script>alert('Dining booked successfully!'); window.location='dineout.php';</script>";
    } else {
        echo "<script>alert('Failed to book dining.'); window.location='dineout.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Dining</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }
        .navbar {
            background-color: #000 !important;
            padding: 25px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .content-container {
            flex: 1;
            overflow-y: auto;
            margin-top: 80px;
            padding: 20px;
        }
        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
        }
        .transparent-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
    <a class="navbar-brand fw-bold" href="user_dashboard.php" style="font-size: 2rem;">
            Plaza Shopping
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dineout.php">Dining</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-container">
    <div class="container my-4">
        <div class="transparent-container">
            <h2 class="text-center">Book Dining - <?= htmlspecialchars($dining["name"]); ?></h2>
            <p><strong>Original Price:</strong> $<?= number_format($dining["price"], 2); ?></p>
            <?php if (!empty($dining["offer_price"]) && $dining["offer_price"] < $dining["price"]): ?>
                <p><strong>Offer Price:</strong> $<?= number_format($dining["offer_price"], 2); ?> <span class="text-success">(Discount Applied)</span></p>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="guest_name" class="form-label">Guest Name:</label>
                    <input type="text" class="form-control" name="guest_name" required>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="number" class="form-control" name="age" required min="1">
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile No:</label>
                    <input type="text" class="form-control" name="mobile" required pattern="[0-9]{10}" title="Enter a valid 10-digit number">
                </div>
                <div class="mb-3">
                    <label for="dining_date" class="form-label">Dining Date:</label>
                    <input type="date" class="form-control" name="dining_date" required min="<?= date('Y-m-d'); ?>">
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" name="quantity" required min="1" id="quantity" onchange="updateTotal()">
                </div>
                <p><strong>Total Price:</strong> $<span id="total_price"><?= number_format($final_price, 2); ?></span></p>
                <button type="submit" class="btn btn-primary">Book Now</button>
            </form>
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

<script>
    function updateTotal() {
        let pricePerMeal = <?= $final_price; ?>;
        let quantity = document.getElementById("quantity").value;
        document.getElementById("total_price").innerText = (pricePerMeal * quantity).toFixed(2);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
