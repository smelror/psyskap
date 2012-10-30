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


function menu($loggedin, $selected) {
		echo '<ul id="nav">';
		if(!$loggedin) {
			($selected == 'login')? $this->lia('?page=login', 'Logg inn', 'selected') : $this->lia('?page=login', 'Logg inn', '');
			($selected == 'opprett')? $this->lia('?page=opprett', 'Ny bruker', 'selected') : $this->lia('?page=opprett', 'Ny bruker', '');
		} else {
			$menuitems = array(
				"user" => array(
					"level" => 1,
					"url" => "user",
					"text" => "Oversikt"
					),
				"endre" => array(
					"level" => 1,
					"url" => "endre",
					"text" => "Endre"
					),
				"skap" => array(
					"level" => 1,
					"url" => "skap",
					"text" => "Skap"
					),
				"tilbakemelding" => array(
					"level" => 3,
					"url" => "tilbakemelding",
					"text" => "Tilbakemeldinger"
					),
				"system" => array(
					"level" => 3,
					"url" => "system",
					"text" => "System"
					),
				"admin" => array(
					"level" => 4,
					"url" => "admin",
					"text" => "Administrasjon"
					)
				);
			foreach ($menuitems as $item) {
				if($item['level'] <= $level) {
					if($item['url'] == $selected) {
						$this->lia('?p='.$item['url'], $item['text'], 'selected');
					} else {
						$this->lia('?p='.$item['url'], $item['text']);
					}
				}
			}
			$this->lia('logout.php', 'Logg ut', 'logout');
		}
		echo "</ul>";
	}

?>