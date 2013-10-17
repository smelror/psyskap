<?php
/*
 Page: register.php
 Desc: Registreringssiden for skapeiere; fyll ut skjema som sendes til forslags-tabellen.
*/
if($registrert) {
      echo "<h1>Bekreftelse p&aring; registrering</h1>";
      echo '<p class="success">Registreringen var vellykket! Tusen takk for registreringen.</p>';
      echo '<div class="monoSpaced">';
      echo '<p><strong>Eier:</strong> '.$_POST['eier'].'</p>';
      echo '<p><strong>Skap:</strong> '.$_POST['selSkap'].'</p>';
      echo '<p><strong>Etasje:</strong> '.$skap->getRom().'</p>';
      echo '<p><strong>Bygg:</strong> '.$skap->getBygg().'</p>';
      echo '<p><strong>Pris:</strong> '.$pris.'</p>';
      echo '</div>';
      echo '<p>Vennligst skriv ut denne siden og legg med betaling for semesteret [insert current semester], om du ikke allerede har betalt.</p>';
    } else {
      echo "<h1>Registrer ditt eierskap</h1>";
      if($errs) echo '<p>Det oppstod feil under registrering:</p>';
      else echo '<p>Fyll ut f&oslash;lgende skjema og send det inn.</p>';
      $forms->register('regsugg', $page, $db, $errs);
    }
?>
<p>Om du beh&oslash;ver assistanse kan du <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.<p>