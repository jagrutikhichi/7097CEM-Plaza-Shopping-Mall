<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$events = $conn->query("SELECT * FROM events WHERE event_date >= CURDATE()");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
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

        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
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
                <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-container">
    <div class="container my-4">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Upcoming Events</h2>
            <div class="row">
                <?php if ($events->num_rows > 0): ?>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="uploads/<?= htmlspecialchars($event['image']); ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($event['title']); ?>"
                                     onerror="this.onerror=null; this.src='assets/default_event.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title"> <?= htmlspecialchars($event['title']); ?> </h5>
                                    <p class="card-text"> <?= htmlspecialchars($event['description']); ?> </p>
                                    <p class="card-text"><strong>Date:</strong> <?= htmlspecialchars($event['event_date']); ?></p>
                                    <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
                                    <p class="card-text">
                                        <strong>Price:</strong> $<?= number_format($event['price'], 2); ?>
                                        <?php if (!empty($event['offer_price']) && $event['offer_price'] < $event['price']): ?>
                                            <span class="text-success fw-bold">$<?= number_format($event['offer_price'], 2); ?></span>
                                            <span class="badge bg-danger"><?= round(100 - ($event['offer_price'] / $event['price'] * 100)) ?>% Off</span>
                                        <?php else: ?>
                                            <span class="text-muted">No Discount</span>
                                        <?php endif; ?>
                                    </p>
                                    <a href="book_event.php?id=<?= $event['id']; ?>" class="btn btn-primary">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No upcoming events available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
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

            <div class="col-md-3">
                <h5>Follow Us</h5>
                <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="text-light"><i class="fab fa-linkedin fa-lg"></i></a>
            </div>

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
    </div>
</footer>

</body>
</html>
