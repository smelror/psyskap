<?php
/*
=================
	PSYSKAP
	INSTALL
	v 1.0.1
=================
*/
include_once 'includes/classes/class.db.php';
include_once 'includes/classes/class.forms.php';
include 'config.php';

$dbs = new PsyDB();
$form = new PsyForms();
$db = $dbs->getCon();

if(!isset($_POST['addModerator'])) {
	print "<p><strong>STEG 1</strong></p>";
	print "<p>Oppretter database-tabeller:</p>";

	// Create 'eiere' - works!
	echo "<p>Opretter eiere...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS eiere (
			id	int	NOT NULL AUTO_INCREMENT,
			navn varchar(30) NOT NULL,
			saldo int(5) NOT NULL,
			betalt_tom varchar(3) NOT NULL,
			type int(1) NOT NULL DEFAULT '1',
			merknad text,
			skap varchar(5) NOT NULL,
			PRIMARY KEY(id)
		);");
	echo "OK!</p>";

	// Create 'forslag' - works!
	echo "<p>Oppretter forslag...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS forslag (
			navn varchar(30) NOT NULL,
			skap varchar(5) NOT NULL,
			PRIMARY KEY(navn)
		);");
	echo "OK!</p>";

	// Create 'skap' - works!
	echo "<p>Oppretter skap...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS skap (
			skapnr varchar(5) NOT NULL,
			rom varchar(8) NOT NULL,
			bygg varchar(8) NOT NULL,
			pris int(2) NOT NULL,
			eier int(10) NOT NULL,
			PRIMARY KEY(skapnr)
		);");
	echo "OK!</p>";

	// Create 'errorlog' - works!
	echo "<p>Oppretter errorlog...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS errorlog (
			id 	int(4)	NOT NULL AUTO_INCREMENT,
			melding text NOT NULL,
			dato TIMESTAMP(8),
			PRIMARY KEY(id)
		);");
	echo "OK!</p>";

	// Create 'users' - works!
	echo "<p>Oppretter users...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS users (
			id int NOT NULL AUTO_INCREMENT,
			username varchar(30) NOT NULL,
			password varchar(64) NOT NULL,
			salt varchar(3) NOT NULL,
			epost varchar(65) NOT NULL,
			created TIMESTAMP DEFAULT NOW(),
			PRIMARY KEY(id)
			);");
	echo "OK!</p>";

	// Create 'semester' - works!
	echo "<p>Oppretter semester...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS semester (
			id varchar(3) NOT NULL,
			antall_eiere int(4),
			current int(1) NOT NULL DEFAULT '0',
			activated_by varchar(30) NULL,
			PRIMARY KEY(id)
			);");
	echo "OK!</p>";


	print "<p>Alle tabeller opprettet.</p>";

	// read 'skap' file, populate array
	$all_skap = array();
	$skapfile = "skap.csv";
	if (($handle = fopen($skapfile, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ":")) !== FALSE) {
		$skapadd = array(
			'skapnr' => $data[0],
			'rom' => $data[1],
			'bygg' => $data[2],
			'pris' => $data[3]		
		);
		array_push($all_skap, $skapadd);
	    }
	    fclose($handle);
	} else {
		die("Could not find or read skap.csv file. The installation has stopped.");
	}

	// check if table is empty
	$q = $db->prepare('SELECT FROM skap');
	$q->execute();
	$count = $q->rowCount();
	$skapcount = 0;
	// if table is empty, populate it
	if($count == 0) {
		// Insert skap into db - should work (better?)
		$q = $db->prepare("INSERT INTO skap (skapnr, rom, bygg, pris) VALUES (:skapnr, :rom, :bygg, :pris)");
		foreach ($all_skap as $skap) {
			$q->execute($skap);
			$skapcount++;
		}
	}
	if($skapcount == 705) print "<p>All skap registrert.</p>";
	else print '<p class="warning">Ikke alle skap ble registrert.</p>';

	// Legger til semestre og setter current == nåværende semester
	echo '<p>Legger til semestre i database...';
	$semestre = array();
	$y = date('y');
	for ($x = 0; $x < 3; $x++) {
		$semestre[] = "H".($y+$x);
		$semestre[] = "V".($y+$x);
	}
	$q = $db->prepare("INSERT INTO semester (id, antall_eiere, current) VALUES (:sem, 0, 0)");
	foreach ($semestre as $sem) {
		$q->execute(array('sem' => $sem));
	}
	echo 'OK!</p>';
	$cur = date('y');
	if (date('n') < 6) $cur = "V".$cur;
	else $cur = "H".$cur;
	echo '<p>Setter '.$cur.' som aktiv semester...';
	$q = $db->prepare("UPDATE semester SET current = 1 WHERE id = ?");
	$q->execute(array($cur));
	echo 'OK!</p>';


print "<p>Registrer moderator:</p>";
$form->addMod(null, 'addModForm', 'install'); // Posts to install.php?step=2
$db = null;
$dbs = null;
} // step 1


if(isset($_POST['addModerator']) && $_POST['addModerator']) {
	print "<p><strong>STEG 2</strong></p>";
	
	// Validate addMod-form
	if($errors = $form->validate_addMod($_POST['mod'], $_POST['pwd'], $_POST['epost'], $db)) {
		print "<p><strong>Det oppstod feil under moderator-oppretting:</strong></p>";
		$form->addMod($errors, 'addModForm', 'install');
	} else {
		// Valid mod, add mod
		print "<p>Registrerer moderator for: ".$_POST['epost'];
		$dbs->addMod($_POST['mod'], $_POST['pwd'], $_POST['epost']);
		print "... OK!</p>";

		// Send email to new moderator
		$message = "
			Hei!<br><br>
			Din epostadresse er registrert som moderator i PsySkap (http://www.psychaid.no/skap), og kan logge inn via<br>
			http://www.psychaid.no/skap/?p=login <br><br>
			usr: ".$_POST['mod']." <br>
			pwd: ".$_POST['pwd']." <br><br>
			Vi anbefaler at du endrer passord etter å ha logget inn. <br><br>
			Mvh, PsychAid <br>
			skap@psychaid.no";
		$maildata = array(
			'to_email' => $_POST['epost'],
			'to_name' => $_POST['mod'],
			'from_email' => 'skap@psychaid.no',
			'from_name' => 'PsySkap',
			'subject' => 'PsySkap: Moderator opprettet',
			'message' => $message
			);
		if(mail_send($maildata)) {
			print "<p>E-post med brukernavn\passord er sendt.</p>";
		}
		print "<p>Innstallasjon fullf&oslash;rt uten problemer.</p>";
		echo '<p>G&aring; til <a href="index.php?p=velkommen">PsySkap-forsiden</a> eller <a href="index.php?p=login">logg inn</a>.</p>';
	}
	$db = null;
	$dbs = null;
	$form = null;
} // step 2
?>
