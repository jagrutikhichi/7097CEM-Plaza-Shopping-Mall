<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];
$conn->query("DELETE FROM dining WHERE id = $id");

header("Location: manage_dining.php");
exit();
?>
