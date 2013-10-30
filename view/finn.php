<?php
/*
  Page: finn.php
  Desc: Find and checkup on skap.
*/

if(!empty($skap)) {
  echo '<p><a href="finn">&laquo; Tilbake til skaplisten</a></p>';
  echo '<div class="raised skapSingle">';
  echo '<h1>Detaljer for '.$skap->getNr().'</h1>';
  $nrclass = ''; $nrtitle = '';
  ($skap->getEier()) ? $nrclass = 'skapNr-na' : $nrclass = 'skapNr-a';
  ($skap->getEier()) ? $nrtitle = 'Skapet er opptatt' : $nrtitle = 'Skapet er ledig!';
    echo '<p class="'.$nrclass.'" title="'.$nrtitle.'">Nummer: '.$skap->getNr().'</p>';
    echo '<p class="skapEtg">Etasje: '.$skap->getRom().'</p>';
    echo '<p class="skapBygg">Bygg: '.$skap->getBygg().'</p>'; 
    echo '<p class="skapPris">Pris: '.PRIS.' NOK</p>';
    echo '<p><a href="http://www.psychaid.no/skap/finn&skap='.$skap->getNr().'">Direktelenke for '.$skap->getNr().'</a></p>';
  echo '</div>';
  // Display skap here.
} else {
  echo '<h1>Finn ditt skap</h1>';
  echo '<p>Benytter du bokskap i PSI-byggene? Sjekk skapet ditt her!</p>';
  $forms->finnSkap('finnSkap', $page, $db);
}
?>