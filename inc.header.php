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

if(is_logged_in()) {
	
}

?><!doctype html>
<html lang="no">
<head>
	<script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
	<link type="text/css" media="screen, projection" rel="stylesheet" href="includes/styles.css" />

</head>
<body>