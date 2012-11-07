<?php
/*
	Page: login.php
	Desc: Login for moderators/admins.
*/


if(isset($_POST['_login_check']) && $_POST['_login_check']) {
	if($form_errors = $forms->validate_login($_POST['usr'], $_POST['pwd'], $db)) {
		$html->showLogin($forms, $form_errors); //$forms->loginForm($form_errors);
	} else {
		$this->validateUser($_POST['usr']);
		$this->navigateTo('user');


?>