<?php

session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}

include 'db.php';

if(isset($_POST['add'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO events (title, description, date, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sss", $title, $desc, $date);
    $stmt->execute();
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM events WHERE id=$id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Connect - Events Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="topbar">
    <div class="logo">Campus Connect</div>

    <div class="nav-dropdown">
    <button class="nav-btn">Menu ▾</button>
    <div class="dropdown-content">
        <a href="index.php">Dashboard</a>
        <a href="events.php">Events</a>
        <a href="attendance.php">Attendance</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>
</div>

<div class="events-page">
    <div class="events-wrapper">

        
        <div class="events-main">

            <div class="events-header-box">
                <h2>Upcoming Events</h2>
                <p>Explore Campus Activities</p>
            </div>

            <div class="events-list">
                <?php
                $events = $conn->query("SELECT * FROM events ORDER BY date DESC");
                while ($row = $events->fetch_assoc()):
                ?>
                    <div class="event-card">

                        
                        <div class="event-image">
                            <img src="uploads/<?= $row['image'] ?: 'default.png' ?>" alt="Event Image">

                        </div>

                        
                        <div class="event-content">
                            <h3><?= $row['title'] ?></h3>

                            <span class="event-date">
                                <?= date("F d, Y h:i A", strtotime($row['date'])) ?>
                            </span>

                            <p><?= $row['description'] ?></p>
                        </div>

                        
                        <div class="event-actions">

                            <button class="view-btn" style="background-color: blue; color:white;"
                                onclick="viewDetails(
                                    '<?= addslashes($row['title']) ?>',
                                    '<?= addslashes($row['description']) ?>',
                                    '<?= $row['date'] ?>'
                                )">
                                View Details
                            </button>

                            <button class="edit-btn" style="background-color: green; color: white;"
                                onclick="openEditEvent(
                                    <?= $row['id'] ?>,
                                    '<?= addslashes($row['title']) ?>',
                                    '<?= addslashes($row['description']) ?>',
                                    '<?= $row['date'] ?>'
                                )">
                                Edit
                            </button>

                            <button class="delete-btn" style="background-color: red; color:white;"
                                onclick="deleteEvent(<?= $row['id'] ?>)">
                                Delete
                            </button>

                        </div>

                    </div>
                <?php endwhile; ?>
            </div>

        </div> 

        <div class="add-event-card">
            <h3>Add Event</h3>

            <form id="eventForm" enctype="multipart/form-data">
                <input type="text" name="title" placeholder="Event Title" required>
                <input type="datetime-local" name="date" required>
                <textarea name="description" placeholder="Event Description"></textarea>
                <input type="file" name="image" accept="image/*" required>
                <button type="submit" style="background-color: aqua;">Add Event</button>
            </form>
        </div>

    </div> 
</div> 


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script src="script.js"></script>

</body>
</html>
