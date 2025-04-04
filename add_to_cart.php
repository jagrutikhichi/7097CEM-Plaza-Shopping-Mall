<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = $_POST["quantity"];

    $product = $conn->query("SELECT * FROM products WHERE id = $product_id")->fetch_assoc();

    if (!$product) {
        echo "<script>alert('Product not found!'); window.location='user_dashboard.php';</script>";
        exit();
    }

    // Use offer price if available
    $price = !empty($product["offer_price"]) && $product["offer_price"] < $product["price"] ? $product["offer_price"] : $product["price"];

    $cart_item = [
        "id" => $product["id"],
        "name" => $product["name"],
        "price" => $price,
        "original_price" => $product["price"],
        "offer_price" => !empty($product["offer_price"]) ? $product["offer_price"] : "N/A",
        "discount" => !empty($product["offer_price"]) && $product["offer_price"] < $product["price"] 
            ? round(100 - ($product["offer_price"] / $product["price"] * 100)) . "%" 
            : "-",
        "quantity" => $quantity,
        "image" => $product["image"]
    ];

    // Initialize cart if not set
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    // Add to cart
    $_SESSION["cart"][$product_id] = $cart_item;

    echo "<script>alert('Product added to cart!'); window.location='cart.php';</script>";
}
?>
