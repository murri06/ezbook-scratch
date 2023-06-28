<?php
//connecting to database
include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // retrieve the checked checkboxes
    $completed_tasks = $_POST["completed"];
    $department_id = $_POST["department"];
    echo $department_id;
    // loop through the checked tasks and update them in the database
    foreach ($completed_tasks as $task_id) {
        $query = "UPDATE dep_tasks SET complete = 1 WHERE id = $task_id";
        $conn->query($query);
    }

    //redirect back to the original page (to prevent form resubmission on refresh) and close connection for database
    header("Location: ../dashboard.php?form=1&department=$department_id");
    $conn->close();
    exit();
}
