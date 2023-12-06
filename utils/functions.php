<?php

function is_logged_in() {
  start_session();
  return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

function start_session() {
  $id = session_id();
  if($id === "") {
    session_start();
  }
}
?>