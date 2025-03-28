<?php

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