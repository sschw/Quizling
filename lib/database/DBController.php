<?php
  session_start();
  require_once(dirname(__FILE__) . "/../external/idiorm.php");
  
  class DBController {
    private static $dbcontroller;
    
    private function __construct() {
      // Define different DBs for different languages
      $connections = array(
        array(
          "language"=>"de",
          "host"=>"",
          "db"=>"",
          "username"=>"",
          "password"=>""
        ),
        array(
          "language"=>"en",
          "host"=>"",
          "db"=>"",
          "username"=>"",
          "password"=>""
        )
      );
      // Define Default Connection
      ORM::configure('mysql:host='.$connections[0]["host"].';dbname='.$connections[0]["db"]);
      ORM::configure('username', $connections[0]["username"]);
      ORM::configure('password', $connections[0]["password"]);
      ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

      // Define Connections for Languages
      foreach($connections as $connection) {
        ORM::configure('mysql:host='.$connection["host"].';dbname='.$connection["db"], null, $connection["language"]);
        ORM::configure('username', $connection["username"], $connection["language"]);
        ORM::configure('password', $connection["password"], $connection["language"]);
        ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'), $connection["language"]);
      }
    }
    
    public static function get() {
      if(!isset($dbcontroller)) {
        DBController::$dbcontroller = new DBController();
      }
      return DBController::$dbcontroller;
    }
    
    public function getAllCategories() {
      return ORM::for_table("category", $_SESSION["language"])->find_many();
    }
    
    public function getCategoryById($id) {
      return ORM::for_table("category", $_SESSION["language"])->find_one($id);
    }
    
    public function getCategoryByName($name) {
      return ORM::for_table("category", $_SESSION["language"])->where_equal("name", $name)->find_one();
    }
    
    public function getCategoriesByNameAsArray($names) {
      return ORM::for_table("category", $_SESSION["language"])->where_in("name", $names)->find_array();
    }
    
    public function getCategoriesByHighscoreId($id) {
      return ORM::for_table("category", $_SESSION["language"])->join('category2highscore', array('category.id', '=', 'category2highscore.category_id'))->where_equal("category2highscore.highscore_id", $id)->find_many();
    }
    
    public function getAllQuestions() {
      return ORM::for_table("question", $_SESSION["language"])->find_many();
    }
    
    public function getQuestionById($id) {
      return ORM::for_table("question", $_SESSION["language"])->find_one($id);
    }
    
    public function getQuestionsByCategoriesAsArray($categories) {
      $categories_id = array();
      foreach($categories as $cat) {
        array_push($categories_id, $cat['id']);
      }
      return ORM::for_table("question", $_SESSION["language"])->where_in("category_id", $categories_id)->find_array();
    }
    
    public function getHighscoreById($id) {
      return ORM::for_table("highscore", $_SESSION["language"])->find_one($id);
    }
    
    public function getHighscore() {
      return ORM::for_table("highscore", $_SESSION["language"])->order_by_desc("score")->limit(100)->offset(0)->find_many();
    }
    
    public function getPersonHighscore($person) {
      return ORM::for_table("highscore", $_SESSION["language"])->where("playername", $person)->find_one();
    }
    
    public function getUserByUsernamePassword($username, $password) {
      return ORM::for_table("user", $_SESSION["language"])->where(array("name" => $username, "password" => $username.md5($username.$password)))->find_one();
    }
    
    public function getUserById($user) {
      return ORM::for_table("user", $_SESSION["language"])->find_one($user);
    }
    
    public function countUsersByUsername($username) {
      return ORM::for_table("user", $_SESSION["language"])->where("name", $username)->count();
    }
    
    /* Admin Panel Functions */
    
    public function getSpecificCount($table) {
      return ORM::for_table($table, $_SESSION["language"])->count();
    }
    
    public function getSpecificCountWithSearch($table, $sfield, $soper, $sstring) {
      $query = ORM::for_table($table, $_SESSION["language"]);
      switch($soper) {
        case "eq" : 
          return $query->where_equal($sfield, $sstring)->count();
          break;
        case "ne" : 
          return $query->where_not_equal($sfield, $sstring)->count();
          break;
        case "lt" : 
          return $query->where_lt($sfield, $sstring)->count();
          break;
        case "gt" : 
          return $query->where_gt($sfield, $sstring)->count();
          break;
        case "bw" : 
          return $query->where_like($sfield, $sstring."%")->count();
          break;
        case "ew" : 
          return $query->where_like($sfield, "%".$sstring)->count();
          break;
        case "cn" : 
          return $query->where_like($sfield, "%".$sstring."%")->count();
          break;
      }
    }
    
    public function getSpecificData($table, $start, $rows, $sidx, $sort) {
      return ORM::for_table($table, $_SESSION["language"])->order_by_expr($sidx." ".$sort)->find_array();
    }
    
    public function getSpecificDataWithSearch($table, $start, $rows, $sidx, $sort, $sfield, $soper, $sstring) {
      $query = ORM::for_table($table, $_SESSION["language"]);
      switch($soper) {
        case "eq" : 
          return $query->where_equal($sfield, $sstring)->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "ne" : 
          return $query->where_not_equal($sfield, $sstring)->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "lt" : 
          return $query->where_lt($sfield, $sstring)->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "gt" : 
          return $query->where_gt($sfield, $sstring)->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "bw" : 
          return $query->where_like($sfield, $sstring."%")->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "ew" : 
          return $query->where_like($sfield, "%".$sstring)->order_by_expr($sidx." ".$sort)->find_array();
          break;
        case "cn" : 
          return $query->where_like($sfield, "%".$sstring."%")->order_by_expr($sidx." ".$sort)->find_array();
          break;
      }
      return array();
    }
    
    /* Creating Data */
    
    public function createCategory($name) {
      $cat = ORM::for_table("category", $_SESSION["language"])->create();
      
      $cat->name = $name;
      
      $cat->save();
      return $cat;
    }
    
    public function createQuestion($cat_id, $text, $cAnswer, $wAnswer1, $wAnswer2, $wAnswer3) {
      $que = ORM::for_table("question", $_SESSION["language"])->create();
      
      $que->category_id = $cat_id;
      $que->text = $text;
      $que->correct_answer = $cAnswer;
      $que->wrong_answer1 = $wAnswer1;
      $que->wrong_answer2 = $wAnswer2;
      $que->wrong_answer3 = $wAnswer3;
      
      $que->save();
      return $que;
    }
    
    public function createHighscore($cats, $name, $score, $duration) {
      $hig = ORM::for_table("highscore", $_SESSION["language"])->create();
      
      $hig->playername = $name;
      $hig->score = $score;
      $hig->set_expr('time', 'NOW()');
      $hig->duration = $duration;
      
      $hig->save();
      
      $this->createCategory2Highscores($cats, $hig->id);
      return $hig;
    }

    public function createCategory2Highscores($cats, $highscore) {
      foreach($cats as $cat) {
        $c2h = ORM::for_table("category2highscore", $_SESSION["language"])->create();

        $c2h->category_id = $cat['id'];
        $c2h->highscore_id = $highscore;

        $c2h->save();
      }
    }
    
    public function createUser($username, $password) {
      $per = ORM::for_table("user", $_SESSION["language"])->create();
      
      $per->username = $username;
      $per->password = $username.md5($username.$password);
      
      $per->save();
      return $per;
    }

    public function removeCategories2HighscoresByHighscore($id) {
      return ORM::for_table("category2highscore")->where_equal("highscore_id", $id)->delete_many();
    }
  }
 ?>