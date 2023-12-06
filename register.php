<?php
include 'utils/db_connection.php';
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if(empty($username) || empty($password)) {
    $error_message = "Username and password are required.";
  } else {
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Check if the username is already taken
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check_query);

    if($check_result && mysqli_num_rows($check_result) > 0) {
      $error_message = "Username is already taken.";
    } else {
      // Insert the new user into the database
      $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
      $insert_result = mysqli_query($conn, $insert_query);

      if($insert_result) {
        // Registration successful, redirect to login page
        header("Location: login.php");
        exit();
      } else {
        $error_message = "Error executing the query: ".mysqli_error($conn);
      }
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
  <title>Registration</title>
  <link rel="stylesheet" type="text/css" href="styles/globals.css">
</head>

<body>
  <?php require 'layout/header.php'; ?>
  <main class="flex-start flex-col">

    <h2>Registration From</h2>
    <?php
    if(isset($error_message)) {
      echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
    <form method="post" action="" class="flex-start flex-col">
      <label for="username">Username:</label>
      <input type="text" name="username" required>
      <label for="password">Password:</label>
      <input type="password" name="password" required>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Log In</a></p>
  </main>
  <?php require 'layout/footer.php'; ?>
</body>

</html>