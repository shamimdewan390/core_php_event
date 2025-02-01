
<?php


require_once '../../classes/Event.php';
function downloadAttendeesCSV($eventId) {

$objEvent = new Event();
    $columns = "events.id AS event_id, events.name AS event_name, attendees.id AS attendee_id, attendees.name AS attendee_name, attendees.phone";
    $maintable = "events";
    $jointype = ['INNER JOIN'];
    $joinTables = ['attendees'];
    $joinConditions = ['events.id = attendees.event_id'];
    $where = ['event_id' => $eventId];

    $event = $objEvent->join($columns, $maintable, $jointype, $joinTables, $joinConditions, $where);

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Attendee Name', 'Phone']);

    while ($eventRow = $event->fetch_assoc()) {
        fputcsv($output, [$eventRow['attendee_name'], $eventRow['phone']]);
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_' . $eventId . '.csv"');

    fclose($output);
}

if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    downloadAttendeesCSV($eventId);
}
?>