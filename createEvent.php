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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $eventName = $_POST['event_name'];
  $eventDate = $_POST['event_date'];
  $eventDetails = $_POST['event_details'];

  // Sanitize inputs
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

        // Insert the new event into the database with the image
        $insertQuery = "INSERT INTO events (event_name, event_date, event_details, event_img, organizer_id) VALUES ('$eventName', '$eventDate', '$eventDetails', '$eventImage', '$organizerID')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
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
    // Insert the new event into the database without an image
    $insertQuery = "INSERT INTO events (event_name, event_date, event_details, organizer_id) VALUES ('$eventName', '$eventDate', '$eventDetails', '$organizerID')";
    $insertResult = mysqli_query($conn, $insertQuery);

    if ($insertResult) {
      $error_message = "isset(_FILES['event_img']) && _FILES['event_img']['error'] == 0";
      // header("Location: viewEvents.php");
      // exit();
    } else {
      $error_message = "Error executing the query: " . mysqli_error($conn);
    }
  }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Event</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">
    <h1>Event Form</h1>
    <?php
    if (isset($error_message)) {
      echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <form method="post" action="" class="flex-start flex-col" enctype="multipart/form-data">
      <label for="event_name">Event Name:</label>
      <input type="text" id="event_name" name="event_name" required>
      <label for="event_date">Event Date:</label>
      <input type="date" id="event_date" name="event_date" required>
      <label for="event_details">Event Details:</label>
      <textarea name="event_details" id="event_details" rows="4" required></textarea>
      <label for="event_img">Event Image:</label>
      <input type="file" id="event_img" name="event_img" accept="image/*" maxlength="2000000" required>
      <button type="submit">Create Event</button>
    </form>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>