<header>
  <nav class="flex-between">
    <?php
    require_once 'utils/functions.php';
    echo '<a href="index.php" class="h2-bold">ENSAK EVENTS</a>';
    ?>
    <div class="flex-between">
      <?php
      if (is_logged_in() && is_admin()) {
        require_once 'utils/functions.php';
        echo '<a href = "viewEvents.php">Your Events</a>';
        echo '<a href = "logout.php">Logout</a>';
      } else if (is_logged_in()) {
        echo '<a href = "logout.php">Logout</a>';
      } else {
        echo '<a href="login.php">Login</a>';
      }
      ?>
    </div>
  </nav>
</header>