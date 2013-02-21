<?php
/*
	Page: login.php
	Desc: Login for moderators/admins.
*/
if(isset($_POST['_login_check']) && $_POST['_login_check']) {
	if($form_errors = $forms->validate_login($_POST['usr'], $_POST['pwd'], $db)) {
		echo '<h1>PsySkap: Logg inn</h1>';
		$forms->login($form_errors);
	}
} else {
	echo '<h1>PsySkap: Logg inn</h1>';
	$forms->login();
}
?>