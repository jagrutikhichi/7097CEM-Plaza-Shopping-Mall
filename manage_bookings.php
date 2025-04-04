<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch event bookings
$event_bookings = $conn->query("
    SELECT eb.id, u.name AS user_name, u.email, e.title, eb.guest_name, eb.age, eb.mobile, eb.quantity, eb.total_price, eb.booking_date
    FROM event_bookings eb
    JOIN events e ON eb.event_id = e.id
    JOIN users u ON eb.user_email = u.email
");

// Fetch dining bookings
$dining_bookings = $conn->query("
    SELECT bd.id, u.name AS user_name, u.email, d.name AS dining_name, bd.guest_name, bd.age, bd.mobile, bd.dining_date, bd.quantity, bd.total_price, bd.booking_date
    FROM booking_dining bd
    JOIN dining d ON bd.dining_id = d.id
    JOIN users u ON bd.user_email = u.email
");

// Handle cancellations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancel_booking"])) {
    $booking_id = intval($_POST["booking_id"]);
    $booking_type = $_POST["booking_type"];

    if ($booking_type == "event") {
        $stmt = $conn->prepare("DELETE FROM event_bookings WHERE id = ?");
    } elseif ($booking_type == "dining") {
        $stmt = $conn->prepare("DELETE FROM booking_dining WHERE id = ?");
    }

    if (isset($stmt)) {
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            echo "<script>alert('Booking cancelled successfully!'); window.location='manage_bookings.php';</script>";
        } else {
            echo "<script>alert('Failed to cancel booking.'); window.location='manage_bookings.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: url('assets/background1.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.85) !important;
        }

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

        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px 0;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            width: 90%;
            max-width: 1400px;
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
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="content-wrapper">
    <div class="table-container">
        <h2 class="text-center mb-4" style="font-weight: bold;">Manage Event Bookings</h2>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Event</th>
                    <th>Guest Name</th>
                    <th>Age</th>
                    <th>Mobile</th>
                    <th>Quantity</th>
                    <th>Total Price ($)</th>
                    <th>Booking Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $event_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["user_name"]); ?></td>
                        <td><?= htmlspecialchars($row["email"]); ?></td>
                        <td><?= htmlspecialchars($row["title"]); ?></td>
                        <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                        <td><?= $row["age"]; ?></td>
                        <td><?= $row["mobile"]; ?></td>
                        <td><?= $row["quantity"]; ?></td>
                        <td>$<?= number_format($row["total_price"], 2); ?></td>
                        <td><?= $row["booking_date"]; ?></td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                                <input type="hidden" name="booking_type" value="event">
                                <button type="submit" name="cancel_booking" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2 class="text-center mb-4" style="font-weight: bold;">Manage Dining Bookings</h2>
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Dining</th>
                    <th>Guest Name</th>
                    <th>Age</th>
                    <th>Mobile</th>
                    <th>Dining Date</th>
                    <th>Quantity</th>
                    <th>Total Price ($)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $dining_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["user_name"]); ?></td>
                        <td><?= htmlspecialchars($row["email"]); ?></td>
                        <td><?= htmlspecialchars($row["dining_name"]); ?></td>
                        <td><?= htmlspecialchars($row["guest_name"]); ?></td>
                        <td><?= $row["age"]; ?></td>
                        <td><?= $row["mobile"]; ?></td>
                        <td><?= $row["dining_date"]; ?></td>
                        <td><?= $row["quantity"]; ?></td>
                        <td>$<?= number_format($row["total_price"], 2); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="booking_id" value="<?= $row["id"]; ?>">
                                <input type="hidden" name="booking_type" value="dining">
                                <button type="submit" name="cancel_booking" class="btn btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; 2025 Plaza Shopping Mall Admin Panel. All Rights Reserved.</p>
</footer>
</body>
</html>
