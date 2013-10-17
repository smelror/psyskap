<?php
$errs = array();
$hei = "Halla! Du kom hit!";

if(isset($_POST['_login_check']) && $_POST['_login_check']) {
	$errs = $forms->validate_login($_POST['usr'], $_POST['pwd'], $db);
	if(!$errs) {
		validateUser($_POST['usr']);
		header("Location: ".$SERVER['PATH_INFO']."dashboard");
	}
}
?>