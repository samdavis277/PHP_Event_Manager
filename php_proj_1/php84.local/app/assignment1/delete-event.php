<?php

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