<?php
session_start();
include("config.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_SESSION["user"];
    $event_id = $_POST["event_id"];
    $guest_name = $_POST["guest_name"];
    $age = $_POST["age"];
    $mobile_no = $_POST["mobile_no"];
    $quantity = $_POST["quantity"];

    // Get event price
    $event_query = $conn->prepare("SELECT price FROM events WHERE id = ?");
    $event_query->bind_param("i", $event_id);
    $event_query->execute();
    $event_result = $event_query->get_result();

    if ($event_result->num_rows == 0) {
        echo "<script>alert('Invalid event.'); window.location='events.php';</script>";
        exit();
    }

    $event = $event_result->fetch_assoc();
    $total_price = $event["price"] * $quantity;

    // Insert booking into the database
    $stmt = $conn->prepare("INSERT INTO event_bookings (user_email, event_id, guest_name, age, mobile_no, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisiiid", $user_email, $event_id, $guest_name, $age, $mobile_no, $quantity, $total_price);

    if ($stmt->execute()) {
        echo "<script>alert('Event booked successfully!'); window.location='events.php';</script>";
    } else {
        echo "<script>alert('Failed to book event.'); window.location='events.php';</script>";
    }
}
?>
