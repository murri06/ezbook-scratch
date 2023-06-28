<?php
//connecting to database
include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // receiving data from the form
    $id = $_POST["id"];
    $department_id = $_POST["department_id"];

    //creating sql request and performs it, then checking for complete
    $sql = "DELETE FROM hr_staff WHERE id = $id";

    if ($conn->query($sql)) {
        //redirecting to homepage and closing connection for database
        header("Location: ../dashboard.php?form=1&department=$department_id");
        $conn->close();
        exit();
    } else echo 'Error!';
}