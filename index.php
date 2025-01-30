<?php
require_once './views/layout/header.php';

require_once 'classes/Event.php';
require_once 'config.php';


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
                            <?php $count = 1;
                            foreach ($events as $key => $event) { ?>
                                <tr>
                                    <!-- <th><?= $event['id'] ?></th> -->
                                    <td><?= $event['name'] ?> </td>
                                    <td><?= $event['description'] ?> </td>
                                    <td><?= $event['date'] ?> </td>
                                    <td><?= $event['capacity'] ?> </td>
                                    <td>
                                        <a href="<?php echo $base_url . 'views/attendee/create.php/?id=' . $event['id'] ?>" class="btn btn-info">Apply</a>

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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once './views/layout/footer.php';
?>