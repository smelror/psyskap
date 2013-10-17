<?php
/*
	Page: inc.header.php
	Desc: Prepares page\menus then displays as HTML5
*/
if(!isset($_GET['p'])) header("Location: ".$SERVER['PATH_INFO']."velkommen"); // ".$SERVER['PHP_SELF']."?p=velkommen"
// startup and check
session_start();

include_once "config.php";
include_once "includes/classes/class.forms.php";
include_once "includes/classes/class.skap.php";
include_once "includes/classes/class.db.php";

$forms = new PsyForms();
$db = new PsyDB();

// set page: if logged in, set requested, else set to index
if(isset($_GET['p']) && is_logged_in()) $page = $_GET['p'];
// if not logged in
else {
  $allowed = array('login', 'velkommen', 'finn', 'register');
  if(in_array($_GET['p'], $allowed)) $page = $_GET['p'];
  else $page = 'velkommen';
}

$model = 'model/'.$page.'php';
if(file_exists($model)) {
  include $model; // Perform headerscheck and business logic
}

// Set title
$title = 'PsySkap | '.ucfirst($page);

?><!doctype html>
<html lang="no">
<head>
  <title><?php echo $title; ?></title>
  <meta name="description" content="PsySkap - For deg som benytter bokskap hos PSI/UIO." />
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet" type="text/css">
  <link type="text/css" media="screen, projection" rel="stylesheet" href="http://www.psychaid.no/wp-content/themes/psychaid_v2/style.css">
  <link type="text/css" media="screen, projection" rel="stylesheet" href="includes/psyskap.css">
  <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
</head>
<body>
<div id="menu">
  <div class="row">
    <p id="logo">
      <?php $homeurl; if(is_logged_in()) $homeurl = 'http://www.psychaid.no/skap/dashboard'; else $homeurl = 'http://www.psychaid.no/';
      echo '<a href="'.$homeurl.'">'; ?>
        <img class="transOpacity" src="http://www.psychaid.no/wp-content/themes/psychaid_v2/pics/psychaid_logo.png" alt="PsychAid" title="PsychAid" />
      </a>
    </p>
    <?php menu(is_logged_in(), $page); ?>
  </div><!-- row -->
</div><!-- menu -->
<div id="wrapper" class="clearer">
  <div id="content">
  <?php if(!is_logged_in()) echo '<div id="pageContent">'; ?>