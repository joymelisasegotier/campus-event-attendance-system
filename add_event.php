<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';
$rawDate = $_POST['date'] ?? '';

if (!$rawDate) {
    die("Date missing");
}

$rawDate = str_replace('T', ' ', $rawDate);
$date = $rawDate . ':00';




    if (empty($title) || empty($date)) {
        die("Missing required fields");
    }

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES['image']['name']);
        $target = "uploads/" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            die("Image upload failed");
        }
    }

    $stmt = $conn->prepare(
        "INSERT INTO events (title, description, date, image)
         VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $title, $desc, $date, $imageName);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Insert failed: " . $stmt->error;
    }

    $stmt->close();
}
