<?php
  /*
   Page: inc.footer.php
   Desc: Finishes the HTML; closes pageContent/content and displays footer.
   */

  // If page:index, create sidebar (pageMenu) 
if($page == "index") {
  echo '</div>'; // pageContent
  echo '<ul id="pageMenu" class="noMargin">';
  echo '<li class="current_page_item"><a href="http://www.psychaid.no/skap">Skap</a></li>';
  echo '</ul>';
  echo '<div id="pageMenuEnd">&nbsp;</div>';
 }

echo '</div>'; // content
echo '</div>'; // wrapper

echo '<div id="footer">';
echo '<p>PsychAid <a href="/?p=login">&copy;</a> 2003 - 2012</p>';
echo '</div>';
?>