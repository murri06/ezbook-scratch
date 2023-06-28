<?php
// Creating variables
$intExpErr = $extExpErr = $wageMonthErr = $res = '';

if (isset($_POST['submit'])) {

    if (is_numeric($_POST['intExp']))
        $intExp = $_POST['intExp'];
    else
        $intExpErr = 'Must be a number!';

    if (is_numeric($_POST['extExp']))
        $extExp = $_POST['extExp'];
    else
        $extExpErr = 'Must be a number!';

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST['middleName']))
        $middleName = filter_input(INPUT_POST, 'middleName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    else
        $middleName = '-';

    $dob = $_POST['dob'];
    $sex = $_POST['sex'];
    $department_id = $_POST['department_id'];
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $grade = $_POST['grade'];
    $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $wageMonth = $_POST['wageMonth'];

    if (empty($intExpErr) && empty($extExpErr)) {
        if (isset($_POST["id"])) {
            $id = $_POST["id"];
            $sql = "UPDATE hr_staff SET name = '$name', surname = '$surname', middleName = '$middleName', dob = '$dob', department_id = '$department_id', location = '$location',
                    intExp = '$intExp', extExp = '$extExp', grade = '$grade', position = '$position', wageMonth = '$wageMonth' WHERE id = '$id'";
        } else
            $sql = "INSERT INTO hr_staff (name, surname, middleName, dob, sex, department_id, location, intExp, extExp, grade, position, wageMonth) 
                VALUES ('$name', '$surname', '$middleName', '$dob', '$sex', '$department_id', '$location', '$intExp', '$extExp', '$grade', '$position', '$wageMonth')";
        //if request is successful, redirecting back to department page
        if ($conn->query($sql)) {
            header('Location: dashboard.php?form=1&department=' . $department_id);
            exit();
        }
    }
}

//fill in form for editing staff
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM hr_staff WHERE id = $id";
    $result = $conn->query($sql);
    $_POST = $result->fetch_assoc();
}

if (isset($_GET['res']))
    $res = '<h3>Success! </h3> ';
?>

<form method="post" class="w-50 m-auto h-75" style="margin-bottom: 80px">
    <h2 class="text-center mb-4"><?php if (isset($_GET["id"])) echo "Edit worker " . $_POST['name'] . ' ' . $_POST['surname']; else echo 'Fill a form for a new Worker'; ?></h2>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $_POST['name'] ?? '' ?>"
                   required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="surname" class="form-label">Surname:</label>
            <input type="text" class="form-control" id="surname" name="surname"
                   value="<?php echo $_POST['surname'] ?? '' ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="middleName" class="form-label">Middle Name:</label>
            <input type="text" class="form-control" id="middleName" name="middleName"
                   value="<?php echo $_POST['middleName'] ?? '' ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label for="dob" class="form-label">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $_POST['dob'] ?? '' ?>"
                   required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="sex" class="form-label">Sex:</label>
            <select class="form-control" id="sex" name="sex" required>
                <option value="">Choose...</option>
                <?php
                $options = array("Male", "Female", "Other");
                foreach ($options as $option) {
                    echo "<option value='$option'";
                    if (isset($_POST['sex']) && $_POST['sex'] == $option) {
                        echo ' selected="selected"';
                    }
                    echo ">$option</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label for="department_id" class="form-label">Department:</label>
            <select class="form-control" id="department_id" name="department_id" required>
                <option value="">Choose...</option>
                <?php
                //receiving data from department database to create a dropdown menu for department choosing
                $sql = "SELECT name, id FROM departments";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '"';
                    if (isset($_POST["department_id"]) && $_POST["department_id"] == $row["id"])
                        echo ' selected="selected"';

                    echo '> ' . $row["name"] . '</option>';
                }
                ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="location" class="form-label">Location:</label>
            <input type="text" class="form-control" id="location" name="location"
                   value="<?php echo $_POST['location'] ?? '' ?>" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="intExp" class="form-label">Internal
                Experience(years): <?php echo '<span class="text-danger">' . $intExpErr . '</span>' ?></label>
            <input type="text" class="form-control" id="intExp" name="intExp"
                   value="<?php echo $_POST['intExp'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="extExp" class="form-label">External
                Experience(years): <?php echo '<span class="text-danger">' . $extExpErr . '</span>' ?></label>
            <input type="text" class="form-control" id="extExp" name="extExp"
                   value="<?php echo $_POST['extExp'] ?? '' ?>" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="grade" class="form-label">Grade(1 to 10):</label>
            <input type="number" class="form-control" id="grade" name="grade" min="1" max="10"
                   value="<?php echo $_POST['grade'] ?? '' ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="position" class="form-label">Position:</label>
            <input type="text" class="form-control" id="position" name="position" min="1" max="10"
                   value="<?php echo $_POST['position'] ?? '' ?>" required>

        </div>

        <div class="col-md-6 mb-3">
            <label for="wageMonth" class="form-label">Wage per month:</label>
            <input type="number" class="form-control" id="wageMonth" name="wageMonth"
                   value="<?php echo $_POST['wageMonth'] ?? '' ?>" required>
            <?php if (isset($_GET["id"])) echo ' <input type="hidden" id="id" name="id" value="' . $id . '">' ?>
        </div>
        <?php echo $res ?>
    </div>

    <button style="margin-bottom: 80px" class="btn btn-dark" type="submit"
            name="submit"><?php if (isset($_GET["id"])) echo 'Submit'; else echo 'Create'; ?></button>
    <a style="margin-bottom: 80px" class="btn btn-secondary"
       href="dashboard.php?form=1&department=<?= $_POST["department_id"] ?>">Cancel</a>
</form>