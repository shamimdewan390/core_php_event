<?php
require '../layout/header.php';
require_once '../../classes/Event.php'; 


if($_SERVER["REQUEST_METHOD"] == "POST") {
    $post = $_POST;
    $events = new Event();
    $result = $events->insert("events", $post);
    if($result == "inserted"){
        header("Location: index.php");
    }
} else {
    // header("Location: create.php");
}
?>

<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Add Event</h3>
                    <a href="create.php" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" name="capacity" class="form-control" id="capacity" placeholder="Enter capacity">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" id="date">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layout/footer.php';
?>