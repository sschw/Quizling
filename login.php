<?php
  session_start();
  require_once("lib/database/DBController.php");
  
  // Check if user exists and define session
  $controller = DBController::get();
  try {
    $user = $controller->getUserByUsernamePassword($_POST['username'], $_POST['password']);
    if($user != false) {
      $_SESSION['user_id'] = $user->id;
      echo "true";
    } else {
      echo "false";
    }
  } catch(Exception $e) {
    echo "false";
  }
 ?>