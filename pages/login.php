<?php
/*
	Page: login.php
	Desc: Login for moderators/admins.
*/
if(isset($_POST['_login_check']) && $_POST['_login_check']) {
	if($form_errors = $forms->validate_login($_POST['usr'], $_POST['pwd'], $db)) {
		$forms->loginForm($form_errors);
	} else {
		validateUser($_POST['usr']);
	}
} else {
?>
	<h1>PsySkap: Logg inn</h1>
	<?php $forms->loginForm(); ?>
	

<?php
}
?>