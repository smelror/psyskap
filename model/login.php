<?php
require "includes/classes/class.formkey.php";
$formKey = new FormKey();
$errs = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
    if(!isset($_POST['form_key']) || !$formKey->validate()) {  
    	$errs[] = "The magic key to Narnia is lost...";
    } else {
		if(isset($_POST['_login_check']) && $_POST['_login_check']) {
			$errs = $forms->validate_login($_POST['usr'], $_POST['pwd'], $db);
			if(!$errs) {
				validateUser($_POST['usr']);
				header("Location: ".$SERVER['PATH_INFO']."dashboard");
			}
		}
	}
}
?>