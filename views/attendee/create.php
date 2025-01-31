<?php
require '../layout/header.php';
require_once '../../classes/Attendee.php';
require_once '../../classes/Event.php';

session_start(); // Ensure session is started

$errors = []; // Array to store validation errors

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post = $_POST;
    $objAttendee = new Attendee();
    $objEvent = new Event();

    // Validate Name
    if (empty($post['name'])) {
        $_SESSION['error'] = "Name is required.";
    }

    // Validate Phone
    if (empty($post['phone'])) {
        $_SESSION['error'] = "Phone number is required.";
    } elseif (!preg_match('/^\d{11}$/', $post['phone'])) {
        $_SESSION['error'] = "Phone number should be 11 digits.";
    }

    // Proceed only if there are no validation errors
    if (empty($_SESSION['error'])) {

        $event = $objEvent->find("*", 'events', ['id' => $post['event_id']]);
        $capacity = $event['capacity'];
        $attendeeCount = $objAttendee->countRows('*', 'attendees', ['event_id' => $event['id']]);

        if ($attendeeCount >= $capacity) {
            $_SESSION['info'] = "Sorry, the event is full. Registration is closed.";
        } else {
            $result = $objAttendee->insert("attendees", $post);

            if ($result == "inserted") {
                header("Location: " . $base_url . "index.php");
            }
        }
    }
} else {
    $id = $_GET['id'];
}
?>

<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <?php

            if (isset($_SESSION['info'])) {
                echo "<div class='alert alert-info'>" . $_SESSION['info'] . "</div>";
                unset($_SESSION['info']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }

            ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Apply</h3>

                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="attendeeForm">
                        <input type="hidden" name="event_id" value="<?= $id ?> ">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                            <span id="nameError" style="color:red;"></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="number" name="phone" class="form-control" id="phone" placeholder="Enter phone">
                            <span id="phoneError" style="color:red;"></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("attendeeForm").addEventListener("submit", function(event) {
        let valid = true;

        // Clear previous errors
        document.getElementById("nameError").textContent = "";
        document.getElementById("phoneError").textContent = "";

        // Get the form values
        let name = document.getElementById("name").value;
        let phone = document.getElementById("phone").value;

        // Name validation
        if (name.trim() === "") {
            document.getElementById("nameError").textContent = "Name is required.";
            valid = false;
        }

        // Phone validation
        if (phone.trim() === "") {
            document.getElementById("phoneError").textContent = "Phone number is required.";
            valid = false;
        } else if (!/^\d{11}$/.test(phone)) {
            document.getElementById("phoneError").textContent = "Phone number should be 11 digits.";
            valid = false;
        }
        if (!valid) {
            event.preventDefault();
        }
    });
</script>
<?php
require '../layout/footer.php';
?>