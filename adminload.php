<?php
  session_start();
  
  if(!isset($_SESSION['user_id']) || !isset($_GET['type'])) {
    die("Not authorized");
  }
  
  $page = $_GET['page']; // get the requested page
  $limit = $_GET['rows']; // get how many rows we want to have into the grid
  $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
  $sord = $_GET['sord']; // get the direction
  
  
  if($_GET['_search'] == "true") {
    $sfield = $_GET['searchField']; 
    $soper = $_GET['searchOper']; 
    $sstring = $_GET['searchString']; 
  }
  
  if(!$sidx) $sidx =1;
  
  require_once("lib/database/DBController.php");
  
  // DB doesn't check for SQL Injection for Tables
  switch($_GET['type']) {
    case "questions":
      $table = "question";
      break;
    case "categories":
      $table = "category";
      break;
    case "highscore":
      $table = "highscore";
      break;
    default:
      die("unhandled operation");
      break;
  }
  
  // Get Number of Entries from DB
  if(is_numeric($start) && is_numeric($limit) && ctype_alnum($sidx) && ctype_alnum($sord)) {
    if($_GET['_search'] == "true") {
      $count = DBController::get()->getSpecificCountWithSearch($table, $sfield, $soper, $sstring);
    } else {
      $count = DBController::get()->getSpecificCount($table);
    }
  } else {
    $count = 0;
  }
  
  if( $count > 0 ) {
    $total_pages = ceil($count/$limit);
  } else {
    $total_pages = 0;
  }
  $start = $limit*$page - $limit;
  
  // Get Entries for this site
  if(is_numeric($start) && is_numeric($limit) && ctype_alnum($sidx) && ctype_alnum($sord)) {
    if($_GET['_search'] == "true") {
      $rows = DBController::get()->getSpecificDataWithSearch($table, $start, $limit, $sidx, $sord, $sfield, $soper, $sstring);
    } else {
      $rows = DBController::get()->getSpecificData($table, $start, $limit, $sidx, $sord);
    }
  } else {
    $rows = array();
  }
  
  // Load categories for question
  if($_GET['type'] == 'questions') {
    $categories = DBController::get()->getAllCategories();
  } 
  
  // Prepare json file (fill an array with all data)
  $i = 0;
  $responce = new stdClass;
  foreach($rows as $row) {
    $responce->rows[$i]['id'] = $row['id'];
    $responce->rows[$i]['cell'] = $row;
    
    // Load Categories for Highscore and fill it in otherwise if it is an question fill in the correct category
    if($_GET['type'] == 'highscore') {
      $categories = DBController::get()->getCategoriesByHighscoreId($row['id']);
      $categoryNames = "";
      foreach($categories as $category) {
        if($categoryNames != "") {
          $categoryNames .= "; ";
        }
        $categoryNames .= $category->name;
      }
      $responce->rows[$i]['cell']['categories'] = $categoryNames;
    }
    else if($_GET['type'] == 'questions') {
      $responce->rows[$i]['cell']['category'] = getObjectByIdFromArray($categories, $row['category_id'])->name;
    }
    $i++;
  }
  $responce->page = $page;
  $responce->total = $total_pages;
  $responce->records = $count;
  // Send data
  echo json_encode($responce);
  
  // Utility Function
  function getObjectByIdFromArray($array, $id) {
    foreach($array as $obj) {
      if($obj->id == $id) {
        return $obj;
      }
    }
  }
 ?>