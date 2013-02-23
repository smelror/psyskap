<?php
/*
	Page: instillinger.php
	Desc: Settings; change user password/email, add moderators, system error log.
*/

// Gets subpage
$sub = ''; 
if(isset($_GET['sub']) && $_GET['sub']) $sub = $_GET['sub'];
else $sub = 'setting';
$curUser = $db->getUser($_SESSION['userid']);

// This page requires a sidemenu (edit user info, add moderators)
echo '<div id="pageContent">';

// 1. Display error log
if($sub == 'logg') {
   echo '<h1>Systemlogg</h1>';
   $el = $db->getErrorlog();
   if(!empty($el)) {
      echo '<table>';
      echo '<thead><tr><th>ID</th><th>Melding</th><th>Dato</th></tr></thead>';
      echo '<tbody>';
      foreach ($el as $m) {
        echo '<tr><td>'.$m['id'].'</td><td>'.$m['melding'].'</td><td>'.$m['dato'].'</td></tr>';
      }
      echo '</tbody></table>';
   } else {
    echo '<p class="info">Det er ingen feilmeldinger &aring; rapportere! En fin dag idag, ikke sant?</p>';
   }
}

// 2. Display edit user info - seems to work OK (DO NOT CHANGE)
elseif ($sub == 'edit') {
	echo '<h1>Endre brukerinfo: '.$curUser['username'].'</h1>';
	if(isset($_POST['editusr']) && $_POST['editusr']) { // Check if submitted changes
    if($errors = $forms->validate_editUser($curUser, $_POST['new_epost'], $_POST['new_pwd'], $_POST['new_pwd_control']), $db->getCon()) {
      $forms->editUser($curUser['epost'], $errors);
    } else {
      if($db->editUser($curUser['id'], $_POST['new_epost'], $_POST['new_pwd'])) {
        echo '<p class="success">Endringene var vellykket!</p>';
      } else {
        echo '<p class="error">Noe gikk veldig galt med endringene. Ta kontakt med systemansvarlig for videre veiledning.</p>';
      }
    }
  } else {
    $forms->editUser($curUser['epost']);
  }
}

// 3. Display moderators (lists, add mods)
elseif ($sub == 'mods') {
  echo '<h1>Moderatorer</h1>';
  echo '<h2>Legg til</h2>';
  if(isset($_POST['addModerator']) && $_POST['addModerator']) {  
    if($errors = $forms->validate_addMod($_POST['mod'], $_POST['pwd'], $_POST['epost'], $db->getCon())) {
      $forms->addMod($errors, 'addModForm', 'install');
    } else {
      if($db->addMod($_POST['mod'], $_POST['pwd'], $_POST['epost'])) {
        echo '<p class="success"><strong>'.$_POST['mod'].'</strong> ble opprettet!';
        $message = "
          Hei!<br><br>
          Din epostadresse er registrert som moderator i PsySkap (http://www.psychaid.no/skap), og kan logge inn via<br>
          http://www.psychaid.no/skap/login <br><br>
          usr: ".$_POST['mod']." <br>
          pwd: ".$_POST['pwd']." <br><br>
          Vi anbefaler at du endrer passord etter å ha logget inn. <br><br>
          Mvh, PsychAid <br>
          skap@psychaid.no";
        $maildata = array(
          'to_email' => $_POST['epost'],
          'to_name' => $_POST['mod'],
          'from_email' => 'skap@psychaid.no',
          'from_name' => 'PsySkap',
          'subject' => 'PsySkap: Moderator opprettet',
          'message' => $message
          );
        if(mail_send($maildata)) {
          echo '<br /><br />E-post med brukernavn og passord er sendt til '.$_POST['epost'].'.';
        }
        echo '</p>';
      }
    }
  } else {
    $forms->addMod(null, 'addModForm', 'innstillinger&amp;sub=mods');
  }
  echo '<h2>Oversikt</h2>';
  $allmods = $db->getAllMods();
  echo '<table>';
  echo '<thead><tr><th>Bruker</th><th>E-post</th><th>Opprettet</th></tr></thead>'; // id, username, epost, created
  echo '<tbody>';
  foreach ($allmods as $m) {
    $dato = explode(' ', $m['created']);
    echo '<tr><td>'.$m['username'].'</td><td>'.$m['epost'].'</td><td>'.$dato[0].'</td></tr>';
  }
  echo '</tbody></table>';
}

// 4. Display default (instillinger 'home')
else {
  echo '<h1>Innstillinger</h1>';
  echo '<p>Her kan du utf&oslash;re f&oslash;lgende handlinger:</p>';
  acb_o();
    acb('innstillinger&amp;sub=edit','userinfo','Endre brukerinfo','Skift passord eller e-postadresse');
    acb('innstillinger&amp;sub=mods','mods','Moderatorer','Legg til eller fjern moderatorer');
    acb('innstillinger&amp;sub=logg','elog','Systemlogg','Feilmeldinger som rapporteres');
  acb_c();
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
    case 'logg':
    	$logg = 'current_page_item';
    	break;
    default:
    	$sett = 'current_page_item';
    	break;
  }
  lia('innstillinger', 'Innstillinger', $sett);
  lia('innstillinger&amp;sub=edit', 'Endre konto', $konto);
  lia('innstillinger&amp;sub=mods', 'Moderatorer', $mods);
  lia('innstillinger&amp;sub=logg', 'Systemlogg', $logg);
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
?>
