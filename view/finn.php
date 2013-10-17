<?php
/*
  Page: finn.php
  Desc: Find and checkup on skap.
*/
// if lookup
if((isset($_POST['selSkap']) && $_POST['selSkap']) || (isset($_GET['skap']) && $_GET['skap'])) {
  if($_POST['selSkap']) $lookup = $_POST['selSkap'];
  else $lookup = $_GET['skap'];
  // validate lookup
  $skap = new Skap($db->getSkap($lookup));
    if(!empty($skap) && get_class($skap) == 'Skap') {
      echo '<p><a href="finn">&laquo; Tilbake til skaplisten</a></p>';
      echo '<div class="raised skapSingle">';
      echo '<h1>Detaljer for '.$skap->getNr().'</h1>';
      $nrclass = ''; $nrtitle = '';
      ($skap->getEier()) ? $nrclass = 'skapNr-na' : $nrclass = 'skapNr-a';
      ($skap->getEier()) ? $nrtitle = 'Skapet er opptatt' : $nrtitle = 'Skapet er ledig!';
        echo '<p class="'.$nrclass.'" title="'.$nrtitle.'">Nummer: '.$skap->getNr().'</p>';
        echo '<p class="skapEtg">Etasje: '.$skap->getRom().'</p>';
        echo '<p class="skapBygg">Bygg: '.$skap->getBygg().'</p>';
        echo '<p class="skapPris">Pris: '.$skap->getPris().'</p>';
        // echo '<p class="skapEta">Betalt for: '.$skap->get
        echo '<p><a href="http://www.psychaid.no/skap/finn&skap='.$skap->getNr().'">Direktelenke for '.$skap->getNr().'</a></p>';
      echo '</div>';
      // Display skap here.
    } else {
      print '<p class="error">Skapet du s&oslahs;kte etter, '.htmlentities($lookup).', er ikke gyldig.</p>';
    }
 } else {
  echo '<h1>Finn ditt skap</h1>';
  echo '<p>Benytter du bokskap i PSI-byggene? Sjekk skapet ditt her!</p>';
  $forms->finnSkap('finnSkap', $page, $db);
 }
?>