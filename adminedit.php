<?php
  session_start();
  
  if(!isset($_SESSION['user_id']) || !isset($_GET['type'])) {
    die("Not authorized");
  }
  
  require_once("lib/database/DBController.php");
  
  // Switch to the correct type
  switch($_GET['type']) {
    case "questions":
      // If operation is delete - delete the entry
      $id = htmlentities($_POST['id']);
      if($_POST['oper'] == "del") {
        $q = DBController::get()->getQuestionById($id);
        $q->delete();
        break;
      }
      
      $category = htmlentities($_POST['category']);
      $text = htmlentities($_POST['text']);
      $correct_answer = htmlentities($_POST['correct_answer']);
      $wrong_answer1 = htmlentities($_POST['wrong_answer1']);
      $wrong_answer2 = htmlentities($_POST['wrong_answer2']);
      $wrong_answer3 = htmlentities($_POST['wrong_answer3']);
      $category_obj = DBController::get()->getCategoryByName($category);
      
      if($category_obj == null) {
        $category_id = DBController::get()->createCategory($category)->id;
      } else {
        $category_id = $category_obj->id;
      }
      
      // If operation is add - add a new entry otherwise modify an existing one
      if($_POST['oper'] == "add") {
        $q = DBController::get()->createQuestion($category_id, $text, $correct_answer, $wrong_answer1, $wrong_answer2, $wrong_answer3);
      } else if($_POST['oper'] == "edit") {
        $q = DBController::get()->getQuestionById($id);
        $q->category_id = $category_id;
        $q->text = $text;
        $q->correct_answer = $correct_answer;
        $q->wrong_answer1 = $wrong_answer1;
        $q->wrong_answer2 = $wrong_answer2;
        $q->wrong_answer3 = $wrong_answer3;
        
        $q->save();
      }
      break;
    
    case "categories":
      $id = htmlentities($_POST['id']);
      
      // If operation is delete - delete the entry
      if($_POST['oper'] == "del") {
        $c = DBController::get()->getCategoryById($id);
        $c->delete();
        break;
      }
      
      $name = htmlentities($_POST['name']);
      
      // If operation is add - add a new entry otherwise modify an existing one
      if($_POST['oper'] == "add") {
        $c = DBController::get()->createCategory($name);
      } else if($_POST['oper'] == "edit") {
        $c = DBController::get()->getCategoryById($id);
        $c->name = $name;
        
        $c->save();
      }
      break;
    
    case "highscore": 
      $id = htmlentities($_POST['id']);
      
      // If operation is delete - delete the entry
      if($_POST['oper'] == "del") {
        $c = DBController::get()->getHighscoreById($id);
        $c->delete();
        break;
      }
      
      break;
    // unimplemented
    case "users": 
      if($_POST['oper'] == "add") {
        $username = htmlentities($_POST['username']);
        $password = htmlentities($_POST['password']);
        $u = DBController::get()->createUsers($username, $password);
      }
      break;
  }
 ?>