<?php
include 'database.php';

//check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //receiving the post data from the form and sanitizing it
    $name = filter_input(INPUT_POST, "dep_name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $totalCost = filter_input(INPUT_POST, "dep_cost", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //creating a query request to create a new task
    $sql = "INSERT INTO departments(name, totalCost, amountWorkers ) VALUES ('$name', '$totalCost', '0')";
    if ($conn->query($sql)) {

        //redirect back to the original page (to prevent form resubmission on refresh) and close connection to the database
        header("Location: ../dashboard.php?form=1");
        $conn->close();
        exit();
    } else echo 'Error!';
}
