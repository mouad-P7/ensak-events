<?php
// Include the database connection file
include 'utils/db_connection.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
  // Get the event ID from the URL
  $eventID = $_GET['id'];

  // Retrieve the event details from the database
  $selectQuery =
    "SELECT events.*, 
      users.username as organizer_username, 
      users.user_img as organizer_img 
    FROM events 
    JOIN users ON events.organizer_id = users.user_id
    WHERE events.event_id = '$eventID'";
  $result = mysqli_query($conn, $selectQuery);

  if (!$result) {
    die("Error executing the query: " . mysqli_error($conn));
  }

  // Fetch event details
  $event = mysqli_fetch_assoc($result);
} else {
  // Redirect to the index page if 'id' parameter is not set
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Details</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main>
    <?php
    if (isset($event)) {
      echo
        "<img src='{$event['event_img']}' alt='Event Image' width='150' height='100'>
        <h3>{$event['event_name']}</h3>
        <p>Date: {$event['event_date']}</p>
        <p>Details: {$event['event_details']}</p>
        <p>Organized by: {$event['organizer_username']}</p>";
      if (!empty($event['organizer_img'])) {
        echo
          "<img 
          src='{$event['organizer_img']}' 
          alt='Organizer Image' 
          width='100' height='100'>";
      }

    } else {
      echo "<p>Event not found.</p>";
    }
    ?>
  </main>
  <?php require 'layout/footer.php'; ?>

</body>

</html>