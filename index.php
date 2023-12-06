<?php
// Include the database connection file
include 'utils/db_connection.php';

// Retrieve all events from the database
$selectQuery = "SELECT * FROM events";
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
  <title>Home</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">
    <?php
    if (empty($events)) {
      echo "<p>No events found.</p>";
    } else {
      foreach ($events as $event) {
        echo
          "<div class='flex-center flex-col'>
            <h3>{$event['event_name']}</h3>
            <p>Date: {$event['event_date']}</p>
          </div>
          <a href='event.php?id={$event['event_id']}'>View More Details</a>";
      }
    }
    ?>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>