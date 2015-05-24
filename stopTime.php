<?php
  session_start();
  
  // Save Duration of Quiz
  $_SESSION['duration'] = time() - $_SESSION['starttime'];
  
  // Unset session - player should not continue with playing
  $_SESSION['questions'] = array();
  $_SESSION['answeredQuestions'] = array();
  $_SESSION['current_question'] = null;
 ?>