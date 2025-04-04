<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Redirect if the cart is empty
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "<script>alert('Your cart is empty!'); window.location='user_dashboard.php';</script>";
    exit();
}

// Fetch user details
$user_email = $_SESSION["user"];
$user_query = $conn->prepare("SELECT * FROM users WHERE email = ?");
$user_query->bind_param("s", $user_email);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

$fullname = $user["fullname"] ?? "";
$address = $user["address"] ?? "";
$phone = $user["phone"] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["place_order"])) {
    $fullname = trim($_POST["fullname"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    $payment_method = $_POST["payment_method"];

    if (empty($fullname) || empty($address) || empty($phone) || empty($payment_method)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        $total_amount = 0;
        $order_items = [];

        foreach ($_SESSION["cart"] as $id => $item) {
            $price = ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                        ? $item["offer_price"] 
                        : $item["original_price"];
            $subtotal = $price * $item["quantity"];
            $total_amount += $subtotal;
            $order_items[] = "{$item["name"]} (x{$item["quantity"]}) - \${$subtotal}";
        }

        $order_items_str = implode(", ", $order_items);
        $order_date = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("INSERT INTO orders (user_email, fullname, address, phone, items, total_amount, payment_method, order_date) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdss", $user_email, $fullname, $address, $phone, $order_items_str, $total_amount, $payment_method, $order_date);
        $stmt->execute();

        // Clear cart after order placement
        unset($_SESSION["cart"]);

        echo "<script>alert('Order placed successfully!'); window.location='user_dashboard.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    
    <style>
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: #000 !important;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .content-container {
            flex: 1;
            margin-top: 100px;
            padding: 20px;
        }

        .transparent-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .form-control, .btn {
            border-radius: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px; /* Space above footer */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            Plaza Shopping
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Checkout</h2>

            <h3 class="text-dark">Order Summary</h3>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Original Price</th>
                        <th>Offer Price</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION["cart"] as $id => $item): ?>
                        <?php 
                            $price = ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                                        ? $item["offer_price"] 
                                        : $item["original_price"];
                            $subtotal = $price * $item["quantity"];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item["name"]); ?></td>
                            <td><?= $item["quantity"]; ?></td>
                            <td>$<?= $item["original_price"]; ?></td>
                            <td><?= $item["offer_price"] !== "N/A" ? "$" . $item["offer_price"] : "-"; ?></td>
                            <td>
                                <?= ($item["offer_price"] !== "N/A" && $item["offer_price"] < $item["original_price"]) 
                                    ? round((($item["original_price"] - $item["offer_price"]) / $item["original_price"]) * 100, 2) . "%" 
                                    : "-"; ?>
                            </td>
                            <td>$<?= number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5"><strong>Total:</strong></td>
                        <td><strong>$<?= number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <h3 class="text-dark">Billing Details</h3>
            <form method="POST">
                <div class="mb-3">
                    <label>Full Name:</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($fullname); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Address:</label>
                    <textarea name="address" class="form-control" required><?= htmlspecialchars($address); ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Phone:</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone); ?>" required>
                </div>

                <div class="mb-3">
                    <label>Payment Method:</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" name="place_order" class="btn btn-primary w-100">Place Order</button>
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
</body>
</html>
