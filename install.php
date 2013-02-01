<?php
/*
=================
	PSYSKAP
	INSTALL
	v 0.9b
=================
*/
// Maximum execution time of 30 seconds exceeded - need to test more
if(!isset($_GET['step'])) { header('Location: '. $_SERVER['PHP_SELF'] .'?step=1'); }

include_once 'includes/classes/class.db.php';
include_once 'includes/classes/class.forms.php';
include 'config.php';

$dbs = new PsyDB();
$form = new PsyForms();
$db = $dbs->getCon();

$step = $_GET['step'];
if($step == 1) {
	print "<strong>STEG 1</strong>".PHP_EOL;
	print "Oppretter database-tabeller:".PHP_EOL;

	// Create 'eiere' - should work
	echo "Opretter eiere...";
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
	echo "OK!".PHP_EOL;

	// Create 'forslag' - should work
	echo "Oppretter forslag...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS forslag (
			navn varchar(30) NOT NULL,
			skap varchar(5) NOT NULL
		);");
	echo "OK!".PHP_EOL;

	// Create 'skap' - should work
	echo "Oppretter skap...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS skap (
			skapnr varchar(5) NOT NULL,
			rom varchar(8) NOT NULL,
			bygg varchar(8) NOT NULL,
			pris int(2) NOT NULL,
			eier int(10) NOT NULL,
			PRIMARY KEY(skapnr)
		);");
	echo "OK!".PHP_EOL;

	// Create 'errorlog' - should work
	echo "Oppretter errorlog...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS errorlog (
			id 	int(4)	NOT NULL AUTO_INCREMENT,
			melding text NOT NULL,
			dato TIMESTAMP(8),
			PRIMARY KEY(id)
		);");
	echo "OK!".PHP_EOL;

	// Create 'users' - should work
	echo "Oppretter users...";
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
	echo "OK!".PHP_EOL;

	// Create 'semester' - should work
	echo "Oppretter semester...";
	$db->exec("
		CREATE TABLE IF NOT EXISTS semester (
			id varchar(3) NOT NULL,
			antall_eiere int(4),
			current int(1) NOT NULL DEFAULT '0',
			activated_by varchar(30) NOT NULL,
			PRIMARY KEY(id)
			);");
	echo "OK!".PHP_EOL;


	print "Alle tabeller opprettet.".PHP_EOL;

	// read 'skap' file, populate array
	$all_skap = array();
	$skapfile = "skap.csv";
	if (($handle = fopen($skapfile, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ":")) !== FALSE) {
		// could work - if not use http://www.php.net/manual/en/function.array-push.php
		$all_skap .= array(
			'skapnr' => $data[0],
			'rom' => $data[1],
			'bygg' => $data[2],
			'pris' => $data[3]		
		);		//print '> '.$ins_skap.' '.$ins_rom.' '.$ins_bygg.' '.$ins_pris.' inserted.';
	    }
	    fclose($handle);
	} else {
		die("Could not find or read skap.csv file. The installation has stopped.");
	}
	// check if table is empty
	$q = $db->prepare('SELECT FROM skap');
	$q->execute();
	$count = $q->rowCount();
	// if table is empty, populate it
	if($count == 0) {
		// Insert skap into db - should work (better?)
		foreach ($all_skap as $skap) {
			$q = $db->prepare("INSERT INTO skap (skapnr, rom, bygg, pris) VALUES (:skapnr, :rom, :bygg, :pris)");
			$q->execute($skap);
		}
	}	
	print "All skap registrert.".PHP_EOL;

print PHP_EOL.PHP_EOL;
print "Registrer moderator:".PHP_EOL;
$form->addMod(null, 'addModForm', $_SERVER['PHP_SELF'] .'?step=2'); // Posts to install.php?step=2
$db = null;
$dbs = null;
} // step 1


if($step == 2) {
	print "<strong>STEG 2</strong>".PHP_EOL;
	
	// Validate addMod-form
	if($errors = $form->validateAddMod()) {
		print "<strong>Det oppstod feil under moderator-oppretting:</strong>".PHP_EOL;
		$form->addMod($errors, 'addModForm', $_SERVER['PHP_SELF'] .'?step=2');
	} else {
		// Valid mod, add mod
		print "Registrerer moderator for: ".$_POST['epost'];
		$db->addMod($_POST['mod'], $_POST['pwd'], $_POST['epost']);
		print "... OK!".PHP_EOL;

		// Send email to new moderator
		$message = "
			Hei!\n\n
			Din epostadresse er registrert som moderator i PsySkap (http://www.psychaid.no/skap), og kan logge inn via\n
			http://www.psychaid.no/skap/?p=login \n\n
			usr: ".$_POST['mod']." \n
			pwd: ".$_POST['pwd']." \n\n
			Vi anbefaler at du endrer passord etter å ha logget inn.";
		$maildata = array(
			'to_email' => $_POST['epost'],
			'to_name' => $_POST['mod'],
			'from_email' => 'skap@psychaid.no',
			'from_name' => 'PsySkap',
			'subject' => 'PsySkap: Moderator opprettet',
			'message' => $message
			);
		if(mail_send($maildata)) {
			print "E-post med brukernavn\passord er sendt.".PHP_EOL;
		}
		print "Innstallasjon fullført uten problemer.".PHP_EOL.PHP_EOL;
		echo 'Gå til <a href="?p=finn">PsySkap-forside</a> eller <a href="?p=login">logg inn</a>.';
	}
	$db = null;
	$dbs = null;
	$form = null;
} // step 2
?>
