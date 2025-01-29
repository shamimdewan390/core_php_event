<?php
include 'layout/header.php';
require_once '../classes/Auth.php';

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth();
    $post = [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "password" => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ];
    
    $result = $auth->registerUser($post);
    if ($result) {
        $_SESSION['success'] = "registration Succeful!";
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
    } else {
        header("location: " . $_SESSION['PHP_SELF']);
    }
    header("location: login.php");
    // header("location: event/index.php");
}
?>

<div class="row">
    <div class="col-md-8 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    Register
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include './layout/footer.php';
?>