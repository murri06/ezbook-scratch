<?php

// Connecting to db and starting session
include 'inc/header.php';
$error = '';

// Checking for a post to process form data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Receiving data from the post form and sanitizing it
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Query the database
    $result = $conn->query("SELECT * FROM pass_syst WHERE login = '$login'");
    $user = $result->fetch_assoc();

    if (isset($user)) {

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {

            // Start a session and store the user information
            $_SESSION['role'] = $user['role'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['name'] = $user['name'];

            // Redirect to a protected page
            header('Location: dashboard.php');
            exit;

        } else {
            // Display an error message
            $error = 'Invalid username or password.';
        }
    } else {
        // Display an error message
        $error = 'Invalid username or password.';
    }
}
?>

    <main class="container-fluid col-md-6 my-auto d-flex flex-column justify-content-center align-items-center"
          style="min-height: 80vh">
        <form method="post">
            <div class="mb-3">
                <label class="form-label" for="login">Login:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input class="form-control" type="text" id="login" name="login" placeholder="Enter your username"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password">Password:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter your password"
                           required>
                    <span class="input-group-text" onclick="togglePassword()"><i id="eyePass"
                                                                                 class="bi bi-eye-slash"></i></span>
                </div>
            </div>

            <div class="mb-3">
                <p class="text-danger"><?php echo $error; ?></p>
                <button type="submit" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Login</button>
            </div>

        </form>
    </main>


    <script>
        // Creating a function for showing password
        function togglePassword() {

            var passwordInput = document.getElementById("password");
            var eye = document.getElementById("eyePass");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eye.className = "bi bi-eye";
            } else {
                passwordInput.type = "password";
                eye.className = "bi bi-eye-slash";

            }
        }

    </script>


<?php
include 'inc\footer.php';
?>