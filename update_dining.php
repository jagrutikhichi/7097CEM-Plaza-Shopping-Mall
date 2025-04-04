<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];
$dining = $conn->query("SELECT * FROM dining WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $location = $_POST["location"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];
    $image = $dining["image"];

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    }

    $stmt = $conn->prepare("UPDATE dining SET name=?, description=?, location=?, image=?, price=?, offer_price=? WHERE id=?");
    $stmt->bind_param("ssssddi", $name, $description, $location, $image, $price, $offer_price, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Dining updated successfully!'); window.location='manage_dining.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating dining.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Dining - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: url('assets/dining_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        .navbar-brand img {
        height: 50px; /* Adjusted height for a smaller logo */
        width: auto;  /* Maintains aspect ratio */
        margin-right: 10px;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 25px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
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
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="form-container">
        <h2 class="text-center mb-4" style="font-weight: bold;">Update Dining</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Dining Name</label>
                <input type="text" class="form-control" name="name" value="<?= $dining['name']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" required><?= $dining['description']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="location" value="<?= $dining['location']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (£)</label>
                <input type="number" class="form-control" name="price" step="0.01" value="<?= $dining['price']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Price (£) <span class="text-muted">(Optional)</span></label>
                <input type="number" class="form-control" name="offer_price" step="0.01" value="<?= $dining['offer_price']; ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <img src="<?= $dining['image']; ?>" width="100" class="mb-2">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload New Image</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Dining</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2025 Plaza Shopping Mall Admin Panel. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
