<?php
/*
 Page: register.php
 Desc: Registreringssiden for skapeiere; fyll ut skjema som sendes til forslags-tabellen.
*/
if($registrert) {
      echo "<h1>Bekreftelse p&aring; registrering</h1>";
      echo '<p class="success">Registreringen var vellykket!</p>';
      echo '<div class="monoSpaced">';
      echo '<p><strong>Eier:</strong> '.htmlentities($_POST['eier']).'</p>';
      echo '<p><strong>Skap:</strong> '.$_POST['selSkap'].'</p>';
      echo '<p><strong>Etasje:</strong> '.$skap->getRom().'</p>';
      echo '<p><strong>Bygg:</strong> '.$skap->getBygg().'</p>';
      echo '<p><strong>Pris:</strong> '.PRIS.'</p>';
      echo '</div>';
      echo '<p>For &aring; fullf&oslash;re betalingen:</p>';
      echo '<ol><li><a href="javascript:window.print()">Skriv ut denne siden</a></li><li>Fest betaling for [insert current semester] p&aring; utskriften</li><li>Legg det i postkassa ved [POSTKASSEDESC]</li></ol>';
    } else {
      echo "<h1>Registrer ditt eierskap</h1>";
      if($errs) echo '<p class="warning">Det oppstod feil under registrering:</p>';
      else echo '<p>Fyll ut f&oslash;lgende skjema og send det inn.</p>';
      $forms->register('regsugg', $page, $db, $errs);
    }
?>
<p>Om du beh&oslash;ver assistanse kan du <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.<p>