<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$dining = $conn->query("SELECT * FROM dining");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dineout Options</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
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
        .transparent-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .card img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
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
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="dining.php">Dining</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-container">
    <div class="container my-4">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Dineout Options</h2>
            <div class="row">
                <?php if ($dining->num_rows > 0): ?>
                    <?php while ($dine = $dining->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="uploads/<?= htmlspecialchars($dine['image']); ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($dine['name']); ?>"
                                     onerror="this.onerror=null; this.src='assets/default_dining.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title"> <?= htmlspecialchars($dine['name']); ?> </h5>
                                    <p class="card-text"> <?= htmlspecialchars($dine['description']); ?> </p>
                                    <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($dine['location']); ?></p>
                                    <p class="card-text">
                                        <strong>Price:</strong> <del>$<?= number_format($dine['price'], 2); ?></del>
                                        <?php if (!empty($dine['offer_price']) && $dine['offer_price'] < $dine['price']): ?>
                                            <span class="text-success fw-bold">$<?= number_format($dine['offer_price'], 2); ?></span>
                                            <span class="badge bg-danger"><?= round(100 - ($dine['offer_price'] / $dine['price'] * 100)) ?>% Off</span>
                                        <?php else: ?>
                                            <span class="text-muted">No Discount</span>
                                        <?php endif; ?>
                                    </p>
                                    <a href="book_dining.php?id=<?= $dine['id']; ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No dining options available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer bg-dark text-white text-center py-3">
    <p>&copy; 2025 Plaza Shopping. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
