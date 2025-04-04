<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

// Get the search query
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (!empty($query)) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR category LIKE ?");
    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    // If no search query is provided, display all products
    $products = $conn->query("SELECT * FROM products");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">🏬 Mall Shopping Portal</a>

        <div class="ms-auto d-flex">
            <form class="d-flex me-3" action="search.php" method="GET">
                <input class="form-control me-2" type="search" name="query" placeholder="Search Products..." value="<?= htmlspecialchars($query); ?>" required>
                <button class="btn btn-light" type="submit">Search</button>
            </form>

            <!-- Back to Dashboard -->
            <a href="user_dashboard.php" class="btn btn-outline-light me-2">Back to Dashboard</a>

            <!-- Basket Button -->
            <a href="cart.php" class="btn btn-warning">
                Your Basket <span class="cart-count">(<?= isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0 ?>)</span>
            </a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h2 class="text-center">Search Results</h2>
    <div class="product-table p-3">
        <table class="table table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Offer Price</th>
                    <th>Discount</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products->num_rows > 0): ?>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><img src="uploads/<?= $row["image"]; ?>" width="50" class="rounded"></td>
                            <td><?= $row["name"]; ?></td>
                            <td><?= $row["category"]; ?></td>
                            <td>$<?= number_format($row["price"], 2); ?></td>
                            <td>
                                <?php if (!empty($row["offer_price"]) && $row["offer_price"] < $row["price"]): ?>
                                    <span class="text-success fw-bold">$<?= number_format($row["offer_price"], 2); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">No Discount</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($row["offer_price"]) && $row["offer_price"] < $row["price"]): ?>
                                    <span class="badge bg-danger"><?= round(100 - ($row["offer_price"] / $row["price"] * 100)) ?>% Off</span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $row["quantity"]; ?></td>
                            <td>
                                <form class="add-to-cart-form" method="POST">
                                    <input type="hidden" name="product_id" value="<?= $row["id"]; ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?= $row["quantity"]; ?>" class="form-control mb-2" required>
                                    <button type="submit" class="btn btn-primary btn-sm">Add to Cart</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No products found for "<strong><?= htmlspecialchars($query); ?></strong>"</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
