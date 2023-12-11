<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: login.php");
  exit();
}
include 'utils/functions.php';
if (!is_admin()) {
  header("Location: accessDenied.php");
  exit();
}

// Include the database connection file
include 'utils/db_connection.php';

// Get the organizer ID from the session
$organizerID = $_SESSION['user_id'];

// Retrieve events for the logged-in user from the database
$selectQuery = "SELECT * FROM events WHERE organizer_id = '$organizerID'";
$result = mysqli_query($conn, $selectQuery);

$organizerQuery = "SELECT * FROM users WHERE user_id = '$organizerID'";
$result2 = mysqli_query($conn, $organizerQuery);

if (!$result || !$result2) {
  die("Error executing the query: " . mysqli_error($conn));
}

// Fetch and display events
$events = mysqli_fetch_all($result, MYSQLI_ASSOC);
$organizer = mysqli_fetch_assoc($result2);
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
  <main class="flex flex-col">
    <div class="flex-start">
      <img id="profile-img" src="<?php echo $organizer['user_img']; ?>" alt="profile-img">
      <p>
        <?php echo $_SESSION['username']; ?>
      </p>
    </div>
    <div>
      <div class='event-row'>
        <h3 class='event-name h3-bold'>Name:</h3>
        <p class='event-actions h3-bold'>Actions:</p>
      </div>
      <?php
      if (empty($events)) {
        echo "<p>No events found.</p>";
      } else {
        foreach ($events as $event) {
          echo
            "<div class='event-row'>
            <h3 class='event-name'>{$event['event_name']}</h3>
            <div class='event-actions'>
              <a href='event.php?id={$event['event_id']}' class='normal'>View</a>
              <a href='eventDashboard.php?id={$event['event_id']}' class='info'>Info</a>
              <a href='editEvent.php?id={$event['event_id']}' class='warning'>Edit</a>
              <form id=\"deleteEventForm{$event['event_id']}\" method='post' action='deleteEvent.php'>
                <input type='hidden' name='event_id' value='{$event['event_id']}'>
                <button type='button' onclick='confirmDelete(\"deleteEventForm{$event['event_id']}\")' class='danger'>Delete</button>
              </form>
            </div>
          </div>";
        }
      }
      ?>
    </div>
    <a id="create-event" href="createEvent.php">Create New Event</a>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

<script>
  function confirmDelete(formId) {
    var result = confirm("Are you sure you want to delete this event?");
    if (result) document.getElementById(formId).submit();
  }
</script>

</html>