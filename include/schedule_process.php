<?php
// Include the database connection file
include 'db_connection.php';

// Add new schedule
if (isset($_POST['add'])) {
    $scheduleTime = $_POST['schedule_time'];
    $scheduleDate = $_POST['schedule_date'];
    $status = 'vacant'; // Default status is vacant

    $stmt = $db->prepare("INSERT INTO schedule (schedule_time, schedule_date, status) VALUES (?, ?, ?)");

    // Check for errors in the prepared statement
    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sss", $scheduleTime, $scheduleDate, $status);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the schedule form after adding
    header("Location: ../schedule_form.php");
    exit();
}

// Edit schedule
if (isset($_POST['edit'])) {
    $schedule_id = $_POST['schedule_id'];
    $scheduleTime = $_POST['schedule_time'];
    $scheduleDate = $_POST['schedule_date'];
    $status = $_POST['status'];

    $stmt = $db->prepare("UPDATE schedule SET schedule_time=?, schedule_date=?, status=? WHERE schedule_id=?");

    // Check for errors in the prepared statement
    if (!$stmt) {
        die('Error: ' . $db->error);
    }

    $stmt->bind_param("sssi", $scheduleTime, $scheduleDate, $status, $schedule_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the schedule form after editing
    header("Location: ../schedule_form.php");
    exit();
}

// Delete schedule
if (isset($_GET['delete'])) {
    $schedule_id = $_GET['delete'];
    $db->query("DELETE FROM schedule WHERE schedule_id=$schedule_id");

    // Redirect back to the schedule form after deleting
    header("Location: ../schedule_form.php");
    exit();
}

$db->close();
?>
