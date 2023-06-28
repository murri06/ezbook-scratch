<?php
//connecting to database
include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // receiving data from the form
    $id = $_GET["dep"];
    //creating sql request and performs it, then checking for complete
    $sql1 = "DELETE FROM departments WHERE id = $id";
    $sql2 = "DELETE FROM hr_staff WHERE department_id = $id";
    $sql3 = "DELETE FROM dep_tasks WHERE department_id = $id";
    $query = $sql1 . ";" . $sql2 . ";" . $sql3;
    echo $query;
    if ($conn->multi_query($query)) {
        //redirecting to homepage and closing connection to the database
        header("Location: ../dashboard.php?form=1");
        $conn->close();
        exit();
    } else echo 'Error!';
}