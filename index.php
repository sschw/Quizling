<?php
session_start();
require 'lib/external/smarty/Smarty.class.php';

/** Init Default Language **/
if(!isset($_SESSION['language'])) {
  $_SESSION['language'] = "de";
}

/** Init Page Content **/
if(isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = "game";
}

$smarty = new Smarty;

/** Handle 404 Page **/
if(!file_exists($smarty->template_dir[0]."pages/". $page .".tpl")) {
  header("HTTP/1.0 404 Not Found"); 
  $page = "error";
}

/** Handle Subpages **/
if(isset($_GET['subpage'])) {
  if(file_exists($smarty->template_dir[0]."pages/".$page."/".$_GET['subpage'].".tpl")) {
    $smarty->assign("subpage", $_GET['subpage']);
  } else {
    header("HTTP/1.0 404 Not Found"); 
    $page = "error";
  }
}

$smarty->assign("page", "pages/".$page);

if(isset($_GET["details"])) {
  $smarty->assign("details", $_GET["details"]);
}

$ajax_request = @($_SERVER["HTTP_AJAX_REQUEST"] == "true");
$smarty->display($ajax_request ? 'content.tpl' : 'site.tpl');
 ?>