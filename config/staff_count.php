<?php
$department_id = $row["id"];

$sql = "SELECT id FROM hr_staff WHERE department_id = '$department_id'";
$staff = $conn->query($sql);

$countStaff = $staff->num_rows;
if ($countStaff !== $row["amountWorkers"]) {
    $sql = "UPDATE departments SET amountWorkers = '$countStaff' WHERE id = '$department_id'";
    if (!$conn->query($sql))
        echo 'Error!';
    $row["amountWorkers"] = $countStaff;
}