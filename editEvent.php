<?php
session_start();

// Include the database connection file
include 'utils/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to the login page if not logged in
  header("Location: login.php");
  exit();
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
  // Get the event ID from the URL
  $eventID = $_GET['id'];

  // Retrieve the event details from the database
  $selectQuery = "SELECT * FROM events WHERE event_id = '$eventID'";
  $result = mysqli_query($conn, $selectQuery);

  if (!$result) {
    die("Error executing the query: " . mysqli_error($conn));
  }

  // Fetch event details
  $event = mysqli_fetch_assoc($result);

} else {
  // Redirect to the viewEvents.php page if 'id' parameter is not set
  header("Location: viewEvents.php");
  exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $eventId = $_POST['event_id']; // Assuming you have a hidden input for event_id in your form
  $eventName = $_POST['event_name'];
  $eventDate = $_POST['event_date'];
  $eventDetails = $_POST['event_details'];

  // Sanitize inputs
  $eventId = mysqli_real_escape_string($conn, $eventId);
  $eventName = mysqli_real_escape_string($conn, $eventName);
  $eventDate = mysqli_real_escape_string($conn, $eventDate);
  $eventDetails = mysqli_real_escape_string($conn, $eventDetails);

  // Get the organizer ID from the session
  $organizerID = $_SESSION['user_id'];

  // Check if an image was uploaded
  if (isset($_FILES['event_img']) && $_FILES['event_img']['error'] == 0) {
    $target_dir = 'images/';  // Set your target directory
    $target_file = $target_dir . basename($_FILES['event_img']['name']);

    // Check file size (2MB limit)
    if ($_FILES['event_img']['size'] > 2 * 1024 * 1024) {
      $error_message = "File size exceeds the limit of 2MB.";
    } else {
      if (move_uploaded_file($_FILES['event_img']['tmp_name'], $target_file)) {
        $eventImage = $target_file;

        // Update the existing event in the database with the image
        $updateQuery =
          "UPDATE events 
          SET event_name = '$eventName', 
            event_date = '$eventDate', 
            event_details = '$eventDetails', 
            event_img = '$eventImage' 
          WHERE event_id = '$eventId' AND organizer_id = '$organizerID'";
        $updateResult = mysqli_query($conn, $updateQuery);

        if ($updateResult) {
          header("Location: viewEvents.php");
          exit();
        } else {
          $error_message = "Error executing the query: " . mysqli_error($conn);
        }
      } else {
        $error_message = "Error moving the uploaded file.";
      }
    }
  } else {
    // Update the existing event in the database without changing the image
    $updateQuery =
      "UPDATE events 
      SET event_name = '$eventName', 
        event_date = '$eventDate', 
        event_details = '$eventDetails' 
      WHERE event_id = '$eventId' AND organizer_id = '$organizerID'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
      header("Location: viewEvents.php");
      exit();
    } else {
      $error_message = "Error executing the query: " . mysqli_error($conn);
    }
  }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Event</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">
    <h1>Edit Event</h1>
    <form method="post" action="" class="flex-start flex-col" enctype="multipart/form-data">
      <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
      <label for="event_name">Event Name:</label>
      <input type="text" id="event_name" name="event_name" value="<?php echo $event['event_name']; ?>" required>
      <label for="event_date">Event Date:</label>
      <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>" required>
      <label for="event_details">Event Details:</label>
      <textarea name="event_details" id="event_details" rows="4"
        required><?php echo $event['event_details']; ?></textarea>
      <img src="<?php echo $event['event_img']; ?>" alt="Current Event Image" width="150" height="100">
      <label for="event_img">Upload a new image:</label>
      <input type="file" id="event_img" name="event_img" accept="image/*" maxlength="2000000">
      <button type="submit">Save Changes</button>
    </form>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>