<?php
  session_start();
  // If joker available - send wrong answers
  if(!$_SESSION['joker']) {
    $_SESSION['joker'] = true;
    $rand = rand(1, 3);
    echo json_encode(array($_SESSION['current_question']['wrong_answer' . $rand], $_SESSION['current_question']['wrong_answer' . (($rand%3)+1)]));
  }
 ?>