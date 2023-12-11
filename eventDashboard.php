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
    SELECT users.user_id, users.username, users.email, users.user_img
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
  <?php require 'utils/config.php'; ?>
  <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsApiKey; ?>&callback=initMap"></script>
  <script>
    var map;
    var marker;
    function initMap() {
      // Initialize the map
      map = new google.maps.Map(document.getElementById('map'), {
        center: {
          lat: <?php echo $event['event_latitude']; ?>,
          lng: <?php echo $event['event_longitude']; ?>
        },
        zoom: 15 // You can adjust the zoom level as needed
      });
      // Add a marker for the event's location
      marker = new google.maps.Marker({
        position: {
          lat: <?php echo $event['event_latitude']; ?>,
          lng: <?php echo $event['event_longitude']; ?>
        },
        map: map,
        title: 'Event Location'
      });
    }
  </script>
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex flex-col">
    <div id="hero">
      <div id="event-img">
        <img class="bg-img" src="<?php echo $event['event_img']; ?>" alt="event-img">
      </div>
      <h1 class='event-name h1-semibold'>
        <?php echo $event['event_name']; ?>
      </h1>
    </div>
    <div class='flex-between flex-wrap'>
      <p class='event-type'><strong class='h1-semibold'>Categorie:</strong>
        <?php echo $event['event_type']; ?>
      </p>
      <p class='event-date'><strong class='h1-semibold'>Date:</strong>
        <?php echo $event['event_date']; ?>
      </p>
    </div>
    <p class='event-details'><strong class='h1-semibold'>Details:</strong>
      <?php echo $event['event_details']; ?>
    </p>
    <?php
    if ($event['event_latitude'] != 0 && $event['event_longitude'] != 0) {
      echo "<div id='map'></div>";
    } else {
      echo "<p>No Location in this event.</p>";
    }
    ?>
    <h2>Registered Users:</h2>
    <div>
      <div class='user-row'>
        <h3 class='user-profile'>User:</h3>
        <h3 class='user-email'>Email:</h3>
      </div>
      <?php
      if (empty($users)) {
        echo "<p>No users registered for this event.</p>";
      } else {
        foreach ($users as $user) {
          echo
            "<div class='user-row'>
              <div class='user-profile'>
                <img id='profile-img' src='{$user['user_img']}' alt='User Image' width='30' height='30'>
                <p class='username'>{$user['username']}</p>
              </div>
              <p class='user-email'>{$user['email']}</p>
            </div>";
        }
      }
      ?>
    </div>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>