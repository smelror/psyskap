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

function checkLogin($forms, $db) {
	if(isset($_POST['_login_check']) && $_POST['_login_check']) {
	  if(!$forms->validate_login($_POST['usr'], $_POST['pwd'], $db)) {
	    validateUser($_POST['usr']);
	    header("Location: ".$SERVER['PATH_INFO']."dashboard");
	  }
	}
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
			lia('http://www.psychaid.no/aksjoner/', 'Aksjoner');
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
							 "url" => "semester",
							 "text" => "Semester"
							 ),
  				     "innstillinger" => array(
						 "url" => "innstillinger",
						 "text" => "Innstillinger"
						 )
				     );
		  foreach ($menuitems as $item) {
		    if($item['url'] == $selected) {
		      lia($item['url'], $item['text'], 'current_page_item'); // current_page_item = using same stylesheet as www.psychaid.no
		    } else {
		      lia($item['url'], $item['text']);
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
	session_regenerate_id (); //this is an easy security measure
    $_SESSION['valid'] = 1;
	$_SESSION['userid'] = $userid;
}
/**
 * Function responsible for sending unicode emails.
 *
 * @author Gajus Kuizinas <g.kuizinas@anuary.com>
 * @version 1.0.1 (2012 01 11)
 */
function mail_send($arr)
	{
    if (!isset($arr['to_email'], $arr['from_email'], $arr['subject'], $arr['message'])) {
        throw new HelperException('mail(); not all parameters provided.');
    }
    $to = empty($arr['to_name']) ? $arr['to_email'] : '"' . mb_encode_mimeheader($arr['to_name']) . '" <' . $arr['to_email'] . '>';
    $from = empty($arr['from_name']) ? $arr['from_email'] : '"' . mb_encode_mimeheader($arr['from_name']) . '" <' . $arr['from_email'] . '>';
    $headers = array
    (
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset="UTF-8";',
        'Content-Transfer-Encoding: 7bit',
        'Date: ' . date('r', $_SERVER['REQUEST_TIME']),
        'Message-ID: <' . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
        'X-Mailer: PHP v' . phpversion(),
        'X-Originating-IP: ' . $_SERVER['SERVER_ADDR'],
    );
    return mail($to, '=?UTF-8?B?' . base64_encode($arr['subject']) . '?=', $arr['message'], implode("\n", $headers));
}
?>