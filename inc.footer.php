<?php
  /*
   Page: inc.footer.php
   Desc: Finishes the HTML; closes pageContent/content and displays footer.
   */

  // If page:index, create sidebar (pageMenu) 
if($page == "index") {
  echo '</div>'; // pageContent
  echo '<ul id="pageMenu" class="noMargin">';
  lia('http://www.psychaid.no/skap/', 'Skap', 'current_page_item')
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
 }

echo '</div>'; // content
echo '</div>'; // wrapper

echo '<div id="footer">';
  if(is_logged_in()) echo '<p>PsychAid &copy; 2003 - 2012</p>';
  else echo '<p>PsychAid <a href="/?p=login">&copy;</a> 2003 - 2012</p>';
echo '</div>';
?>