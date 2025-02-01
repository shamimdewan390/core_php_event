<?php
require_once './views/layout/header.php';

require_once __DIR__ . '/classes/Event.php';
require_once __DIR__ . '/config.php';



session_start();

if (isset($_GET['search'])) {
    $search = !empty($_GET['search']) ? trim($_GET['search']) : null;
    $searchColumn = 'name';
}

if (isset($_GET['min_capacity']) || isset($_GET['max_capacity'])) {
    $min_capacity = !empty($_GET['min_capacity']) ? trim($_GET['min_capacity']) : null;
    $max_capacity = !empty($_GET['max_capacity']) ? trim($_GET['max_capacity']) : null;
}

if (empty($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
if (empty($_POST['limit']) && empty($_GET['limit'])) {
    $limit = 6;
} elseif (isset($_POST['limit'])) {
    $limit = $_POST['limit'];
} else {
    $limit = $_GET['limit'];
}

$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'asc';
$nextSortOrder = ($sortOrder === 'asc') ? 'desc' : 'asc';


$limit = (int) $limit;
$offset = ($page - 1) * $limit;


$eventObj = new Event();
$events = $eventObj->pagination('*', 'events', $limit, $offset, $user_id, $sortColumn, $nextSortOrder, $min_capacity, $max_capacity, $searchColumn, $search);

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
                </div>

                <div class="card-body">
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class='alert alert-danger'><?= $_SESSION['error']; ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get" class="form-inline">
                        <div class="row mb-3">
                            <div class="col">
                                <input input name="search" type="text" placeholder="Search by Name" class="form-control">

                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>

                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get" class="form-inline">
                        <div class="row">
                            <div class="col">
                                <input type="text" name="min_capacity" class="form-control" placeholder="Min Capacity">
                            </div>
                            <div class="col">
                                <input type="text" name="max_capacity" class="form-control" placeholder="Max Capacity">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary">clear</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">


                    <div class="row">
                        <?php $count = 1;
                        foreach ($events as $key => $event) { ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= $event['name'] ?></h5>
                                        <p class="card-text"><?= $event['description'] ?> </p>
                                        <p class="card-text">Capacity: <?= $event['capacity'] ?> </p>
                                        <p class="card-text">Date: <?= date('d-m-Y', strtotime($event['date'])) ?> </p>
                                        <a href="<?php echo $base_url . 'views/attendee/create.php/?id=' . $event['id'] ?>" class="btn btn-info">Apply</a>

                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="card-footer">
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