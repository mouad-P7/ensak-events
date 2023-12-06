<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: login.php");
  exit();
}

// Include the database connection file
include 'utils/db_connection.php';

// Get the organizer ID from the session
$organizerID = $_SESSION['user_id'];

// Retrieve events for the logged-in user from the database
$selectQuery = "SELECT * FROM events WHERE organizer_id = '$organizerID'";
$result = mysqli_query($conn, $selectQuery);

if (!$result) {
  die("Error executing the query: " . mysqli_error($conn));
}

// Fetch and display events
$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Events Dashboard</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
  <link rel="stylesheet" type="text/css" href="styles/viewEvents.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">
    <h1>Your Events:</h1>
    <div class='flex-center event-row'>
      <h3 class='event-name h3-bold'>Name:</h3>
      <p class='event-date h3-bold'>Date:</p>
      <p class='event-details h3-bold'>Details:</p>
      <p class='event-actions h3-bold'>Actions:</p>
    </div>
    <?php
    if (empty($events)) {
      echo "<p>No events found.</p>";
    } else {
      foreach ($events as $event) {
        echo
          "<div class='flex-center event-row'>
            <h3 class='event-name'>{$event['event_name']}</h3>
            <p class='event-date'>{$event['event_date']}</p>
            <p class='event-details'>{$event['event_details']}</p>
            <div class='event-actions'>
              <a href='event.php?id={$event['event_id']}' class='info'>View</a>
              <a href='editEvent.php?id={$event['event_id']}' class='normal'>Edit</a>
              <form method='post' action='deleteEvent.php'>
                <input type='hidden' name='event_id' value='{$event['event_id']}'>
                <button type='submit' class='danger'>Delete</button>
              </form>
            </div>
          </div>";
      }
    }
    ?>
    <a href="createEvent.php">Create New Event</a>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>