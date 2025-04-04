<?php
session_start();
include("config.php");

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET["id"];
$event = $conn->query("SELECT * FROM events WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $event_date = $_POST["event_date"];
    $location = $_POST["location"];

    // Check if a new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $image = $_FILES["image"]["name"];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    } else {
        $image = $event["image"]; // Keep existing image
    }

    $sql = "UPDATE events SET 
            title = '$title', 
            description = '$description', 
            event_date = '$event_date', 
            location = '$location', 
            image = '$image' 
            WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Event</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <nav>
        <a href="manage_events.php">Back to Events</a>
    </nav>

    <h2>Edit Event</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" value="<?= $event['title']; ?>" required><br>

        <label>Description:</label>
        <textarea name="description" required><?= $event['description']; ?></textarea><br>

        <label>Event Date:</label>
        <input type="date" name="event_date" value="<?= $event['event_date']; ?>" required><br>

        <label>Location:</label>
        <input type="text" name="location" value="<?= $event['location']; ?>" required><br>

        <label>Current Image:</label>
        <img src="uploads/<?= $event['image']; ?>" width="50"><br>

        <label>Upload New Image:</label>
        <input type="file" name="image" accept="image/*"><br>

        <button type="submit">Update Event</button>
    </form>
</body>
</html>
