<?php
/*
 Page: register.php
 Desc: Registreringssiden for skapeiere; fyll ut skjema som sendes til forslags-tabellen.
*/
?>
<h1>Registrer ditt eierskap</h1>
<?php
if(isset($_POST['selSkap']) && $_POST['selSkap']) {
  if($errors = $forms->valdiate_register($_POST['eier'], $_POST['selSkap'], $db)) {
    echo '<p>Det oppstod feil under registrering:</p>';
    $forms->register('regsugg', $page, $db, $errors);
  } else {
    // Valid reg
    if($db->addSuggestion($_POST['eier'], $_POST['selSkap'])) {
      echo '<p class="success">Registreringen var vellykket! Tusen takk for registreringen.</p>';
      // Add print-format?
    } else {
      echo '<p class="warning">Det oppstod en systemfeil. Vennligst prøv igjen senere.</p>';
    }
  }
 } else {
  echo '<p>Fyll ut f&oslash;lgende skjema og send det inn.</p>';
  $forms->register('regsugg', $page, $db);
  }
?>
<p>Om du beh&oslash;ver assistanse kan du <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.<p>