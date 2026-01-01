<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    // Check if attendance already exists
    $check = $conn->prepare(
        "SELECT id FROM attendance WHERE event_id=? AND user_id=?"
    );
    $check->bind_param("ii", $event_id, $user_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        
        $update = $conn->prepare(
            "UPDATE attendance SET status=? WHERE event_id=? AND user_id=?"
        );
        $update->bind_param("sii", $status, $event_id, $user_id);
        $update->execute();
    } else {
        
        $insert = $conn->prepare(
            "INSERT INTO attendance (event_id, user_id, status)
             VALUES (?, ?, ?)"
        );
        $insert->bind_param("iis", $event_id, $user_id, $status);
        $insert->execute();
    }

    echo "success";
}
?>
