<?php
//connecting to database
include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // receiving data from the form
    $id = $_GET["id"];
    $department_id = $_GET["dep"];

    //creating sql request and performs it, then checking for complete
    $sql = "DELETE FROM dep_tasks WHERE id = $id";
    if ($conn->query($sql)) {
        //redirecting to homepage and closing connection for database
        header("Location: ../dashboard.php?form=1&department=$department_id");
        $conn->close();
        exit();
    } else echo 'Error!';
}