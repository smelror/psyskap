<?php
  /*
   Page: inc.footer.php
   Desc: Finishes the HTML; closes pageContent/content and displays footer.
   */

  // If page:index, create sidebar (pageMenu) 
if(!is_logged_in()) {
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
  lia('velkommen', 'Velkommen', $vel);
  lia('finn', 'Finn', $fin);
  lia('register', 'Registrer', $reg);
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
 }

echo '</div>'; // content
echo '</div>'; // wrapper

echo '<div id="footer">';
  if(is_logged_in()) echo '<p>PsychAid &copy; 2003 - 2012</p>';
  else echo '<p>PsychAid <a href="http://psychaid.no/skap/login">&copy;</a> 2003 - 2012</p>';
  echo '<p>PsySkap (v'.VERSION.') av Vegard Smelror &Aring;mdal</p>';
echo '</div>';
echo '</body>';
echo '</html>';
?>