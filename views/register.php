<?php
session_start();
include 'layout/header.php';
require_once '../classes/Auth.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth();
    
    // Validate Name
    if (empty($_POST['name'])) {
        $errors['name'] = "Name is required.";
    }

    // Validate Email
    if (empty($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate Password
    if (empty($_POST['password'])) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $post = [
            "name" => trim($_POST['name']),
            "email" => trim($_POST['email']),
            "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ];

        $result = $auth->registerUser($post);

        if ($result) {
            $_SESSION['success'] = "Registration successful!";
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['email'] = $result['email'];
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registration failed. Email may already be in use.";
            header("Location: register.php");
            exit();
        }
    }
}
?>

<div class="row">
    <div class="col-md-8 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class='alert alert-danger'><?= $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="registerForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            <span class="text-danger" id="nameError"><?= $errors['name'] ?? '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <span class="text-danger" id="emailError"><?= $errors['email'] ?? '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                            <span class="text-danger" id="passwordError"><?= $errors['password'] ?? '' ?></span>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("registerForm").addEventListener("submit", function(event) {
        let valid = true;

        // Clear previous error messages
        document.getElementById("nameError").textContent = "";
        document.getElementById("emailError").textContent = "";
        document.getElementById("passwordError").textContent = "";

        // Get form values
        let name = document.getElementById("name").value.trim();
        let email = document.getElementById("email").value.trim();
        let password = document.getElementById("password").value;

        // Name validation
        if (name === "") {
            document.getElementById("nameError").textContent = "Name is required.";
            valid = false;
        }

        // Email validation
        if (email === "") {
            document.getElementById("emailError").textContent = "Email is required.";
            valid = false;
        } else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
            document.getElementById("emailError").textContent = "Invalid email format.";
            valid = false;
        }

        // Password validation
        if (password === "") {
            document.getElementById("passwordError").textContent = "Password is required.";
            valid = false;
        } else if (password.length < 6) {
            document.getElementById("passwordError").textContent = "Password must be at least 6 characters.";
            valid = false;
        }

        // Prevent form submission if validation fails
        if (!valid) {
            event.preventDefault();
        }
    });
</script>

<?php include 'layout/footer.php'; ?>
