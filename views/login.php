<?php
session_start();
require_once './layout/header.php';
require_once '../classes/Auth.php';

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']); // Clear the success message after showing it
}

if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Clear the error message after showing it
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $authObj = new Auth();
    $user = $authObj->login('*', 'users', ["email" => $_POST["email"]], $_POST['password']);

    if (password_verify($_POST['password'], $user->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['email'] = $user->email;
        header("location: event/index.php");
    } else {
        header("location: " . $_SESSION['PHP_SELF']);
    }
}
?>

<div class="row">
    <div class="col-md-8 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    Login
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                            <span id="emailError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            <span id="passwordError" style="color:red;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        let valid = true;

        // Clear previous errors
        document.getElementById("emailError").textContent = "";
        document.getElementById("passwordError").textContent = "";

        // Get form values
        let email = document.getElementById("email").value;
        let password = document.getElementById("password").value;

        // Email validation
        if (email.trim() === "") {
            document.getElementById("emailError").textContent = "Email is required.";
            valid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) { 
            document.getElementById("emailError").textContent = "Please enter a valid email address.";
            valid = false;
        }

        // Password validation
        if (password.trim() === "") {
            document.getElementById("passwordError").textContent = "Password is required.";
            valid = false;
        } else if (password.length < 5) { // Password length validation
            document.getElementById("passwordError").textContent = "Password must be at least 5 characters long.";
            valid = false;
        }

        // Prevent form submission if validation fails
        if (!valid) {
            event.preventDefault();
        }
    });
</script>
<?php
require_once './layout/footer.php';
?>