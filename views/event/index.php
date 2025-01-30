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

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';
$nextSortOrder = ($sortOrder === 'asc') ? 'desc' : 'asc';


$limit = (int) $limit;
$offset = ($page - 1) * $limit;

$eventObj = new Event();
$events = $eventObj->pagination('*', 'events', $limit, $offset, $sortColumn, $nextSortOrder);

$totalRowCount = $eventObj->index("*", "events")->num_rows;
$totalPage = ceil($totalRowCount / $limit);
?>



<style>
    .text-center {
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

    a.btn.btn-primary.float-right.col-2 {
        float: right;
    }
</style>
<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Event List</h3>
                    <a href="<?php echo $base_url . '/views/event/create.php' ?>" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <a href="?sort=name&order=<?= $nextSortOrder ?>">
                                        Name <?= ($sortColumn === 'name' && $sortOrder === 'asc') ? '⬆️' : '⬇️' ?>
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="?sort=description&order=<?= $nextSortOrder ?>">
                                        Description <?= ($sortColumn === 'description' && $sortOrder === 'asc') ? '⬆️' : '⬇️' ?>
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="?sort=date&order=<?= $nextSortOrder ?>">
                                        Date <?= ($sortColumn === 'date' && $sortOrder === 'asc') ? '⬆️' : '⬇️' ?>
                                    </a>
                                </th>
                                <th scope="col">
                                    <a href="?sort=capacity&order=<?= $nextSortOrder ?>">
                                        Capacity <?= ($sortColumn === 'capacity' && $sortOrder === 'asc') ? '⬆️' : '⬇️' ?>
                                    </a>
                                </th>

                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event) { ?>
                                <tr>
                                    <td><?= $event['name'] ?></td>
                                    <td><?= $event['description'] ?></td>
                                    <td><?= $event['date'] ?></td>
                                    <td><?= $event['capacity'] ?></td>
                                    <td>
                                        <a href="<?= $base_url . 'views/event/edit.php?id=' . $event['id'] ?>" class="btn btn-primary">Edit</a>
                                        <a href="<?= $base_url . 'views/event/show.php?id=' . $event['id'] ?>" class="btn btn-primary">Show</a>
                                        <a href="downloadAttendees.php?event_id=<?= $event['id'] ?>" class="btn btn-success">Download Attendees CSV</a>
                                        <a href="<?= $base_url . 'views/event/delete.php?id=' . $event['id'] ?>" onclick=" return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
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