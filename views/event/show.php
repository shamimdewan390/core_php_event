<?php
session_start();
require '../layout/header.php';
require_once '../../classes/Attendee.php';
require_once '../../classes/Event.php';


$user_id = $_SESSION['user_id'];

if(!$user_id){
    header("Location: " . $base_url . "index.php");
}

$id = $_GET['id'];
$objAttendee = new Attendee();
$objEvent = new Event();
$columns = "events.id AS event_id, events.name AS event_name, attendees.id AS attendee_id, attendees.name AS attendee_name, attendees.phone";
$maintable = "events";
$jointype = ['INNER JOIN'];
$joinTables = ['attendees'];
$joinConditions = ['events.id = attendees.event_id'];
$where = ['event_id' => $id];
$event = $objEvent->join($columns, $maintable, $jointype, $joinTables, $joinConditions, $where);
$currentEventId = null;

?>

<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card-body">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Attendee List: </h3>
                    <?php

                    ?>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($eventRow = $event->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $eventRow['attendee_name'] ?> </td>
                                    <td><?= $eventRow['phone'] ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require '../layout/footer.php';
?>