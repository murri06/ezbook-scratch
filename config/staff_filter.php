<?php
if (isset($_POST['searchButton']) && !isset($_POST['clearButton'])) {
    // Processing search request
    $searchBy = $_POST['searchBy'];
    $searchText = $_POST['searchText'];

    //adding additional parameters to field name and creating sql request
    if ($searchBy == 'name')
        $sql = "SELECT * FROM hr_staff WHERE $searchBy LIKE '%$searchText%'  AND department_id = '$department_id' OR surname LIKE '%$searchText%' AND department_id = '$department_id'";
    else
        $sql = "SELECT * FROM hr_staff WHERE $searchBy LIKE '$searchText' AND department_id = '$department_id'";

    //executing sql request and filling staff array with results
    $staff = $conn->query($sql);

    //checking about successive request
    if ($staff->num_rows == 0) {
        $zeroRecords = 'There is no records.';
    }

} else {
    // Receiving staff data for department
    $sql = "SELECT * FROM hr_staff WHERE department_id = " . $department_id;
    $staff = $conn->query($sql);
}

if (isset($_POST['clearButton']))
    $_POST = array();
