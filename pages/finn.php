<h1>PsySkap</h1>
<p>Om du har skap i PSI-byggene, kan du sjekke til skapet ditt her.</p>
   <?php 
   // if lookup
   if((isset($_GET['s']) && $_GET['s']) || (isset($_POST['s']) && $_POST['s'])) {
     if($_GET['s']) $lookup = $_GET['s'];
     else $lookup = $_POST['s'];
     // validate lookup
     $error = '';
     if(strl($lookup) <= 5) { // Skap identifiers are max five characters long.
       $skap = $db->getSkap($lookup, is_logged_in());
       if(get_class($skap) == 'Skap') {

       } else {
	       $error = 'Skapet du s&oslahs;kte etter, '.htmlentities($lookup).', er ikke gyldig.';
       }
     } else {
       $error = "Skapnummer kan inneholde maks. fem tegn.";
     }
    if($error) print '<div class="warning">'.$error.'</div>';
   } else {
     $forms->finnSkap('finnSkap', $page);
   }
?>