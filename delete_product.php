<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>alert('Product deleted successfully!'); window.location='admin_dashboard.php';</script>";
}
?>
