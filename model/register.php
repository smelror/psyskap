<?php
$registrert = false;
$errs = array();
$skap;

if(isset($_POST['selSkap']) && $_POST['selSkap']) {
	$errs = $forms->valdiate_register($_POST['eier'], $_POST['selSkap'], $db);
	if($errs) {
		$registrert = false;
	} else {
		$skap = new Skap($db->getSkap($_POST['selSkap']));
		$registrert = $skap->set('eier', $_POST['eier'], $db);
		if(!$registrert) {
			$errs[] = 'Systemfeil! Ta kontakt med PsychAid p&aring; kontakt@psychaid.no.';
		}
	}
}
?>