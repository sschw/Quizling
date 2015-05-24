<?php
  session_start();
  // if Question is not set - stop running this script and send fake data
  if($_SESSION['current_question'] == null) {
    echo json_encode(array(0,0,0,0));
    exit;
  }
  
  // Reload data of question
  require("./lib/database/DBController.php");

  $cQuestion = DBController::get()->getQuestionById($_SESSION['current_question']['id']);

  // Check if Answer correct, modify session variables and db stat
  $answer = $_POST['answer'];
  if(isset($answer) && $answer == html_entity_decode($_SESSION['current_question']['correct_answer'])) {
    // Add Current Game to Stats
    $cQuestion->number_of_correct = intval($cQuestion->number_of_correct) + 1;
    $_SESSION['points'] += 30;
  } else {
    $_SESSION['questions'] = array();
    $_SESSION['answeredQuestions'] = array();
    $_SESSION['points'] = 0;
    // Add Current Game to Stats
    $cQuestion->number_of_wrong = intval($cQuestion->number_of_wrong) + 1;
  }
  // Save the Question
  $cQuestion->save();
  // Send data
  
  $ret = array($_SESSION['points'], intval($cQuestion->number_of_correct), intval($cQuestion->number_of_wrong), html_entity_decode($_SESSION['current_question']['correct_answer']));
  echo json_encode($ret);
  
  $_SESSION['current_question'] = null;
 ?>