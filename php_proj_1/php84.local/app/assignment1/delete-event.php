<?php
/*
File: `delete-event.php` (10%)

Functionality: This file should receive an event ID via GET attribute. The PHP should then locate the line in events.txt and delete it, ensuring a blank line is not left in it's place. The user should then be redirect to index.php and a notice stating “Event <NAME> Successfully Deleted” should be displayed.

Instructions:
   - Retrieve the event ID from the GET parameter.
   - Find and remove the corresponding event in events.txt.
   - Redirect back to index.php with a success message.
*/

if (isset($_GET["EventID"])) {
   $eventIDToDelete = $_GET["EventID"];
   $eventsFile = "events.txt";
   $lines = file($eventsFile, FILE_IGNORE_NEW_LINES);
   

   $eventName = "";
   // Find the event to delete by matching the EventID
   foreach ($lines as $key => $line) {
       $eventData = explode("|", $line);
       if ($eventData[0] == $eventIDToDelete) {
         $eventName = $eventData[1];
           unset($lines[$key]); // Remove the event
           break;
       }
   }

   // Rewriting the file with the remaining events
   file_put_contents($eventsFile, implode("\n", $lines) . "\n");
   
   // Redirect back to index.php with success message
   header("Location: index.php?message=Event '$eventName' Successfully Deleted");
   exit;
}