
<?php
// Assuming you've already established the $objEvent and $objAttendee objects

require_once '../../classes/Event.php';
// Function to download attendees as CSV for a specific event
function downloadAttendeesCSV($eventId) {

$objEvent = new Event();
    $columns = "events.id AS event_id, events.name AS event_name, attendees.id AS attendee_id, attendees.name AS attendee_name, attendees.phone";
    $maintable = "events";
    $jointype = ['INNER JOIN'];
    $joinTables = ['attendees'];
    $joinConditions = ['events.id = attendees.event_id'];
    $where = ['event_id' => $eventId];

    $event = $objEvent->join($columns, $maintable, $jointype, $joinTables, $joinConditions, $where);

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output the CSV header
    fputcsv($output, ['Attendee Name', 'Phone']); // Column names

    // Loop through the result set and output each attendee as a CSV row
    while ($eventRow = $event->fetch_assoc()) {
        // Print the event name only once
        fputcsv($output, [$eventRow['attendee_name'], $eventRow['phone']]);
    }

    // Set headers to prompt download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_' . $eventId . '.csv"');

    // Close the output stream
    fclose($output);
}

// Trigger the function to download CSV for a specific event
if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id']; // Get the event_id from the URL
    downloadAttendeesCSV($eventId);
}
?>