<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
  
</head>
<style>
    body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

.button {
    display: inline-block;
    margin: 10px 0;
    padding: 10px 20px;
    text-decoration: none;
    color: #fff;
    background-color: #3498db;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.button:hover {
    background-color: #2980b9;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

thead {
    background-color: #3498db;
    color: #fff;
}

tbody tr:hover {
    background-color: #f5f5f5;
}

tbody td {
    color: #333;
}

/* Responsive Styles */
@media (max-width: 600px) {
    .container {
        padding: 10px;
    }

    table th, table td {
        font-size: 14px;
    }
}
</style>
<body>

<div class="container">
    <h2>Dashboard</h2>

    <!-- Buttons to Access Forms -->
    <a href="patient_form.php" class="button">Manage Patients</a>
    <a href="schedule_form.php" class="button">Manage Schedules</a>
    <a href="appointment_form.php" class="button">Manage Appointments</a>

    <!-- Display Appointments Table -->
    <h2>Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Schedule Date</th>
                <th>Schedule Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Include the database connection file
            include 'include/db_connection.php';

            // Fetch appointments
            $sqlAppointments = "SELECT appointments.id, patients.name AS patient_name, schedule.schedule_date, schedule.schedule_time, appointments.status FROM appointments
            INNER JOIN patients ON appointments.patient_id = patients.patient_id
            INNER JOIN schedule ON appointments.schedule_id = schedule.schedule_id";

            $resultAppointments = $db->query($sqlAppointments);

            if ($resultAppointments === FALSE) {
                die("Error executing the query: " . $db->error);
            }

            if ($resultAppointments->num_rows > 0) {
                while ($row = $resultAppointments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['patient_name']}</td>";
                    echo "<td>{$row['schedule_date']}</td>";
                    echo "<td>{$row['schedule_time']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
