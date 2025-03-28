<?php

session_start(); // Start the session to access session variables

function sanitizeInput($input) {
    // Remove | characters and trim leading/trailing whitespace
    $input = str_replace("|", "", $input);
    return htmlspecialchars(trim($input));
}

// Retrieve POST data
$eventID = isset($_POST["EventID"]) ? $_POST["EventID"] : null;
$eventName = isset($_POST["EventName"]) ? $_POST["EventName"] : null;
$startDateTime = isset($_POST["StartDateTime"]) ? $_POST["StartDateTime"] : null;
$endDateTime = isset($_POST["EndDateTime"]) ? $_POST["EndDateTime"] : null;
$description = isset($_POST["Description"]) ? $_POST["Description"] : null;

$_SESSION["formData"] = [
    "EventName" => $eventName,
    "StartDateTime" => $startDateTime,
    "EndDateTime" => $endDateTime,
    "Description" => $description
];

// $_SESSION["formData"] = $formData; // Store form data in session

if (!$eventID || !$eventName) {
    $_SESSION["errorMessage"] = "Please enter an event name";
    header("Location: edit-event.php?EventID=$eventID");
    exit;
}

if (empty($startDateTime) || empty($endDateTime)) {
    $_SESSION["errorMessage"] = "Please enter a start and end date";
    header("Location: edit-event.php?EventID=" . $eventID);
    exit;
}

if (strtotime($startDateTime) >= strtotime($endDateTime)) {
    $_SESSION["errorMessage"] = "End Date/Time must be after Start Date/Time.";
    header("Location: edit-event.php?EventID=" . $eventID);
    exit;
}

// Validate datetime format
$startDateTimeObj = DateTime::createFromFormat("Y-m-d\TH:i", $startDateTime);
$endDateTimeObj = DateTime::createFromFormat("Y-m-d\TH:i", $endDateTime);

if (!$startDateTimeObj || !$endDateTimeObj) {
    $_SESSION["errorMessage"] = "Invalid date/time format.";
    header("Location: edit-event.php?EventID=$eventID");
    exit;
}

if ($endDateTimeObj <= $startDateTimeObj) {
    $_SESSION["errorMessage"] = "End date/time must be after start date/time.";
    header("Location: edit-event.php?EventID=$eventID");
    exit;
}

$startDateTimeFormatted = $startDateTimeObj->format("Y-m-d H:i:s");
$endDateTimeFormatted = $endDateTimeObj->format("Y-m-d H:i:s");

$eventName = sanitizeInput($eventName);
$description = sanitizeInput($description);

// Read the events file and update the corresponding event
$eventsFile = "events.txt";
$events = file($eventsFile, FILE_IGNORE_NEW_LINES);
$updatedEvents = [];
$eventUpdated = false;

foreach ($events as $event) {
    $eventParts = explode("|", $event);
    if ($eventParts[0] === $eventID) {
        // Update event with new data
        $eventParts[1] = $eventName;
        $eventParts[3] = $startDateTimeFormatted;
        $eventParts[4] = $endDateTimeFormatted;
        $eventParts[2] = $description;
        $updatedEvents[] = implode("|", $eventParts);
        $eventUpdated = true;
    } else {
        $updatedEvents[] = $event;
    }
}

if ($eventUpdated) {
    // Write the updated data back to the file
    file_put_contents($eventsFile, implode("\n", $updatedEvents) . "\n");
    $_SESSION["successMessage"] = "Event '$eventName' successfully updated."; // Success message
    unset($_SESSION["formData"]);  // Clear session data after successful creation
    header("Location: index.php");
    exit;
} else {
    $_SESSION["errorMessage"] = "Event not found."; // Error message
}

header("Location: edit-event.php?EventID=$eventID"); // Redirect back to the edit page
exit;