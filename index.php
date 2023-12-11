<?php
// Include the database connection file
include 'utils/db_connection.php';

// Retrieve all events from the database
$search = isset($_GET['search']) ? $_GET['search'] : '';
$selectQuery =
  "SELECT * FROM events
    WHERE event_name LIKE '%$search%'
    OR event_details LIKE '%$search%'";
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
  <link rel="stylesheet" type="text/css" href="styles/index.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex flex-col">
    <form class="flex-center" method="get" action="">
      <input type="text" name="search" placeholder="Search for events..." value="<?php echo isset($_GET['search']) ?
        htmlspecialchars($_GET['search']) : ''; ?>">
      <button type="submit">Submit</button>
    </form>
    <div id="event-card-ctr">
      <?php
      if (empty($events)) {
        echo "<p>No events found.</p>";
      } else {
        foreach ($events as $event) {
          echo
            "<div class='event-card'>
              <div class='event-data'>
                <div class='event-img-ctr'>
                  <img src='{$event['event_img']}' alt='img' class='bg-img'>
                </div>
                <h3 class='event-name'>{$event['event_name']}</h3>
                <p class='event-date'>Date: {$event['event_date']}</p>
              </div>
              <a class='event-link' href='event.php?id={$event['event_id']}'>
                View More Details
              </a>
            </div>";
        }
      }
      ?>
    </div>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>