<?php

// Creating variables
$login = $password = $role = $res = $name = '';
$loginErr = $passwordErr = $roleErr = $nameErr = '';
$note = 'Create a new user';

//checking for a POST request to use filled form
if (isset($_POST['submit'])) {

    //check if login is set and sanitize input
    if (empty($_POST['login']))
        $loginErr = 'Login must be set!';
    else
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($_GET['id'])) {
        $sql = "SELECT login FROM pass_syst WHERE login = '$login'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0)
            $loginErr = 'There is another account with same login!';
    }


    //check if password is set and hashing it
    if (empty($_POST['password']))
        $passwordErr = 'Password must be set!';
    else
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //check if name is set and sanitize input
    if (empty($_POST['name']))
        $nameErr = 'Name must be set!';
    else
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //check if role is set
    if (empty($_POST['role'])) {
        $roleErr = 'Role must be set!';
    } else {
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //creating query to insert data into database
    if (empty($loginErr) && empty($passwordErr) && empty($roleErr) && empty($nameErr)) {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "UPDATE pass_syst SET login = '$login', password = '$password', name = '$name', role = '$role' WHERE id = '$id'";
        } else
            $sql = "INSERT INTO pass_syst (login, password, name, role) VALUES ('$login', '$password', '$name', '$role')";


        //if request is successful, redirecting to prevent reusing POST after reloading
        if ($conn->query($sql)) {
            header('Location: dashboard.php?res=1');
            exit();
        }
    }
}
//checking for a result using GET data
if (isset($_GET['res']))
    $res = 'Success!';

//receiving data for table
$sql = "SELECT * FROM pass_syst";
$table = $conn->query($sql);

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM pass_syst WHERE id = $id";
    $result = $conn->query($sql);
    $_POST = $result->fetch_assoc();
    $note = 'Edit user ' . $_POST['name'];
}


?>
<div class="container-fluid row">

    <div class="col-md-6">
        <h2 class="d-flex align-items-center justify-content-center">List of users</h2>
        <?php if ($table->num_rows > 0) {
            echo '<div class="slider d-flex align-items-baseline justify-content-center"><table style="max-width: 30vw" class="table table-fixed ';
            if ($table->num_rows > 10) echo ' fixed-table';
            echo '">';
            echo '<thead class="table-dark"><tr>
                            <th class="col-4">Name</th>
                            <th class="col-3">Login</th>
                            <th class="col-3">Role</th>
                            <th class="col-1">Edit</th>
                            <th class="col-1">Delete</th>
                            </tr></thead><tbody>';
            while ($row = $table->fetch_assoc()) {
                echo '<form method="post" action="config/account_delete.php">
                          <input type="hidden" id="id" name="id" value="' . $row["id"] . '"><tr>
                          <td>' . $row["name"] . '</td>
                          <td>' . $row["login"] . '</td>
                          <td>' . $row["role"] . '</td>
                          <td><a class="btn btn-secondary" href="dashboard.php?id=' . $row["id"] . '"><i class="bi bi-pencil"></i></a></td>
                          <td><button type="submit" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this user?\')"><i class="bi bi-trash"></i></button></td>
                          </tr></form>';
            }

            echo '</tbody></table></div>';

        } else echo 'There is no records in database.'


        ?>

    </div>

    <div class="h-75 col-md-6 my-auto d-flex flex-column justify-content-center align-items-center">
        <h2 class="d-flex align-items-center justify-content-center"><?php echo $note ?></h2>
        <form method="POST" class="w-75 mt-4">
            <div class="mb-3">
                <label for="login" class="form-label">Login:</label>
                <input type="text" class="form-control <?php echo !$loginErr ?: 'is-invalid'; ?>" id="login"
                       name="login" value="<?php echo $_POST['login'] ?? '' ?>"
                       placeholder="Login" autocomplete="off">
                <div class="invalid-feedback">
                    <?php echo $loginErr ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Name and surname:</label>
                <input type="text" class="form-control <?php echo !$nameErr ?: 'is-invalid'; ?>" id="name"
                       name="name" value="<?php echo $_POST['name'] ?? '' ?>"
                       placeholder="Name">
                <div class="invalid-feedback">
                    <?php echo $nameErr ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password:</label>
                <input type="text" class="form-control <?php echo !$passwordErr ?: 'is-invalid'; ?>" id="password"
                       name="password" placeholder="Password">
                <div class="invalid-feedback">
                    <?php echo $passwordErr ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="role">Role:</label>
                <select class="form-control <?php echo !$roleErr ?: 'is-invalid'; ?>" id="role" name="role" required>
                    <option value="">Role...</option>
                    <?php $options = array("HRI", "HRO", "MSEI", "MSEO", "Owner");
                    foreach ($options as $option) {
                        echo "<option value='$option'";
                        if (isset($_POST['role']) && $_POST['role'] == $option) {
                            echo ' selected="selected"';
                        }
                        echo ">$option</option>";
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    <?php echo $roleErr ?>
                </div>
                <?php echo $res ?>
            </div>

            <div class="mb-3">
                <input style="margin-bottom: 80px" type="submit" name="submit" value="Send" class="btn btn-dark">
                <?php if (isset($_GET["id"])) echo '<a style="margin-bottom: 80px" class= "btn btn-secondary" href="dashboard.php">Clear</a>'; ?>

            </div>

        </form>
    </div>
</div>
<script src="config/sorting_table.js"></script>