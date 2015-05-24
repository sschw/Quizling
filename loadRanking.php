<?php

  $page = $_GET['page']; // get the requested page
  $limit = $_GET['rows']; // get how many rows we want to have into the grid
  //$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
  //$sord = $_GET['sord']; // get the direction
  // fixed search
  $sidx = "score/duration";
  $sord = "DESC";


  if($_GET['_search'] == "true") {
    $sfield = $_GET['searchField'];
    $soper = $_GET['searchOper'];
    $sstring = $_GET['searchString'];
  }

  if(!$sidx) $sidx =1;

  require_once("lib/database/DBController.php");


  // Load the number of entries in the table
  if(is_numeric($start) && is_numeric($limit)) {
    if($_GET['_search'] == "true") {
      $count = DBController::get()->getSpecificCountWithSearch("highscore", $sfield, $soper, $sstring);
    } else {
      $count = DBController::get()->getSpecificCount("highscore");
    }
  } else {
    $count = 0;
  }

  if( $count >0 ) {
    $total_pages = ceil($count/$limit);
  } else {
    $total_pages = 0;
  }
  $start = $limit*$page - $limit;

  // Load the entries
  if(is_numeric($start) && is_numeric($limit)) {
    if($_GET['_search'] == "true") {
      $rows = DBController::get()->getSpecificDataWithSearch("highscore", $start, $limit, $sidx, $sord, $sfield, $soper, $sstring);
    } else {
      $rows = DBController::get()->getSpecificData("highscore", $start, $limit, $sidx, $sord);
    }
  } else {
    $rows = array();
  }

  // Fill everything into an array
  $i = 0;
  $responce = new stdClass;
  foreach($rows as $row) {
    $responce->rows[$i]['id'] = $row['id'];
    // Ranking should just be nummering the rows
    $row['rank'] = $i + 1;
    $row['duration'] .= "s";
    $responce->rows[$i]['cell'] = $row;
    // Load the categories and fill them into the array
    $categories = DBController::get()->getCategoriesByHighscoreId($row['id']);
    $categoryNames = "";
    foreach($categories as $category) {
      if($categoryNames != "") {
        $categoryNames .= "; ";
      }
      $categoryNames .= $category->name;
    }
    $responce->rows[$i]['cell']['categories'] = $categoryNames;
    $responce->rows[$i]['cell']['calcScore'] = round($row['score'] / $row['duration']);
    $i++;
  }
  $responce->page = $page;
  $responce->total = $total_pages;
  $responce->records = $count;
  // Send everything
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