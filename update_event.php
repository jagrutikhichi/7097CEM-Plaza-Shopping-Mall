<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];
$result = $conn->query("SELECT * FROM events WHERE id = $id");
$event = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $event_date = $_POST["event_date"];
    $location = $_POST["location"];
    $price = $_POST["price"];
    $offer_price = $_POST["offer_price"];

    $imagePath = $event["image"];

    if (!empty($_FILES["image"]["name"])) {
        $target = "uploads/" . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $imagePath = $target;
        }
    }

    $sql = "UPDATE events SET title='$title', description='$description', event_date='$event_date', location='$location', 
            image='$imagePath', price='$price', offer_price='$offer_price' WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Event - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Background */
        body {
            background: url('assets/back.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.85) !important;
        }

        /* Transparent Form Container */
        .content-wrapper {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            max-width: 600px;
            margin: 50px auto;
        }

        /* Form Elements */
        .form-control, .form-label {
            font-size: 16px;
        }

        /* Image Preview */
        .img-preview {
            display: block;
            max-width: 100px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Submit Button */
        .btn-update {
            background: #007bff;
            color: white;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            width: 100%;
        }

        .btn-update:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <!-- Logo & App Name -->
        <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
            <img src="uploads/logo.png" alt="Logo" width="150" height="50" class="me-2">
            <span class="fs-3 fw-bold">Plaza Shopping Mall</span>
        </a>

        <!-- Navbar Toggle for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link fs-5" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white fs-5 px-3" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- Update Event Form -->
<div class="container">
    <div class="content-wrapper">
        <h2 class="text-center mb-4">Update Event</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($event["title"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($event["description"]); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Date:</label>
                <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event["event_date"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Location:</label>
                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($event["location"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (£):</label>
                <input type="number" name="price" step="0.01" class="form-control" value="<?= htmlspecialchars($event["price"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Price (£):</label>
                <input type="number" name="offer_price" step="0.01" class="form-control" value="<?= htmlspecialchars($event["offer_price"]); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image:</label><br>
                <img src="<?= htmlspecialchars($event["image"]); ?>" class="img-preview">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload New Image:</label>
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
            </div>

            <button type="submit" class="btn-update">Update Event</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const imgPreview = document.querySelector(".img-preview");
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
