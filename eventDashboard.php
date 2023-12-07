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

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
  // Get the event ID from the URL
  $eventID = $_GET['id'];

  // Retrieve the event details from the database
  $selectEventQuery = "SELECT * FROM events WHERE event_id = '$eventID'";
  $eventResult = mysqli_query($conn, $selectEventQuery);

  if (!$eventResult) {
    die("Error executing the event query: " . mysqli_error($conn));
  }

  // Fetch event details
  $event = mysqli_fetch_assoc($eventResult);

  // Retrieve the list of registered users for the event
  $selectUsersQuery = "
    SELECT users.user_id, users.username, users.user_img
    FROM users
    JOIN registrations ON users.user_id = registrations.user_id
    WHERE registrations.event_id = '$eventID'
  ";
  $usersResult = mysqli_query($conn, $selectUsersQuery);

  if (!$usersResult) {
    die("Error executing the users query: " . mysqli_error($conn));
  }

  // Fetch and display event details and registered users
  $users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);
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
  <title>Event Dashboard</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
  <link rel="stylesheet" type="text/css" href="styles/eventDashboard.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">
    <h1>
      <?php echo $event['event_name']; ?>
    </h1>
    <div class='event-details-container'>
      <p><strong>Date:</strong>
        <?php echo $event['event_date']; ?>
      </p>
      <p><strong>Details:</strong>
        <?php echo $event['event_details']; ?>
      </p>
    </div>
    <h2>Registered Users:</h2>
    <?php
    if (empty($users)) {
      echo "<p>No users registered for this event.</p>";
    } else {
      echo "<div id='users-ctr'>";
      foreach ($users as $user) {
        echo
          "<div class='flex-center'>
            <img class='user-img' src='{$user['user_img']}' alt='User Image' width='40' height='40'>
            <p class='user-username'>{$user['username']}</p>
          </div>";
      }
      echo "</div>";
    }
    ?>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>