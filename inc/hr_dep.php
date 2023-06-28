<main class="container-fluid h-75">
    <div class="row">
        <div class="col-md-2">
            <h3>Departments</h3>
            <ul class="list-group">
                <?php
                // Retrieve all departments from the database
                $sql = "SELECT * FROM departments";
                $result = $conn->query($sql);

                // Loop through each department and create a link to show staff and tasks for that department
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Counting number of staff and writing info in database if needed
                        include 'config/staff_count.php';

                        // Listing departments
                        echo '<li class="list-group-item"><a class="text-decoration-none" style="color: rgb(33, 37, 41);" href="?form=1&department=' . $row["id"] . '">' . $row["name"] . '</a></li>';
                    }
                } else {
                    echo "No departments found";
                }
                if ($_SESSION["role"] == "HRI")
                    echo '<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#createDepModal" style="margin-top:14px">Create new department</button>';
                ?>
            </ul>
        </div>

        <div class="col-md-10">
            <?php
            // Check if a department has been selected
            if (isset($_GET["department"])):

            $zeroRecords = 'No staff found for this department';
            $department_id = $_GET["department"];

            // Receiving information about department
            $sql = "SELECT * FROM departments WHERE id = " . $department_id;
            $result = $conn->query($sql);
            $result = $result->fetch_assoc();

            // Receiving tasks for department
            $sql = "SELECT * FROM dep_tasks WHERE department_id = " . $department_id;
            $tasks = $conn->query($sql);
            $countTasks = $tasks->num_rows;

            // Counting completed tasks
            $sql = "SELECT * FROM dep_tasks WHERE complete = 1 AND department_id = " . $department_id;
            $compTasks = $conn->query($sql);
            $completedTasks = $compTasks->num_rows;

            include 'config/staff_filter.php';

            echo '<h3>Information about ' . $result['name'];
            if ($_SESSION['role'] == 'HRI'): ?>
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#editDepartmentModal"><i
                            class="bi bi-pencil"></i></button>
                <a class="btn btn-danger" href="config/department_delete.php?dep=<?= $department_id ?>"
                   onclick="return confirm('Are you sure you want to delete this department? All workers and tasks that links to this department will be deleted too.')"><i
                            class="bi bi-trash"></i></a>
            <?php endif;
            echo '</h3>' ?>
            <div class="w-50"><h4>Amount of workers in the department: <?= $result["amountWorkers"] ?></h4></div>
            <div class="w-50"><h4>Completed tasks in the department: <?php echo "$completedTasks/$countTasks" ?></h4>
            </div>
            <div class="w-25"><h4>Total cost: <?php echo $result["totalCost"] ?>k $</h4></div>


            <div class="row">
                <div class="col-md-7 col-12">
                    <div class="row">
                        <div class="col-md-5 col-12"><h3>Staff</h3></div>
                        <div class="col-md-7 col-12">
                            <form method="post">
                                <div class="input-group mb-3">
                                    <select class="form-select" name="searchBy" id="searchBy">
                                        <option value="name">Name</option>
                                        <option value="position" <?php if (isset($_POST['searchBy']) && $_POST['searchBy'] == 'position') echo 'selected="selected"' ?>>
                                            Position
                                        </option>
                                        <option value="sex" <?php if (isset($_POST['searchBy']) && $_POST['searchBy'] == 'sex') echo 'selected="selected"' ?>>
                                            Sex
                                        </option>
                                    </select>

                                    <input type="text" class="form-control" placeholder="Search staff..."
                                           name="searchText" id="searchText"
                                           value="<?php if (isset($_POST['searchText'])) echo $_POST['searchText'] ?>">
                                    <button class="btn btn-outline-dark" type="submit" name="searchButton">Search
                                    </button>
                                    <button class="btn btn-outline-secondary" type="submit" name="clearButton">Clear
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    if ($staff->num_rows > 0): ?>
                        <div class="slider">
                            <table class="table table-fixed <?php if ($staff->num_rows > 10) echo ' fixed-table'; ?>">

                                <thead class="table-dark">
                                <tr>
                                    <th class="col-2">Name</th>
                                    <th class="col-1">Birth date</th>
                                    <th class="col-1">Sex</th>
                                    <th class="col-1">Location</th>
                                    <th class="col-1">Grade</th>
                                    <th class="col-1">Position</th>
                                    <th class="col-1" data-toggle="tooltip" title="Internal experience">Int<br> exp</th>
                                    <th class="col-1" data-toggle="tooltip" title="External experience">Ext<br> exp</th>
                                    <th class="col-1">Wage</th>

                                    <?php if ($_SESSION['role'] == 'HRI'): ?>
                                        <th class="col-1">Edit</th>
                                        <th class="col-1">Delete</th>
                                    <?php endif; ?>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = $staff->fetch_assoc()) {
                                    echo '
                                    <form method="post" action="config/staff_delete.php">
                                        <input type="hidden" id="department_id" name="department_id"
                                       value="' . $department_id . '">
                                        <tr>
                                        <td>' . $row["name"] . ' ' . $row["middleName"] . ' ' . $row["surname"] . '</td>
                                        <td>' . $row["dob"] . '</td>
                                        <td>' . $row["sex"] . '</td>
                                        <td>'
                                        . $row["location"] . '
                                        </td>
                                        <td>' . $row["grade"] . '</td>
                                        <td>'
                                        . $row["position"] . '
                                        </td>
                                        <td>' . $row["intExp"] . '</td>
                                        <td>'
                                        . $row["extExp"] . '
                                        </td>
                                        <td>' . $row["wageMonth"] . '$</td>
                                        <input type="hidden" id="id" name="id" value="' . $row["id"] . '">';

                                    //if user role is set to input, create two buttons for editing and deleting staff
                                    if ($_SESSION['role'] == 'HRI'): ?>
                                        <td><a class="btn btn-secondary"
                                               href="dashboard.php?form=2&id=<?php echo $row["id"] ?>"><i
                                                        class="bi bi-pencil"></i></a></td>
                                        <td>
                                            <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this worker?')">
                                                <i class="bi bi-trash"></i></button>
                                        </td>
                                    <?php endif;
                                    echo '
                                        </tr>
                                    </form>';
                                } ?>


                                </tbody>
                            </table>
                        </div>
                    <?php
                    else:
                        echo "<h3>$zeroRecords</h3>";
                    endif;
                    if ($_SESSION['role'] == 'HRI'): ?>

                        <form method="post" action="dashboard.php?form=2">
                            <input type="hidden"
                                   value="<?php echo $department_id ?>"
                                   name="department_id">
                            <button type="submit" class="btn btn-dark">Create a new worker</button>
                        </form>
                    <?php
                    endif; ?>

                </div>
                <div class="col-md-5 col-12">
                    <h3>Tasks</h3>
                    <?php
                    //Display tasks in a table
                    if ($tasks->num_rows > 0): ?>

                    <form method="post" action="config/task_update.php" style="margin-bottom: 10vh">

                        <div class="slider">
                            <table class="table table-fixed'<?php if ($tasks->num_rows > 5) echo ' fixed-table'; ?>">
                                <thead class="table-dark">
                                <tr>
                                    <th class="col-2">Name</th>
                                    <th class="col-3">Description</th>
                                    <th class="col-3">Importance</th>
                                    <th class="col-2">Date Created</th>
                                    <th class="col-2">Completed</th>
                                    <?php
                                    if ($_SESSION['role'] == 'HRI'):?>
                                        <th class="col-2">Delete</th>
                                    <?php
                                    endif; ?>

                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = $tasks->fetch_assoc()) {
                                    if ($row["complete"] == 1)
                                        $checked = 'checked';
                                    else
                                        $checked = '';

                                    echo '
                                <tr>
                                    <td>' . $row["name"] . '</td>
                                    <td>' . $row["description"] . '</td>
                                    <td>' . $row["importance"]
                                        . '
                                    </td>
                                    <td>' . date("d-m-Y", strtotime($row['dateCreated'])) . '</td>
                                    <td><input class="form-check-input" name="completed[]" ';
                                    if ($_SESSION['role'] == 'HRO' || $_SESSION['role'] == 'Owner')
                                        echo 'disabled';

                                    echo ' type="checkbox"' . $checked . ' value="' . $row["id"] . '">
                                    </td>
                                    ';
                                    if ($_SESSION['role'] == 'HRI'): ?>

                                        <td>
                                            <a class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this task?')"
                                               href="config/task_delete.php?id=<?php echo $row["id"] ?>&dep=<?php echo $department_id ?>">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    <?php
                                    endif;
                                    echo '
                                </tr>
                                ';
                                }

                                ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="department" id="department" value="<?= $department_id ?>">

                        <?php
                        else:
                            echo "<h3>No tasks found for this department</h3>";
                        endif;
                        if ($_SESSION['role'] == 'HRI'):?>
                            <button type="button" class="btn btn-dark" data-toggle="modal"
                                    data-target="#createTaskModal"
                                    style="margin-top:14px">Create new Task
                            </button>
                            <button class="btn btn-dark float-end" type="submit" style="margin-top:14px">Update tasks
                            </button>
                        <?php
                        endif;
                        ?>
                    </form>
                </div>


                <div class=" modal fade" id="createTaskModal" tabindex="-1" role="dialog"
                        aria-labelledby="createTaskModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createTaskModalLabel">Create New Task for
                                        a <?php echo $result["name"]; ?></h5>
                                    <button type="button" class="close btn btn-dark" data-dismiss="modal"
                                            aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form class="form" method="post" action="config/task_create.php">
                                        <div>
                                            <label class="form-check-label" for="task_name">Name of the task:</label>
                                            <input class="form-control" type="text" name="task_name" id="task_name"
                                                   required>
                                        </div>

                                        <div>
                                            <label class="form-check-label" for="task_desc">Description of the
                                                task:</label>
                                            <input class="form-control" type="text" name="task_desc" id="task_desc"
                                                   required>
                                        </div>

                                        <div>
                                            <label class="form-check-label" for="task_imp">Importance of the
                                                task:</label>
                                            <select class="form-control" id="task_imp" name="task_imp" required>
                                                <option value="">Choose...</option>
                                                <option value="C-Priority">C-Priority</option>
                                                <option value="B-Priority">B-Priority</option>
                                                <option value="A-Priority">A-Priority</option>
                                            </select>
                                            <input type="hidden" name="department" id="department"
                                                   value=" <?php echo $department_id; ?> ">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                            </button>
                                            <button type="submit" class="btn btn-primary">Create Task</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog"
                     aria-labelledby="editDepartmentModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDepartmentModalLabel">
                                    Edit</h5>
                                <button type="button" class="close btn btn-dark" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="form" method="post" action="config/department_edit.php">
                                    <div>
                                        <label class="form-check-label" for="total">Edit name:</label>
                                        <input class="form-control" type="text" name="total" id="total"
                                               value="<?php echo $result["name"] ?>" required>
                                        <input type="hidden" name="department" id="department"
                                               value=" <?php echo $department_id; ?> ">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                else:
                    echo "<h3>Please select a department from the menu</h3>";
                endif; ?>
            </div>


            <div class="modal fade" id="createDepModal" tabindex="-1" role="dialog"
                 aria-labelledby="createDepModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createDepModalLabel">Create new department</h5>
                            <button type="button" class="close btn btn-dark" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="form" method="post" action="config/department_create.php">
                                <div>
                                    <label class="form-check-label" for="dep_name">Name of the department:</label>
                                    <input class="form-control" type="text" name="dep_name" id="dep_name"
                                           placeholder="Department of ..."
                                           required>
                                </div>

                                <div>
                                    <label class="form-check-label" for="dep_cost">Total cost(in 1000$):</label>
                                    <input class="form-control" type="text" name="dep_cost" id="dep_cost"
                                           required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                                    </button>
                                    <button type="submit" class="btn btn-primary">Create Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<!--adding script for sorting tables-->
<script src="config/sorting_table.js"></script>