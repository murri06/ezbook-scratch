<?php

include 'database.php';
// check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //receive POST data
    $name = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $department_id = $_POST["department"];

    //creating sql request to update data
    $sql = "UPDATE departments SET name = '$name' WHERE id = '$department_id'";
    if (!$conn->query($sql)) echo 'Error!';
    //redirect back to the original page (to prevent form resubmission on refresh) and close connection to database
    header("Location: ../dashboard.php?form=1&department=$department_id");
    $conn->close();
    exit();
}
