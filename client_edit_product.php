<?php
session_start();
include("config.php");

if (!isset($_SESSION["client"])) {
    header("Location: client_login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $category = $_POST["category"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $offer_price = !empty($_POST["offer_price"]) ? $_POST["offer_price"] : "NULL"; // Handle empty value
    $quantity = $_POST["quantity"];
    
    // Image upload handling
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        $query = "UPDATE products 
                  SET name='$name', 
                      category='$category', 
                      image='$image', 
                      description='$description', 
                      price='$price', 
                      offer_price=$offer_price, 
                      quantity='$quantity' 
                  WHERE id=$id";
    } else {
        $query = "UPDATE products 
                  SET name='$name', 
                      category='$category', 
                      description='$description', 
                      price='$price', 
                      offer_price=$offer_price, 
                      quantity='$quantity' 
                  WHERE id=$id";
    }

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Product updated successfully!'); window.location='client_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Full-height page layout */
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: url('assets/back.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        /* Plaza Mall Title with Logo */
        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 15px;
        }

        /* Page Content Wrapper */
        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        /* Transparent Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 25px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Footer Sticks to Bottom */
        footer {
            background: rgba(0, 0, 0, 0.85);
            color: white;
            text-align: center;
            padding: 15px;
            width: 100%;
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
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="client_login.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="content-wrapper">
    <div class="form-container">
        <h2 class="text-center mb-4" style="font-weight: bold;">Edit Product</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $product['id']; ?>">

            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" class="form-control" name="name" value="<?= $product['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" class="form-control" name="category" value="<?= $product['category']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="uploads/<?= $product['image']; ?>" width="100"><br>
                <label class="form-label">Upload New Image (Optional)</label>
                <input type="file" class="form-control" name="image">
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" required><?= $product['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Price ($)</label>
                <input type="number" class="form-control" name="price" value="<?= $product['price']; ?>" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Price ($) <span class="text-muted">(Optional)</span></label>
                <input type="number" class="form-control" name="offer_price" value="<?= $product['offer_price']; ?>" step="0.01">
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" value="<?= $product['quantity']; ?>" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Plaza Shopping Mall Admin Panel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
