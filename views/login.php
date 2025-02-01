<?php
session_start();
require_once './layout/header.php';
require_once '../classes/Auth.php';

// Display success message
if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}

// Display error message
if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $authObj = new Auth();
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $user = $authObj->login('*', 'users', ["email" => $email], $password);

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['email'] = $user->email;
        header("location: event/index.php");
        header("Location: " . $base_url . "views/event/index.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("location: login.php");

        exit();
    }
}
?>

<div class="row">
    <div class="col-md-8 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm">
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
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value.trim();

        // Email validation
        if (email === "") {
            document.getElementById("emailError").textContent = "Email is required.";
            valid = false;
        } else if (!/\S+@\S+\.\S+/.test(email)) { 
            document.getElementById("emailError").textContent = "Please enter a valid email address.";
            valid = false;
        }

        // Password validation
        if (password === "") {
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
