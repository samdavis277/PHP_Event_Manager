<?php

// Start the session to access session variables
session_start();

// Retrieve the EventID from the URL
$eventID = isset($_GET["EventID"]) ? $_GET["EventID"] : null;
$message = isset($_SESSION["message"]) ? $_SESSION["message"] : ""; // Get message from session if exists
$formData = isset($_SESSION["formData"]) ? $_SESSION["formData"] : []; // Get form data from session if exists

if ($eventID === null) {
    // If no EventID is provided, redirect to index.php with an error message
    header("Location: index.php?message=Event ID is missing.");
    exit;
}

// Read the events.txt file and find the event with the matching ID
$eventsFile = "events.txt";
$eventFound = false;
$eventData = null;

if (file_exists($eventsFile)) {
    $events = file($eventsFile, FILE_IGNORE_NEW_LINES); // Read all lines into an array
    foreach ($events as $event) {
        $eventParts = explode("|", $event); // Split the event data by '|'
        if ($eventParts[0] === $eventID) {
            $eventFound = true;
            $eventData = $eventParts;
            break;
        }
    }
}

if (!$eventFound) {
    // If the event is not found, redirect to index.php with an error message
    header("Location: index.php?message=Event not found.");
    exit;
}

// Extract the event details (ID, name, description, start and end datetime)
list($eventID, $eventName, $description, $startDateTime, $endDateTime) = $eventData;

// Check if there are form errors or data to repopulate form fields
$eventName = isset($formData["EventName"]) ? $formData["EventName"] : $eventName;
$startDateTime = isset($formData["StartDateTime"]) ? $formData["StartDateTime"] : $startDateTime;
$endDateTime = isset($formData["EndDateTime"]) ? $formData["EndDateTime"] : $endDateTime;
$description = isset($formData["Description"]) ? $formData["Description"] : $description;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Edit Event</h1>

                <!-- Display error message if it exists -->
        <?php if (isset($_SESSION['errorMessage'])): ?>
            <div class="bg-red-200 text-black p-4 rounded mb-6">
                <strong>Error: </strong><?php echo $_SESSION['errorMessage']; ?>
            </div>
            <?php unset($_SESSION['errorMessage']); ?>
        <?php endif; ?>

        <!-- Display success message if it exists -->
        <?php if (isset($_SESSION['successMessage'])): ?>
            <div class="bg-green-200 text-green-800 p-4 rounded mb-6">
                <strong>Success: </strong><?php echo $_SESSION['successMessage']; ?>
            </div>
            <?php unset($_SESSION['successMessage']); ?>
        <?php endif; ?>

        <!-- Event Edit Form -->
        <form action="edit-event-handler.php" method="POST" class="bg-white p-6 rounded shadow-sm">
            <!-- Hidden field for EventID -->
            <input type="hidden" name="EventID" value="<?= htmlspecialchars($eventID); ?>">

            <div class="mb-4">
                <label for="EventName" class="block text-sm font-semibold text-gray-700">Event Name</label>
                <input type="text" id="EventName" name="EventName" value="<?= htmlspecialchars($eventName); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="StartDateTime" class="block text-sm font-semibold text-gray-700">Start Date/Time</label>
                <input type="datetime-local" id="StartDateTime" name="StartDateTime" value="<?= htmlspecialchars($startDateTime); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="EndDateTime" class="block text-sm font-semibold text-gray-700">End Date/Time</label>
                <input type="datetime-local" id="EndDateTime" name="EndDateTime" value="<?= htmlspecialchars($endDateTime); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="Description" class="block text-sm font-semibold text-gray-700">Description</label>
                <textarea id="Description" name="Description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= htmlspecialchars($description); ?></textarea>
            </div>

            <div class="mb-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php
// Clear session data after it has been used
if (isset($_SESSION["successMessage"])) {
    echo '<div class="bg-green-200 text-green-800 p-4 rounded mb-6">';
    echo '<strong>Success: </strong>' . $_SESSION["successMessage"];
    echo '</div>';
    unset($_SESSION["successMessage"]); // Unset after displaying
}
?>