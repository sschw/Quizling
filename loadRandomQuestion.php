<?php
  session_start();

  // Send same data if user tries to load random question again
  if($_SESSION['current_question'] == null) {
    // all unanswered questions
    $availableQuestions = question_diff($_SESSION['questions'], $_SESSION['answeredQuestions']); 
    if(count($availableQuestions) == 0) {
      die("No questions left");
    }
  
    // Get a random question out of it
    $_SESSION['current_question'] = $availableQuestions[rand(0, count($availableQuestions)-1)];
    
    // Add it to the answeredQuestion Session
    array_push($_SESSION['answeredQuestions'], $_SESSION['current_question']);
  }
  
  // Get the answers in a random sequence
  $answerColumns = array("correct_answer", "wrong_answer1", "wrong_answer2", "wrong_answer3");
  $answer_order = rand(0, 3);
  
  $answer = array(
    html_entity_decode($_SESSION['current_question'][$answerColumns[$answer_order]]),
    html_entity_decode($_SESSION['current_question'][$answerColumns[($answer_order+1)%4]]),
    html_entity_decode($_SESSION['current_question'][$answerColumns[($answer_order+2)%4]]),
    html_entity_decode($_SESSION['current_question'][$answerColumns[($answer_order+3)%4]])
  );
  
  // Load the Sounds for them
  require_once("./lib/tts.php");
  $soundPaths = array(
                      getTTSFromGoogle(html_entity_decode($_SESSION['current_question']["text"])),
                      getTTSFromGoogle("A: " . $answer[0]),
                      getTTSFromGoogle("B: " . $answer[1]),
                      getTTSFromGoogle("C: " . $answer[2]),
                      getTTSFromGoogle("D: " . $answer[3])
                );
  
  // Send everything to the client
  $return = array(
    "numberOfQuestions" => count($_SESSION['questions']),
    "answeredQuestions" => count($_SESSION['answeredQuestions']),
    "currentPoints" => $_SESSION['points'],
    "joker" => $_SESSION['joker'],
    "question" => array(
        "text" => html_entity_decode($_SESSION['current_question']["text"]),
        "answers" => $answer,
        "soundPaths" => $soundPaths
    )
  );
  
  echo json_encode($return);
  
  // Utility Function - Filter all questions with already answered questions
  function question_diff($question, $answeredQuestion) {
    $diff = array();
    foreach($question as $q) {
      $contains = false;
      foreach($answeredQuestion as $aq) {
        if($q['id'] == $aq['id']) {
          $contains = true;
        }
      }
      if(!$contains) {
        array_push($diff, $q);
      }
    }
    return $diff;
  }
 ?>