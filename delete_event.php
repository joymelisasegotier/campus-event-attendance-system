<?php
include 'db.php';

$id = $_POST['id'];

$conn->query("DELETE FROM events WHERE id=$id");

echo "success";
