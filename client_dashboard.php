<?php
session_start();
include("config.php");

// Check if the client is logged in
if (!isset($_SESSION["client"])) {
    header("Location: client_login.php");
    exit();
}

$client_id = $_SESSION["client_id"];

// Fetch the client's products from the database
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWA Shopping Mall - Client Dashboard</title>
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
        <a class="navbar-brand" href="client_dashboard.php">
            <img src="uploads/logo.png" alt="PWA Logo">
            Plaza Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="client_add_product.php">Add Product</a></li>
                <li class="nav-item"><a class="nav-link" href="client_manage_orders.php">Manage Orders</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Dashboard Container -->
<div class="container">
    <div class="dashboard-container">
        <h2 class="text-center mb-4" style="font-weight: bold; font-size: 30px;">
            Welcome, <?= isset($_SESSION["client_name"]) ? htmlspecialchars($_SESSION["client_name"]) : 'Client'; ?>!
        </h2>

        <h4 class="mb-3">Your Products</h4>

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
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td><img src="uploads/<?= htmlspecialchars($row["image"]); ?>" width="50"></td>
                                <td><?= htmlspecialchars($row["name"]); ?></td>
                                <td><?= htmlspecialchars($row["category"]); ?></td>
                                <td><?= htmlspecialchars($row["description"]); ?></td>
                                <td>$<?= htmlspecialchars($row["price"]); ?></td>
                                <td>
                                    <?= $row["offer_price"] ? "$" . htmlspecialchars($row["offer_price"]) : "N/A"; ?>
                                </td>
                                <td><?= htmlspecialchars($row["quantity"]); ?></td>
                                <td>
                                    <a href="client_edit_product.php?id=<?= $row["id"]; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="client_delete_product.php?id=<?= $row["id"]; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">You have not added any products yet. <a href="client_add_product.php">Add your first product now!</a></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 PWA Shopping Mall Client Panel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
