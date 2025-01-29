<?php
require '../layout/header.php';
require_once '../../classes/Event.php';
require_once __DIR__ . '/../../config.php';

session_start();

if (empty($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
if (empty($_POST['limit']) && empty($_GET['limit'])) {
    $limit = 5;
} elseif (isset($_POST['limit'])) {
    $limit = $_POST['limit'];
} else {
    $limit = $_GET['limit'];
}
//exit;
//echo $limit;exit;

$limit = (int) $limit;
$offset = ($page - 1) * $limit;
// echo $limit;exit;
$eventObj = new Event();
$events = $eventObj->pagination('*', 'events', $limit, $offset);

$totalRowCount = $eventObj->index("*", "events")->num_rows;
$totalPage = ceil($totalRowCount / $limit);
?>

<style>
    .text-center{
        float: right;
    }
    .pagination {
        display: inline-block;
        float: right;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
    }

    .pagination a.active {
        background-color: lightsalmon;
        color: white;
        border-radius: 5px;
    }

    .pagination a:hover:not(.active) {
        background-color: #ddd;
        border-radius: 5px;
    }
    a.btn.btn-primary.float-right.col-2{
        float: right;
    }
</style>
<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Event List</h3>
                    <a href="<?php echo $base_url. '/views/event/create.php' ?>" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">date</th>
                                <th scope="col">Capacity</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1;
                            foreach ($events as $key => $event) { ?>
                                <tr>
                                    <th><?= $count++; ?></th>
                                    <td><?= $event['name'] ?> </td>
                                    <td><?= $event['description'] ?> </td>
                                    <td><?= $event['date'] ?> </td>
                                    <td><?= $event['capacity'] ?> </td>
                                    <td>
                                        <a href="<?php echo $base_url. 'views/event/edit.php/?id=' . $event['id'] ?>" class="btn btn-primary">Edit</a>

                                        <a href="<?php echo $base_url. 'views/event/delete.php/?id=' . $event['id'] ?>" onclick=" return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
                                        <button type="button" onclick="myfunc(this)" value="<?= $book['id'] ?>" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="float-right">
    <div class="pagination">
        <a href="#">&laquo;</a>

        <?php $i = 1;
        while ($i <= $totalPage) { ?>
            <a href="<?= $_SERVER["PHP_SELF"] . '?page=' . $i . '&limit=' . $limit ?>" class="<?= ($page == $i) ? 'active' : '' ?>"><?= $i ?></a>
    <?php $i++;
} ?>
        <a href="#">&raquo;</a>
    </div>

</div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layout/footer.php';
?>