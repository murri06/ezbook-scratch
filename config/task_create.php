<?php
include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// receiving the post data from the form and sanitizing it
    $department_id = $_POST["department"];
    $name = filter_input(INPUT_POST, "task_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, "task_desc", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $importance = filter_input(INPUT_POST, "task_imp", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// creating a query request to create a new task
    $sql = "INSERT INTO dep_tasks(name, description, department_id, complete, importance) VALUES ('$name', '$description', '$department_id', '0' , '$importance')";
    $conn->query($sql);

//  redirect back to the original page (to prevent form resubmission on refresh)
    header("Location: ../dashboard.php?form=1&department=$department_id");
    $conn->close();
    exit();
}
