<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Handle cart operations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_cart"])) {
    $id = $_POST["id"];
    $qty = max(1, (int)$_POST["quantity"]);
    if (isset($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id]["quantity"] = $qty;
        $price = ($_SESSION["cart"][$id]["offer_price"] != "N/A" && $_SESSION["cart"][$id]["offer_price"] < $_SESSION["cart"][$id]["original_price"])
            ? $_SESSION["cart"][$id]["offer_price"] : $_SESSION["cart"][$id]["original_price"];
        $subtotal = $price * $qty;
        $total = array_sum(array_map(fn($item) => (($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? $item["offer_price"] : $item["original_price"]) * $item["quantity"], $_SESSION["cart"]));

        echo json_encode(["subtotal" => number_format($subtotal, 2), "total" => number_format($total, 2)]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
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
            margin-top: 100px;
            padding: 20px;
        }

        .transparent-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .cart-table th, .cart-table td {
            text-align: center;
            vertical-align: middle;
        }

        .cart-buttons {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">
            <img src="uploads/logo.png" alt="Mall Logo" style="height: 40px; margin-right: 10px;">
            Plaza Shopping
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Basket <span class="cart-count">(<?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0 ?>)</span></a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="content-container">
    <div class="container">
        <div class="transparent-container">
            <h2 class="text-center text-dark mb-4">Your Shopping Cart</h2>

            <?php if (empty($_SESSION["cart"])): ?>
                <p class="text-center">Your cart is empty.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped cart-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Original Price</th>
                            <th>Offer Price</th>
                            <th>Discount</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($_SESSION["cart"] as $id => $item): ?>
                            <?php 
                                $price = ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? $item["offer_price"] : $item["original_price"];
                                $subtotal = $price * $item["quantity"];
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><img src="uploads/<?= $item["image"]; ?>" width="50"></td>
                                <td><?= htmlspecialchars($item["name"]); ?></td>
                                <td>$<?= $item["original_price"]; ?></td>
                                <td class="text-success"><?= $item["offer_price"] != "N/A" ? "$" . $item["offer_price"] : "-"; ?></td>
                                <td>
                                    <?= ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? round((($item["original_price"] - $item["offer_price"]) / $item["original_price"]) * 100, 2) . "%" : "-"; ?>
                                </td>
                                <td>
                                    <input type="number" id="qty_<?= $id; ?>" name="quantity[<?= $id; ?>]" 
                                           class="form-control text-center mx-auto" style="width: 70px;" 
                                           value="<?= $item["quantity"]; ?>" min="1" 
                                           onchange="updateSubtotal(<?= $id; ?>)">
                                </td>
                                <td id="subtotal_<?= $id; ?>">$<?= number_format($subtotal, 2); ?></td>
                                <td>
                                    <a href="cart.php?remove=<?= $id; ?>" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="6"><strong>Total:</strong></td>
                            <td><strong id="total">$<?= number_format($total, 2); ?></strong></td>
                            <td class="cart-buttons">
                                <a href="cart.php?clear=true" class="btn btn-warning btn-sm">Clear Cart</a>
                                <a href="checkout.php" class="btn btn-success btn-sm">Checkout</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function updateSubtotal(id) {
        let qty = document.getElementById('qty_' + id).value;
        $.post("cart.php", { update_cart: 1, id: id, quantity: qty }, function(response) {
            let data = JSON.parse(response);
            $('#subtotal_' + id).text("$" + data.subtotal);
            $('#total').text("$" + data.total);
        });
    }
</script>

</body>
</html>
