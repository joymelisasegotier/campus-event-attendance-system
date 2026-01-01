<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}
include 'db.php';
?>


<!DOCTYPE html>
<html>
<head>
    <title>Campus Connect - Home</title>
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
</div>


<div class="main">
<div class="hero">
    <div class="hero-overlay">
        <div class="hero-text">
            <h1>Welcome to Campus Connect</h1>
            <p>Your gateway to campus events and participation.</p>
            <button id="getStartedBtn" style="background-color: lightblue;">Get Started</button>
        </div>
    </div>
</div>


   <div class="cards">
    <div class="card">
        <h3>Track Attendance</h3>
        <p>Monitor event participation efficiently.</p>
    </div>

    <div class="card">
        <h3>Upcoming Events</h3>
        <p>Stay updated with campus activities.</p>
    </div>
</div>

<div class="cards" style="margin-top:40px;">

   
    <div class="card">
        <h3>Latest Events</h3>
        <?php
$latest = $conn->query("SELECT * FROM events ORDER BY date DESC LIMIT 2");

if ($latest->num_rows > 0):
    while ($row = $latest->fetch_assoc()):
?>
    <div class="mini-event">
        <h4><?= $row['title'] ?></h4>
        <span><?= date("M d, Y", strtotime($row['date'])) ?></span>
    </div>
<?php
    endwhile;
else:
?>
    <p>No events yet.</p>
<?php endif; ?>

        <a href="events.php" class="view-link" style="display:inline-block; padding:10px 18px; background:#f4b400; color:#fff; text-decoration:none; border-radius:6px;">View All Events →</a>
    </div>

   
    <div class="card">
        <h3>Attendance Summary</h3>
        <?php
        $total = $conn->query("SELECT COUNT(*) AS total FROM attendance")->fetch_assoc()['total'];
        $present = $conn->query("SELECT COUNT(*) AS p FROM attendance WHERE status='Present'")->fetch_assoc()['p'];
        $percent = $total > 0 ? round(($present/$total)*100) : 0;
        ?>
        <p><strong><?= $percent ?>%</strong> attendance rate</p>
        <p><?= $present ?> events attended</p>
        <a href="attendance.php" class="view-link" style="display:inline-block; padding:10px 18px; background:#f4b400; color:#fff; text-decoration:none; border-radius:6px;">View Attendance →</a>
    </div>

</div>

<div id="startModal" class="modal">
    <div class="modal-content">
        <h3>Quick Action</h3>
        <p>What would you like to do?</p>
        <a href="events.php">Manage Events</a><br><br>
        <a href="attendance.php">Mark Attendance</a>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="script.js"></script>

</body>
</html>
