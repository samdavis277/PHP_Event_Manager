<?php
/*
File: `new-event-handler.php` (20%)

Functionality: Accepts and processes data from POST request, ensuring all required fields are submitted and data is validated and the correct data type for the given field. Never trust user input! Generate a unique ID for the event, and append the ID and event data as a new line in the events.txt file. Ensure you validate all input to escape or remove | characters. These are used as separators in the line, therefore they must NOT appear in the content. The user should then be redirect to index.php and a notice stating “Event <NAME> Successfully Created” should be displayed, or similar.

Instructions:
   - Read the POST data from the form fields: name, description, start, and end.
   - Validate the data:
      - Ensure name, start, and end are not empty.
	  - Ensure the end datetime is after the start datetime.
      - Use DateTime::createFromFormat() to validate date/time fields.
      - Sanitize all input data to prevent security issues.
   - Generate a unique event ID using uniqid().
   - Remove or escape any | characters in user input to maintain the events.txt file's structure.
   - Append the new event as a new line in events.txt in the format:
	 <id>|<name>|<description>|<start>|<end>.
   - Redirect back to index.php with a success message set via GET attribute.
*/

session_start();  // Start the session to store error messages and form data

// Function to sanitize user input
function sanitize_input($data) {
    $data = str_replace("|", "", $data);
    return htmlspecialchars(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $eventName = sanitize_input($_POST["EventName"]);
    $startDateTime = sanitize_input($_POST["StartDateTime"]);
    $endDateTime = sanitize_input($_POST["EndDateTime"]);
    $description = isset($_POST["Description"]) ? sanitize_input($_POST["Description"]) : "";

    // Store the submitted data in the session for retention
    $_SESSION["eventData"] = [
        "EventName" => $eventName,
        "StartDateTime" => $startDateTime,
        "EndDateTime" => $endDateTime,
        "Description" => $description
    ];

    // Validate required fields
    if (empty($eventName)) {
        $_SESSION["errorMessage"] = "Please enter an event name";
        header("Location: new-event.php");
        exit;
    } elseif (empty($startDateTime) || empty($endDateTime)) {
        $_SESSION["errorMessage"] = "Please enter a start and end date";
        header("Location: new-event.php");
        exit;
    }

    // Validate datetime format
    $startDateTimeObj = DateTime::createFromFormat("Y-m-d\TH:i", $startDateTime);
    $endDateTimeObj = DateTime::createFromFormat("Y-m-d\TH:i", $endDateTime);

    if (!$startDateTimeObj || !$endDateTimeObj) {
        $_SESSION["errorMessage"] = "Invalid date/time format";
        header("Location: new-event.php");
        exit;
    }

    if ($endDateTimeObj <= $startDateTimeObj) {
        $_SESSION["errorMessage"] = "End date/time must be after start date/time";
        header("Location: new-event.php");
        exit;
    }

    $startDateTimeFormatted = $startDateTimeObj->format("Y-m-d H:i:s");
    $endDateTimeFormatted = $endDateTimeObj->format("Y-m-d H:i:s");

    $eventID = uniqid();
    $newEvent = $eventID . "|" . $eventName . "|" . $description . "|" . $startDateTimeFormatted . "|" . $endDateTimeFormatted . "\n";

    $eventsFile = "events.txt";
    if (file_put_contents($eventsFile, $newEvent, FILE_APPEND) === false) {
        $_SESSION["errorMessage"] = "Error saving event";
        header("Location: new-event.php");
        exit;
    }

    $_SESSION["successMessage"] = "Event '$eventName' successfully created!";
    unset($_SESSION["eventData"]);  // Clear session data after successful creation
    header("Location: index.php?message=" . urlencode(($_SESSION["successMessage"])));
    exit;
}
?>