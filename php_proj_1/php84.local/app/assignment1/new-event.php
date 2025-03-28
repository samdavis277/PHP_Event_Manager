<?php
/*
File: `new-event.php` (10%)
Functionality: This new-event.php file should display a form to create a new event.

The Event Name, Start Date/Time, and End Date/Time should be required while Description should be optional.

The Start Date and Time fields should use a attractive and easy-to-use date and time picker, not require text entry.

The form should submit to `new-event-handler.php` using the POST method.
*/
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        
        <h1 class="text-3xl font-bold mb-6">Create New Event</h1>

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

        <!-- Event Form -->
        <form action="new-event-handler.php" method="POST" class="bg-white p-6 rounded shadow-md">
            
            <!-- Event Name -->
            <div class="mb-4">
                <label for="EventName" class="block text-sm font-semibold text-gray-700">Event Name</label>
                <input type="text" id="EventName" name="EventName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    value="<?php echo isset($_SESSION['eventData']['EventName']) ? $_SESSION['eventData']['EventName'] : (isset($_POST['EventName']) ? $_POST['EventName'] : ''); ?>"
                >
            </div>
            
            <!-- Start Date/Time -->
            <div class="mb-4">
                <label for="StartDateTime" class="block text-sm font-semibold text-gray-700">Start Date/Time</label>
                <input type="datetime-local" id="StartDateTime" name="StartDateTime" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    value="<?php echo isset($_SESSION['eventData']['StartDateTime']) ? $_SESSION['eventData']['StartDateTime'] : (isset($_POST['StartDateTime']) ? $_POST['StartDateTime'] : ''); ?>"
                >
            </div>
            
            <!-- End Date/Time -->
            <div class="mb-4">
                <label for="EndDateTime" class="block text-sm font-semibold text-gray-700">End Date/Time</label>
                <input type="datetime-local" id="EndDateTime" name="EndDateTime" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    value="<?php echo isset($_SESSION['eventData']['EndDateTime']) ? $_SESSION['eventData']['EndDateTime'] : (isset($_POST['EndDateTime']) ? $_POST['EndDateTime'] : ''); ?>"
                >
            </div>
            
            <!-- Description (Optional) -->
            <div class="mb-4">
                <label for="Description" class="block text-sm font-semibold text-gray-700">Description</label>
                <textarea id="Description" name="Description" rows="4" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?php echo isset($_SESSION['eventData']['Description']) ? $_SESSION['eventData']['Description'] : (isset($_POST['Description']) ? $_POST['Description'] : ''); ?></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Create Event</button>
            </div>
        </form>
        
    </div>
</body>
</html>