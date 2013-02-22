<?php
/*
	Page: instillinger.php
	Desc: Settings; change password, edit user info, add moderators.
*/

// Gets subpage, if any
$sub = ''; 
if(isset($_GET['sub']) && $_GET['sub']) $sub = $_GET['sub'];
else $sub = 'setting';

// This page requires a sidemenu (edit user info, add moderators)
echo '<div id="pageContent">';



// 1. Display option buttons

echo '<p>'.$sub.'</p>';

// 2. Display edit user info


// 3. Display moderators (lists, add mods)



echo '</div>'; // pageContent
echo '<ul id="pageMenu" class="noMargin">';
  $mods = ''; $konto = ''; $sett = '';
  switch ($sub) {
    case 'edit':
    	$konto = 'current_page_item';
     	break;
    case 'mods':
      	$mods = 'current_page_item';
      	break;
    default:
    	$sett = 'current_page_item';
    	break;
  }
  lia('innstillinger', 'Innstillinger', $sett);
  lia('innstillinger&amp;sub=edit', 'Endre konto', $konto);
  lia('innstillinger&amp;sub=mods', 'Moderatorer', $mods);
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
?>