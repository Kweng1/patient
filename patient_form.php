<?php
// Include the database connection file
include 'include/db_connection.php';

// Initialize variables for the edit form
$editMode = false;
$editId = '';
$editName = '';
$editEmail = '';

// Check if edit button is clicked
if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editMode = true;

    // Fetch data of the selected patient for editing
    $result = $db->query("SELECT * FROM patients WHERE patient_id = $editId");

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $editName = $row['name'];
        $editEmail = $row['email'];
    }
}

// Process form submission for adding new patient
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("INSERT INTO patients (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient_form.php");
    exit();
}

// Process form submission for editing existing patient
if (isset($_POST['edit'])) {
    $id = $_POST['patient_id']; // Change 'id' to 'patient_id'
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $db->prepare("UPDATE patients SET name=?, email=? WHERE patient_id=?");
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the patient form
    header("Location: patient_form.php");
    exit();
}

// Fetch and display patient data from the database
$result = $db->query("SELECT * FROM patients");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Patient Form</title>
</head>
<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
}

form {
    margin-top: 20px;
}

label {
    display: block;
    margin-top: 10px;
    color: #333;
}

input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px;
    margin-right: 10px;
    cursor: pointer;
    background-color: #3498db;
    color: #fff;
    border: none;
    border-radius: 4px;
}

button:hover {
    background-color: #2980b9;
}

.table-container {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
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
        <?php if (!$editMode): ?>
            <h2>Patient Form</h2>
            <!-- Form to add new patient -->
            <form action="patient_form.php" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <button type="submit" name="add">Add</button>
            </form>
        <?php endif; ?>

        <a href="dashboard.php" class="button" style=" background-color: #4CAF50; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;"> Back </a>

        <?php if ($editMode): ?>
            <!-- Form to edit existing patient -->
            <h2>Edit Patient</h2>
            <form action="patient_form.php" method="post">
                <input type="hidden" name="patient_id" value="<?php echo $editId; ?>"> <!-- Change 'id' to 'patient_id' -->
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo $editName; ?>" required>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $editEmail; ?>" required>
                <button type="submit" name="edit">Edit</button>
            </form>
        <?php endif; ?>

        <!-- Display added patients -->
        <h2>Patients</h2>

        <?php
        if ($result->num_rows > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Name</th>';
            echo '<th>Email</th>';
            echo '<th>Action</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td><a href='patient_form.php?edit={$row['patient_id']}' class='button edit'>Edit</a> | <a href='include/patient_process.php?delete={$row['patient_id']}' class='button delete'>Delete</a>
                ";
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No patients found.</p>';
        }

        $result->free(); // Free the result set
        ?>

       
    </div>
</body>
</html>

<?php
$db->close();
?>
