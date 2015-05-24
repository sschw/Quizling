<?php
  session_start();
  // Change Session
  if($_GET["language"] == "de") {
    $_SESSION['language'] = "de";
  } else if($_GET["language"] == "en") {
    $_SESSION['language'] = "en";
  }
 ?>