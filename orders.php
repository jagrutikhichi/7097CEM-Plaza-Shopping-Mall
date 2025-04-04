<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION["user"];

// Fetch orders
$query = "SELECT * FROM orders WHERE user_email = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$orders = $stmt->get_result();

// Cancel order logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_order"])) {
    $order_id = $_POST["order_id"];
    
    $cancel_query = "UPDATE orders SET status = 'Canceled' WHERE id = ? AND user_email = ? AND status IN ('Pending', 'Confirmed')";
    $cancel_stmt = $conn->prepare($cancel_query);
    $cancel_stmt->bind_param("is", $order_id, $user_email);
    
    if ($cancel_stmt->execute() && $cancel_stmt->affected_rows > 0) {
        echo "<script>alert('Order has been canceled.'); window.location='orders.php';</script>";
    } else {
        echo "<script>alert('Order cancellation failed or is not allowed.'); window.location='orders.php';</script>";
    }
    $cancel_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - Plaza Shopping</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Background & Layout */
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
        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .badge-pending { background-color: gray; color: white; }
        .badge-confirmed { background-color: green; color: white; }
        .badge-delivery { background-color: orange; color: white; }
        .badge-delivered { background-color: blue; color: white; }
        .badge-canceled { background-color: red; color: white; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" height="50">
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
            <h2 class="text-center text-dark mb-4">Your Orders</h2>
            
            <?php if ($orders->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Total ($)</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><?= $order["id"]; ?></td>
                                <td><?= $order["items"]; ?></td>
                                <td>$<?= number_format($order["total_amount"], 2); ?></td>
                                <td><?= $order["payment_method"]; ?></td>
                                <td>
                                    <?php 
                                        $status_badge = [
                                            "Pending" => "badge-pending",
                                            "Confirmed" => "badge-confirmed",
                                            "Out for Delivery" => "badge-delivery",
                                            "Delivered" => "badge-delivered",
                                            "Canceled" => "badge-canceled"
                                        ];
                                        echo '<span class="badge ' . $status_badge[$order["status"]] . '">' . $order["status"] . '</span>';
                                    ?>
                                </td>
                                <td>
                                    <?php if (in_array($order["status"], ["Pending", "Confirmed"])): ?>
                                        <form method="POST">
                                            <input type="hidden" name="order_id" value="<?= $order["id"]; ?>">
                                            <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">No Action</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">You have no orders yet.</p>
            <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>