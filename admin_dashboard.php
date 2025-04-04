<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch products, events, and dining
$products = $conn->query("SELECT * FROM products");
$events = $conn->query("SELECT * FROM events");
$dining = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plaza Shopping Mall - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Background Image */
        body {
            background: url('assets/background1.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Transparent Container */
        .dashboard-container {
            background: rgba(82, 192, 243, 0.85);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Navbar Styling */
        .navbar {
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            background: rgba(0, 0, 0, 0.85) !important;
        }

        /* Mall Title */
        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            color: white !important;
            display: flex;
            align-items: center;
        }

        /* Logo */
        .navbar-brand img {
            height: 50px;
            margin-right: 15px;
        }

        /* Table Image */
        .table img {
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Footer Styling */
        footer {
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 15px;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">
            <img src="uploads/logo.png" alt="Plaza Logo">
            Plaza Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="add_product.php">Add Product</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_events.php">Manage Events</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_dining.php">Manage Dining</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_bookings.php">Manage Bookings</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Dashboard Container -->
<div class="container">
    <div class="dashboard-container">
        <h2 class="text-center mb-4" style="font-weight: bold; font-size: 30px;">Manage Products</h2>

        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Offer Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><img src="uploads/<?= $row["image"]; ?>" width="50"></td>
                            <td><?= htmlspecialchars($row["name"]); ?></td>
                            <td><?= htmlspecialchars($row["category"]); ?></td>
                            <td><?= htmlspecialchars($row["description"]); ?></td>
                            <td>$<?= htmlspecialchars($row["price"]); ?></td>
                            <td>$<?= htmlspecialchars($row["offer_price"]); ?></td>
                            <td><?= htmlspecialchars($row["quantity"]); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $row["id"]; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_product.php?id=<?= $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Plaza Shopping Mall Admin Panel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
