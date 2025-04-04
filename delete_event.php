<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];

$result = $conn->query("SELECT image FROM events WHERE id = $id");
$event = $result->fetch_assoc();
$imagePath = $event["image"];

if ($conn->query("DELETE FROM events WHERE id = $id")) {
    if (file_exists($imagePath)) {
        unlink($imagePath); // Delete the image from the server
    }
    header("Location: manage_events.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?>
