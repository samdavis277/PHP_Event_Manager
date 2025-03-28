<?php
/*
File: `index.php` (25%)
Functionality: This index.php file should list all events in events.txt visually grouped as follows:
   - Past Events: Events where `EndDateTime` is before today, ignoring start time.
   - This Week: Events within the current week.
   - Future Events: Events beyond this week.

Instructions:
   - For each event, list all properties stored in events.txt except the event ID in an attractive HTML table or similar layout. Use Tailwind CSS library for styling your HTML pages for this and all other labs/assignments in this course.
   - Next to each event, add an edit button that takes the user to edit-event.php and passes the event ID via GET attribute.
   - Next to the edit button for each event, create a delete button that prompts the user to confirm the deletion via JavaScript, then, if confirmed in the JS confirm(), goes to delete-event.php, passing event ID via get attribute. If the JavaScript prompt is declined do nothing.
   - Place a new event button at the top or bottom of the list of events that links to new-event.php.
   - Include a section for a success/error message that is passed via GET attribute. If the message is present, display it in a box at the top of the page as user feedback.

*/
session_start(); // Start the session to access session variables

// Get events from events.txt
$eventsFile = "events.txt";
$events = [];
if (file_exists($eventsFile)) {
    $lines = file($eventsFile, FILE_IGNORE_NEW_LINES); // Read the file line by line
    foreach ($lines as $line) {
        $eventData = explode("|", $line);
        
        $events[] = [
            "EventID" => $eventData[0],
            "EventName" => $eventData[1],
            "Description" => $eventData[2],
            "StartDateTime" => $eventData[3],
            "EndDateTime" => $eventData[4]
        ];
    }
}

// Get today's date
$today = new DateTime();

// Group events by categories
$pastEvents = [];
$thisWeekEvents = [];
$futureEvents = [];

foreach ($events as $event) {
    $endDateTime = new DateTime($event["EndDateTime"]);
    
    // Categorize events
    if ($endDateTime < $today) {
        $pastEvents[] = $event;
    } else {
        $eventWeekStart = new DateTime("monday this week");
        $eventWeekEnd = new DateTime("sunday this week");
        
        if ($endDateTime >= $eventWeekStart && $endDateTime <= $eventWeekEnd) {
            $thisWeekEvents[] = $event;
        } else {
            $futureEvents[] = $event;
        }
    }
}

// Handle success or error message from GET parameters
$message = isset($_GET["message"]) ? $_GET["message"] : '';

// Get success message from session if available
$message = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        
        <!-- Success/Error Message -->
        <?php if ($message): ?>
            <div class="bg-green-200 text-green-800 p-4 rounded mb-6">
                <strong>Success: </strong><?php echo htmlspecialchars($message); ?>
            </div>
            <?php unset($_SESSION['successMessage']); // Clear message after displaying ?>
        <?php endif; ?>
        
        <h1 class="text-3xl font-bold mb-6">Event List</h1>
        
        <!-- Button to Add New Event -->
        <a href="new-event.php" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Event</a>
        
        <!-- Past Events -->
        <h2 class="text-2xl font-semibold mb-2">Past Events</h2>
        <?php if (count($pastEvents) > 0): ?>
            <table class="min-w-full bg-white border border-gray-300 mb-6">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Event Name</th>
                        <th class="px-4 py-2 border">Start Date/Time</th>
                        <th class="px-4 py-2 border">End Date/Time</th>
                        <th class="px-4 py-2 border">Location</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pastEvents as $event): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EventName']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['StartDateTime']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EndDateTime']); ?></td>
                            <td class="px-4 py-2 border">N/A</td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['Description']); ?></td>
                            <td class="px-4 py-2 border">
                                <a href="edit-event.php?EventID=<?= $event['EventID']; ?>" class="text-blue-500">Edit</a>
                                <button onclick="confirmDelete('<?= $event['EventID']; ?>')" class="text-red-500 ml-4">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No past events available.</p>
        <?php endif; ?>

        <!-- This Week Events -->
        <h2 class="text-2xl font-semibold mb-2">This Week Events</h2>
        <?php if (count($thisWeekEvents) > 0): ?>
            <table class="min-w-full bg-white border border-gray-300 mb-6">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Event Name</th>
                        <th class="px-4 py-2 border">Start Date/Time</th>
                        <th class="px-4 py-2 border">End Date/Time</th>
                        <th class="px-4 py-2 border">Location</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($thisWeekEvents as $event): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EventName']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['StartDateTime']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EndDateTime']); ?></td>
                            <td class="px-4 py-2 border">N/A</td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['Description']); ?></td>
                            <td class="px-4 py-2 border">
                                <a href="edit-event.php?EventID=<?= $event['EventID']; ?>" class="text-blue-500">Edit</a>
                                <button onclick="confirmDelete('<?= $event['EventID']; ?>')" class="text-red-500 ml-4">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No events this week.</p>
        <?php endif; ?>

        <!-- Future Events -->
        <h2 class="text-2xl font-semibold mb-2">Future Events</h2>
        <?php if (count($futureEvents) > 0): ?>
            <table class="min-w-full bg-white border border-gray-300 mb-6">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Event Name</th>
                        <th class="px-4 py-2 border">Start Date/Time</th>
                        <th class="px-4 py-2 border">End Date/Time</th>
                        <th class="px-4 py-2 border">Location</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($futureEvents as $event): ?>
                        <tr>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EventName']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['StartDateTime']); ?></td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event['EndDateTime']); ?></td>
                            <td class="px-4 py-2 border">N/A</td>
                            <td class="px-4 py-2 border"><?= htmlspecialchars($event["Description"]); ?></td>
                            <td class="px-4 py-2 border">
                                <a href="edit-event.php?EventID=<?= $event['EventID']; ?>" class="text-blue-500">Edit</a>
                                <button onclick="confirmDelete('<?= $event['EventID']; ?>')" class="text-red-500 ml-4">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No future events available.</p>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete(eventID) {
            const confirmDelete = window.confirm("Are you sure you want to delete this event?");
            if (confirmDelete) {
                window.location.href = `delete-event.php?EventID=${eventID}`;
            }
        }
    </script>
</body>
</html>