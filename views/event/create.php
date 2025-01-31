<?php
require '../layout/header.php';
require_once '../../classes/Event.php';


session_start();

$user_id = $_SESSION['user_id'];

if (!$user_id) {
    header("Location: " . $base_url . "index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post = $_POST; // Get all form data
    $post['user_id'] = $user_id; // Add user_id

    // Validate Name
    if (empty($post['name'])) {
        $_SESSION['error'] = "Name is required.";
    }

    // Validate Description
    if (empty($post['description'])) {
        $_SESSION['error'] = "Description is required.";
    }

    // Validate Capacity
    if (empty($post['capacity'])) {
        $_SESSION['error'] = "Capacity is required.";
    } elseif (!is_numeric($post['capacity']) || $post['capacity'] <= 0) {
        $_SESSION['error'] = "Capacity must be a positive number.";
    }

    // Validate Date
    if (empty($post['date'])) {
        $_SESSION['error'] = "Date is required.";
    } elseif (strtotime($post['date']) < strtotime(date("Y-m-d"))) {
        $_SESSION['error'] = "The event date cannot be in the past.";
    }

    // If there are no errors, insert into the database
    if (empty($_SESSION['error'])) {
        $events = new Event();
        $result = $events->insert("events", $post);

        if ($result == "inserted") {
            header("Location: index.php");
            exit();
        }
    }
}
?>


<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <?php

            if (isset($_SESSION['error'])) {
                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                unset($_SESSION['error']);
            }

            ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Add Event</h3>
                    <a href="create.php" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" id="eventForm">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                            <span id="nameError" style="color:red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                            <span id="descriptionError" style="color:red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" name="capacity" class="form-control" id="capacity" placeholder="Enter capacity">
                            <span id="capacityError" style="color:red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" id="date">
                            <span id="dateError" style="color:red;"></span>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("eventForm").addEventListener("submit", function(event) {
        let valid = true;

        // Clear previous errors
        document.getElementById("nameError").textContent = "";
        document.getElementById("descriptionError").textContent = "";
        document.getElementById("capacityError").textContent = "";
        document.getElementById("dateError").textContent = "";

        // Get form values
        let name = document.getElementById("name").value;
        let description = document.getElementById("description").value;
        let capacity = document.getElementById("capacity").value;
        let date = document.getElementById("date").value;

        // Name validation
        if (name.trim() === "") {
            document.getElementById("nameError").textContent = "Name is required.";
            valid = false;
        }

        // Description validation
        if (description.trim() === "") {
            document.getElementById("descriptionError").textContent = "Description is required.";
            valid = false;
        }

        // Capacity validation
        if (capacity.trim() === "") {
            document.getElementById("capacityError").textContent = "Capacity is required.";
            valid = false;
        } else if (capacity <= 0) {
            document.getElementById("capacityError").textContent = "Capacity must be a positive number.";
            valid = false;
        }

        // Date validation
        if (date.trim() === "") {
            document.getElementById("dateError").textContent = "Date is required.";
            valid = false;
        }

        // Prevent form submission if validation fails
        if (!valid) {
            event.preventDefault();
        }
    });
</script>

<?php
require '../layout/footer.php';
?>