<?php
/*
	Page: config.php
	Desc: Tools, all-accessible variables and such.
*/


// Removes any whitespace from a string
function removeWhiteSpace($toBeFixed) {
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

// Top tabbed-menu
function menu($loggedin, $selected) {
		echo '<ul id="nav" class="menu">';
		if(!$loggedin) {
			lia('http://www.psychaid.no/','Forsiden');
			lia('http://www.psychaid.no/aksjoner/', 'Delta!');
			lia('http://www.psychaid.no/delta/', 'Delta!');
			lia('http://www.psychaid.no/om-oss/', 'Om oss');
			lia('http://www.psychaid.no/skap/', 'Skap', 'current_page_item');
		} else {
		  $menuitems = array(
				     "dash" => array(
						     "url" => "dashboard",
						     "text" => "Dashboard"
						     ),
				     "skap" => array(
						     "url" => "skap",
						     "text" => "Skap"
						     ),
				     "eiere" => array(
						      "url" => "eiere",
						      "text" => "Eiere"
						      ),
				     "semester" => array(
							 "url" => "system",
							 "text" => "System"
							 ),
				     "admin" => array(
						      "url" => "admin",
						      "text" => "Administrasjon"
						      )
				     );
		  foreach ($menuitems as $item) {
		    if($item['url'] == $selected) {
		      lia('index.php?p='.$item['url'], $item['text'], 'current_page_item'); // current_page_item = using same stylesheet as www.psychaid.no
		    } else {
		      lia('index.php?p='.$item['url'], $item['text']);
		    }
		  }
		  lia('logout.php', 'Logg ut', 'logout');
		}
		echo "</ul>";
	}

// Prints out a <li><a>
function lia($url, $text, $class = '') {
  ($class == '')?  $line = '<li><a href="'.$url.'" title="'.$text.'">'.$text.'</a></li>' : $line = '<li class="'.$class.'"><a href="'.$url.'" title="'.$text.'">'.$text.'</a></li>';
  echo $line;
}

function validateUser($userid) {
	session_regenerate_id (); //this is a security measure
    $_SESSION['valid'] = 1;
	$_SESSION['userid'] = $userid;
}
?>