<?php
/*
 Page: register.php
 Desc: Registreringssiden for skapeiere; fyll ut skjema som sendes til forslags-tabellen.
*/
if(isset($_POST['selSkap']) && $_POST['selSkap']) {
  if($errors = $forms->valdiate_register($_POST['eier'], $_POST['selSkap'], $db)) {
    echo "<h1>Registrer ditt eierskap</h1>";
    echo '<p>Det oppstod feil under registrering:</p>';
    $forms->register('regsugg', $page, $db, $errors);
  } else {
    // Valid reg
    if($db->addSuggestion($_POST['eier'], $_POST['selSkap'])) {
      echo "<h1>Bekreftelse p&aring; registrering</h1>";
      echo '<p class="success">Registreringen var vellykket! Tusen takk for registreringen.</p>';
      echo '<div class="monoSpaced">';
      echo '<p><strong>Eier:</strong> '.$_POST['eier'].'</p>';
      echo '<p><strong>Skap:</strong> '.$_POST['selSkap'].'</p>';
      $skap = new Skap($db->getSkap($_POST['selSkap']));
      echo '<p><strong>Etasje:</strong> '.$skap->getRom().'</p>';
      echo '<p><strong>Bygg:</strong> '.$skap->getBygg().'</p>';
      echo '<p><strong>Pris:</strong> '.$skap->getPris().'</p>';
      echo '</div>';
      echo '<p>Vennligst skriv ut denne siden og legg med betaling for semesteret [insert current semester], om du ikke allerede har betalt.</p>';
    } else {
      echo '<p class="warning">Det oppstod en systemfeil. Vennligst prøv igjen senere.</p>';
    }
  }
 } else {
  echo "<h1>Registrer ditt eierskap</h1>";
  echo '<p>Fyll ut f&oslash;lgende skjema og send det inn.</p>';
  $forms->register('regsugg', $page, $db);
  }
?>
<p>Om du beh&oslash;ver assistanse kan du <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.<p>