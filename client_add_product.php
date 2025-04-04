<?php
session_start();
include("config.php");

if (!isset($_SESSION["client"])) {
    header("Location: client_login.php");
    exit();
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];
    $quantity = $_POST["quantity"];

    // Handling file upload
    $target_dir = "uploads/";
    $image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $query = "INSERT INTO products (name, category, image, description, price, offer_price, quantity) 
                  VALUES ('$name', '$category', '$image', '$description', '$price', '$offer_price', '$quantity')";

        if ($conn->query($query) === TRUE) {
            $success_message = "Product added successfully!";
        } else {
            $error_message = "Error adding product!";
        }
    } else {
        $error_message = "Failed to upload image!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Background Image */
        body {
            background: url('assets/background1.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Transparent Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-top: 50px;
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
                <li class="nav-item"><a class="nav-link" href="client_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Add Product Form -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 form-container">
            <h2 class="text-center mb-4" style="font-weight: bold;">Add Product</h2>

            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Enter category" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="Enter product description" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" class="form-control" placeholder="Enter price" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Offer Price ($) <small>(Optional)</small></label>
                    <input type="number" name="offer_price" class="form-control" placeholder="Enter offer price" step="0.01">
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Add Product</button>
            </form>
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
