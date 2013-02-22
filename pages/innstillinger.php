<?php
/*
	Page: instillinger.php
	Desc: Settings; change password, edit user info, add moderators.
*/

// Gets subpage, if any
$sub = ''; 
if(isset($_GET['sub']) && $_GET['sub']) $sub = $_GET['sub'];
else $sub = 'setting';
$curUser = $db->getUser($_SESSION['userid']);

// This page requires a sidemenu (edit user info, add moderators)
echo '<div id="pageContent">';

// 1. Display option buttons
if($sub == 'setting') {
	echo '<h1>Innstillinger</h1>';
	echo '<p>Her kan du utf&oslash;re f&oslash;lgende handlinger:</p>';
	acb_o();
		acb('innstillinger&amp;sub=edit','userinfo','Endre brukerinfo','Skift passord eller e-postadresse');
		acb('innstillinger&amp;sub=mods','mods','Moderatorer','Legg til eller fjern moderatorer');
		acb('innstillinger&amp;sub=log','elog','Systemlogg','Feilmeldinger som rapporteres');
	acb_c();
}

// 2. Display edit user info
elseif ($sub == 'edit') {
	echo '<h1>Endre brukerinfo for '.$curUser['username'].'</h1>';
	
	// Check if submitted changes
	
	// Edit userinfo form
	$forms->editUser(); // tbc	
}

// 3. Display moderators (lists, add mods)
elseif ($sub == 'mods') {



}

// 4. Display errorlog
else {



}



echo '</div>'; // pageContent
echo '<ul id="pageMenu" class="noMargin">';
  $mods = ''; $konto = ''; $logg = ''; $sett = '';
  switch ($sub) {
    case 'edit':
    	$konto = 'current_page_item';
     	break;
    case 'mods':
      	$mods = 'current_page_item';
      	break;
    case 'log':
    	$logg = 'current_page_item';
    	break;
    default:
    	$sett = 'current_page_item';
    	break;
  }
  lia('innstillinger', 'Innstillinger', $sett);
  lia('innstillinger&amp;sub=edit', 'Endre konto', $konto);
  lia('innstillinger&amp;sub=mods', 'Moderatorer', $mods);
  lia('innstillinger&amp;sub=log', 'Systemlogg', $logg);
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
?>
