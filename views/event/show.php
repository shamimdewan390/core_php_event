<?php
require '../layout/header.php';
?>

<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Event List</h3>
                    <a href="create.php" class="btn btn-primary">+ Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <td>new event</td>
                            </tr>
                            <tr>
                                <th scope="col">Description</th>
                                <td>Description Description</td>
                            </tr>
                            <tr>
                                <th scope="col">Capacity</th>
                                <td>20</td>
                            </tr>
                            <tr>
                                <th scope="col">Date</th>
                                <td>20/01/25</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layout/footer.php';
?>