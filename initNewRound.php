<?php
  session_start();
  
  require_once("./lib/database/DBController.php");
  
  // get data from client as json file (array of strings)
  $categorieNames = json_decode(stripslashes($_POST['categories']));
  if(!isset($_POST['categories']) || count($categorieNames) == 0) {
    die(json_encode(0));
  }
  
  $categories = DBController::get()->getCategoriesByNameAsArray($categorieNames);
  
  
  // get all questions from categories
  $questions = DBController::get()->getQuestionsByCategoriesAsArray($categories);
  
  // init session values
  $_SESSION['categories'] = $categories;
  $_SESSION['questions'] = $questions;
  $_SESSION['answeredQuestions'] = array();
  $_SESSION['joker'] = false;
  $_SESSION['starttime'] = time();
  $_SESSION['points'] = 0;
  $_SESSION['current_question'] = null;
  $_SESSION['duration'] = null;
  
  echo json_encode(count($_SESSION['questions']));


 ?>