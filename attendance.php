<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signup.php");
    exit();
}

include 'db.php';

if(isset($_POST['mark'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $check = $conn->query("SELECT * FROM attendance WHERE event_id=$event_id AND user_id=$user_id")->num_rows;
    if($check > 0){
        $conn->query("UPDATE attendance SET status='$status' WHERE event_id=$event_id AND user_id=$user_id");
    } else {
        $conn->query("INSERT INTO attendance (event_id, user_id, status) VALUES ($event_id, $user_id, '$status')");
    }
}
?>

<?php
// Get the logged-in user
$user_id = $_SESSION['user_id'];

// Total events
$total_events = $conn->query("SELECT COUNT(*) as total FROM events")->fetch_assoc()['total'];

// Events attended (status = 'Present')
$attended_events = $conn->query("
    SELECT COUNT(*) as attended 
    FROM attendance 
    WHERE user_id = $user_id AND status='Present'
")->fetch_assoc()['attended'];

// Calculate attendance percentage
$attendance_percentage = $total_events > 0 ? round(($attended_events / $total_events) * 100) : 0;
?>



<!DOCTYPE html>
<html>
<head>
    <title>Campus Connect - Attendance</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


<div class="main">

    <h1>Attendance Tracker</h1>
    <p class="subtitle">Monitor Event Attendance</p>

            
        <div class="card" style="margin-bottom:30px;">
            <strong>Current Attendance:</strong> <?= $attendance_percentage ?>% &nbsp;&nbsp;
            <strong>Events Attended:</strong> <?= $attended_events ?> / <?= $total_events ?>
        </div>


    
    <div class="card" style="max-width:500px; margin-bottom:40px;">
        <form id="attendanceForm">
            <select name="event_id" required>
                <option value="">Select Event</option>
                <?php
                $events = $conn->query("SELECT * FROM events");
                while($e = $events->fetch_assoc()):
                ?>
                <option value="<?= $e['id'] ?>"><?= $e['title'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="user_id" required>
                <option value="">Select User</option>
                <?php
                $users = $conn->query("SELECT * FROM users");
                while($u = $users->fetch_assoc()):
                ?>
                <option value="<?= $u['id'] ?>"><?= $u['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <select name="status">
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>

            <button type="submit" style="background-color: aqua;">Mark Attendance</button>
        </form>
    </div>

   
    <div class="card">
        <table class="attendance-table">
            <tr>
                <th>User</th>
                <th>Event</th>
                <th>Status</th>
            </tr>
            <?php
            $records = $conn->query("
                SELECT u.name, e.title, a.status
                FROM attendance a
                JOIN users u ON a.user_id = u.id
                JOIN events e ON a.event_id = e.id
            ");
            while($row = $records->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['title'] ?></td>
                <td class="<?= $row['status'] == 'Present' ? 'present' : 'absent' ?>">
                    <?= $row['status'] ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <button style="margin-top:20px; background-color:lightgreen;">Download Report</button>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="script.js"></script>


</body>
</html>
