<?php
require '../layout/header.php';
require_once '../../classes/Event.php';
require_once __DIR__ . '/../../config.php';

$user_id = $_SESSION['user_id'];

if(!$user_id){
    header("Location: " . $base_url . "index.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $post = $_POST;
    $objEvent = new Event();
    $result = $objEvent->update("events", $post, ['id' => $id]);


    if ($result == "inserted") {
        header("Location: " . $base_url . "/views/event/index.php");
    }
} else {
    $id = $_GET['id'];
    $objEvent = new Event();
    $event = $objEvent->find("*", 'events', ['id' => $id]);
}
?>

<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Event Edit</h3>
                    <a href="<?php echo $base_url . '/views/event/create.php' ?>" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">

                        <input type="text" name="id" hidden value="<?= $event['id'] ?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="<?= $event['name'] ?>" class="form-control" id="name" placeholder="Enter name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="3"><?= $event['description'] ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="capacity">Capacity</label>
                            <input type="number" value="<?= $event['capacity'] ?>" name="capacity" class="form-control" id="capacity" placeholder="Enter capacity">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" value="<?= $event['date'] ?>" name="date" class="form-control" id="date">
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