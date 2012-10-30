<?php
/*
	Page: config.php
	Desc: Tools, all-accessible variables and such.
*/


// Removes any whitespace from a string
public function removeWhiteSpace($toBeFixed) {
	if(!$toBeFixed) { return false; }
	$sPattern = '/\s*/m'; 
	$sReplace = '';
	return preg_replace( $sPattern, $sReplace, $toBeFixed );
}

// Check if user is logged in or not
function is_logged_in() {
	if(isset($_SESSION['valid']) && $_SESSION['valid'])
       	return true;
   	return false;
}

?>