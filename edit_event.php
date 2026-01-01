<?php
include 'db.php';

$id = $_POST['id'];
$title = $_POST['title'];
$desc = $_POST['description'];
$date = $_POST['date'];

$stmt = $conn->prepare(
    "UPDATE events SET title=?, description=?, date=? WHERE id=?"
);
$stmt->bind_param("sssi", $title, $desc, $date, $id);
$stmt->execute();

echo "success";
