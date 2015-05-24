<?php

  require_once("lib/database/DBController.php");
  
  // Load the categories
  $categories = DBController::get()->getAllCategories();

  $texts = array();

  // Get the text out of them
  foreach($categories as $cat) {
    array_push($texts, $cat->name);
  }
  // Send them
  echo json_encode($texts);
 ?>