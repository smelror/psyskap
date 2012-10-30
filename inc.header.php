<?php
/*
	Page: inc.header.php
	Desc: Prepares pages\menus then displays HTML
*/
include_once 'config.php';

// startup and check
session_start();

// set page: if logged in, set requested, else set to index
if(isset($_GET['p']) && is_logged_in()) $page = $_GET['p'];
else $page = "index";

// check locker lookup
if((isset($_GET['s']) && $_GET['s']) || $_POST['s']) {

}


?><!doctype html>
<html lang="no">
<head>
	<title><?php echo $title; ?></title>
	<meta type="description" content="" />
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link type="text/css" media="screen, projection" rel="stylesheet" href="includes/styles.css" />
	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
</head>
<body>
	<!-- wrapper -->
	<!-- top, menu -->