<?php
// Connecting to database
include 'database.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Receiving data from the form
    $id = $_POST["id"];
    // Creating sql request and performs it, then checking for complete
    $sql = "DELETE FROM pass_syst WHERE id = $id";

    if ($conn->query($sql)) {
        // Redirecting to homepage and closing connection for database
        header("Location: ../dashboard.php");
        $conn->close();
        exit();
    } else echo 'Error!';
}
