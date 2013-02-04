<?php
  /*
   Page: inc.footer.php
   Desc: Finishes the HTML; closes pageContent/content and displays footer.
   */

  // If page:index, create sidebar (pageMenu) 
if($page == ("finn" || "velkommen" || "register")) {
  echo '</div>'; // pageContent
  echo '<ul id="pageMenu" class="noMargin">';
  $vel = ''; $fin = ''; $reg = '';
  switch ($page) {
    case 'finn':
      $fin = 'current_page_item';
      break;
    case 'velkommen':
      $vel = 'current_page_item';
      break;
    case 'register':
      $reg = 'current_page_item';
      break;
    default:
      break;
  }
  lia('?p=velkommen', 'Velkommen', $vel);
  lia('?p=finn', 'Finn', $fin);
  lia('?p=register', 'Registrer', $reg);
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
 }

echo '</div>'; // content
echo '</div>'; // wrapper

echo '<div id="footer">';
  if(is_logged_in()) echo '<p>PsychAid &copy; 2003 - 2012</p>';
  else echo '<p>PsychAid <a href="?p=login">&copy;</a> 2003 - 2012</p>';
echo '</div>';
?>