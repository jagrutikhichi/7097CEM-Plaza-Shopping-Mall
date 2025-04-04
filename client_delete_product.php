<?php
session_start();
include("config.php");

if (!isset($_SESSION["client"])) {
    header("Location: client_login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>alert('Product deleted successfully!'); window.location='client_dashboard.php';</script>";
}
?>
