<?php
  session_start();
  
  if($_SESSION['points'] != 0 && $_SESSION['duration'] != null) {
    if(isset($_POST['name']) && $_POST['name'] != "") {
      require_once("./lib/database/DBController.php");

      $player = DBController::get()->getPersonHighscore(htmlentities($_POST['name']));
      if($player == null) {
        // Save score in the db - prevent html injection (sql injection handled by idiorm library)
        DBController::get()->createHighscore($_SESSION['categories'], htmlentities($_POST['name']), $_SESSION['points'], $_SESSION['duration']);
      } else {
        // Modify score if eff. score is higher than the current one
        if(($player->score/$player->duration) < ($_SESSION['points']/$_SESSION['duration'])) {
          $player->score = $_SESSION['points'];
          $player->set_expr('time', 'NOW()');
          $player->duration = $_SESSION['duration'];
          $player->save();

          DBController::get()->removeCategories2HighscoresByHighscore($player->id);
          DBController::get()->createCategory2Highscores($_SESSION['categories'], $player->id);
        }
      }
    }
  }
  // unset game values
  $_SESSION['questions'] = array();
  $_SESSION['answeredQuestions'] = array();
  $_SESSION['points'] = 0;
  $_SESSION['current_question'] = null;
  $_SESSION['endtime'] = null;
 ?>