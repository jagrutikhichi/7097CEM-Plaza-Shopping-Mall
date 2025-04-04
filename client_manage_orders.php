<?php
session_start();
include("config.php");

if (!isset($_SESSION["client"])) {
    header("Location: client_login.php");
    exit();
}

// Fetch all orders
$orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");

// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $order_id = $_POST["order_id"];
    $new_status = $_POST["status"];

    $query = "UPDATE orders SET status=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $order_id);

    if ($stmt->execute()) {
        echo "<script>alert('Order status updated!'); window.location='client_manage_orders.php';</script>";
    } else {
        echo "<script>alert('Failed to update order status.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Full-height page layout */
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

        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
        }

        .badge-pending { background-color: gray; color: white; }
        .badge-confirmed { background-color: green; color: white; }
        .badge-delivery { background-color: orange; color: white; }
        .badge-delivered { background-color: blue; color: white; }
        .badge-canceled { background-color: red; color: white; }

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
                <li class="nav-item"><a class="nav-link" href="client_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="content-wrapper">
    <div class="table-container">
        <h2 class="text-center mb-4" style="font-weight: bold;">Manage Orders</h2>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>User Email</th>
                    <th>Full Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Items</th>
                    <th>Total Amount ($)</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= $order["id"]; ?></td>
                        <td><?= $order["user_email"]; ?></td>
                        <td><?= $order["fullname"]; ?></td>
                        <td><?= $order["address"]; ?></td>
                        <td><?= $order["phone"]; ?></td>
                        <td><?= $order["items"]; ?></td>
                        <td>$<?= number_format($order["total_amount"], 2); ?></td>
                        <td><?= $order["payment_method"]; ?></td>
                        <td>
                            <?php 
                                $status_badge = [
                                    "Pending" => "badge-pending",
                                    "Confirmed" => "badge-confirmed",
                                    "Out for Delivery" => "badge-delivery",
                                    "Delivered" => "badge-delivered",
                                    "Canceled" => "badge-canceled"
                                ];
                                echo '<span class="badge ' . $status_badge[$order["status"]] . '">' . $order["status"] . '</span>';
                            ?>
                        </td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $order["id"]; ?>">
                                <select name="status" class="form-select">
                                    <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Confirmed" <?= $order['status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Out for Delivery" <?= $order['status'] == 'Out for Delivery' ? 'selected' : ''; ?>>Out for Delivery</option>
                                    <option value="Delivered" <?= $order['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="Canceled" <?= $order['status'] == 'Canceled' ? 'selected' : ''; ?>>Canceled</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Update</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
