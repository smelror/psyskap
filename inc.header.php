<?php
/*
	Page: inc.header.php
	Desc: Prepares page\menus then displays as HTML5
*/
include_once "config.php";
include_once "includes/classes/class.forms.php";
$forms = new PsyForms();

// startup and check
session_start();

// set page: if logged in, set requested, else set to index
if(isset($_GET['p']) && is_logged_in()) $page = $_GET['p'];
elseif(isset($_GET['p']) && ($_GET['p'] == 'login')) $page = "login";
else $page = "velkommen";

// Check if DB access is required
if((isset($_GET['s']) && $_GET['s']) || (isset($_POST['s']) && $_POST['s']) || is_logged_in()) {
  // Initiate DB connection
  include "includes/classes/class.db.php";
  $db = new PsyDB();
}

// Set title
$title = 'PsySkap | '.ucfirst($page);

?><!doctype html>
<html lang="no">
<head>
  <title><?php echo $title; ?></title>
  <meta type="description" content="" />
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
  <link href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700" rel="stylesheet" type="text/css" />
  <link type="text/css" media="screen, projection" rel="stylesheet" href="includes/style.css" />
  <?php if(is_logged_in()) echo '<link type="text/css" media="screen, projection" rel="stylesheet" href="includes/psyskap.css" />'; ?>
  <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
</head>
<body>
  <div id="wrapper">
  	<div id="menu">
	  <p id="logo"><a href="http://www.psychaid.no/"><img class="transOpacity" src="http://www.psychaid.no/wp-content/themes/psychaid_v2/pics/psychaid_logo.png" alt="PsychAid" title="PsychAid" /></a></p>
	  <?php menu(is_logged_in(), $page); ?> 
	</div>
	<div id="content">
  <?php if($page == "velkommen"|"finn"|"register") echo '<div id="pageContent">'; ?>