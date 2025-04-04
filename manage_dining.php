<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all dining options
$dining_options = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Dining - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Background */
        body {
            background: url('assets/dining_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        /* Transparent Container */
        .content-wrapper {
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
        }

        /* Dining Cards */
        .dining-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .dining-card:hover {
            transform: scale(1.03);
        }

        .dining-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .dining-details {
            padding: 15px;
        }

        .dining-title {
            font-size: 18px;
            font-weight: bold;
        }

        .dining-price {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }

        .dining-location {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }

        /* Buttons */
        .btn-add {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-edit, .btn-delete {
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-edit {
            color: #007bff;
        }

        .btn-delete {
            color: #dc3545;
        }

        .btn-delete:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background: rgba(0, 0, 0, 0.85);
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
            <img src="uploads/logo.png" alt="Plaza Logo" height="50" class="me-2">
            <span class="fs-3 fw-bold">Plaza Shopping Mall</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container mt-4">
    <div class="content-wrapper">
        <h2 class="text-center mb-4">Manage Dining</h2>

        <!-- Add Dining Button -->
        <div class="mb-3 text-end">
            <a href="add_dining.php" class="btn-add">+ Add New Dining</a>
        </div>

        <!-- Dining Grid -->
        <div class="row">
            <?php while ($dining = $dining_options->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="dining-card">
                        <img src="<?= !empty($dining["image"]) && file_exists($dining["image"]) ? $dining["image"] : 'assets/no-image.png'; ?>" alt="Dining Image">
                        <div class="dining-details">
                            <div class="dining-title"><?= htmlspecialchars($dining["name"]); ?></div>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($dining["description"])); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="dining-location"><?= htmlspecialchars($dining["location"]); ?></span>
                                <span class="dining-price">£<?= number_format($dining["offer_price"] ?: $dining["price"], 2); ?></span>
                            </div>
                            <div class="text-center mt-2">
                                <a href="update_dining.php?id=<?= $dining["id"]; ?>" class="btn-edit me-3">Edit</a>
                                <a href="delete_dining.php?id=<?= $dining["id"]; ?>" class="btn-delete"
                                   onclick="return confirm('Are you sure you want to delete this dining option?')">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
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
