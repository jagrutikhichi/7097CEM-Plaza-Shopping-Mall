<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Fetch cart details from session
if (empty($_SESSION["cart"])) {
    echo "<p>Your cart is empty.</p>";
    exit();
}

$user_id = $_SESSION["user"]["id"]; // Assuming the user ID is stored in session

// Function to get client ID based on product ID
function getClientIdByProduct($product_id, $conn) {
    $client_id = null; // Initialize client_id to null
    $stmt = $conn->prepare("SELECT client_id FROM client_product WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($client_id);
    
    // If a client_id is found, fetch it
    if ($stmt->fetch()) {
        return $client_id;
    }

    // Return null if no result is found
    return null;
}

// Process the booking when the user clicks the "Checkout" button
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["checkout"])) {
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Loop through the cart and add each product to the bookings table
        foreach ($_SESSION["cart"] as $id => $item) {
            // Determine the price (offer or original)
            $price = ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? $item["offer_price"] : $item["original_price"];
            $subtotal = $price * $item["quantity"];

            // Get client ID from product
            $product_id = $item["id"];
            $client_id = getClientIdByProduct($product_id, $conn);

            // Check if client_id is null (if no client ID is found for the product)
            if ($client_id === null) {
                // Handle the case where no client ID is found, e.g., error message
                echo "<p>Error: No client found for product {$item['name']}.</p>";
                // Optionally, you can skip the current product or halt the process
                continue; // Skip this product and move to the next
            }

            // Insert booking into bookings table
            $stmt = $conn->prepare("INSERT INTO bookings (user_id, client_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiidi", $user_id, $client_id, $product_id, $item["quantity"], $subtotal);
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();
        
        // Clear cart after successful booking
        unset($_SESSION["cart"]);

        echo "<p>Booking successful. Your products will be processed soon.</p>";
    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        echo "<p>Error occurred. Please try again later.</p>";
    }
}

// Fetch client products from session cart for display
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Checkout</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

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
                                    <?= ($item["offer_price"] != "N/A" && $item["offer_price"] < $item["original_price"]) ? round(100 - ($item["offer_price"] / $item["original_price"] * 100), 2) . "%" : "-"; ?>
                                </td>
                                <td>
                                    <input type="number" id="qty_<?= $id; ?>" name="quantity[<?= $id; ?>]" 
                                           class="form-control text-center mx-auto" style="width: 70px;" 
                                           value="<?= $item["quantity"]; ?>" min="1" 
                                           onchange="updateSubtotal(<?= $id; ?>)">
                                </td>
                                <td id="subtotal_<?= $id; ?>">$<?= number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="6"><strong>Total:</strong></td>
                            <td><strong id="total">$<?= number_format($total, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Checkout Button -->
                <form method="POST" action="book_client_product.php">
                    <button type="submit" name="checkout" class="btn btn-success btn-sm">Proceed to Checkout</button>
                </form>
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
