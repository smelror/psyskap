<?php
/*
=================
	PSYSKAP
	INSTALL
	v 0.5
=================
*/

include_once 'includes/classes/class.db.php';
include_once 'includes/classes/class.forms.php';

$skapfile = "skap_all.csv";
$dbs = new PsyDB();
$db = $dbs->getCon();

print "Oppretter database-tabeller: \n";

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
echo "OK! \n";

// Create 'forslag' - should work
echo "Oppretter forslag...";
$db->exec("
	CREATE TABLE IF NOT EXISTS forslag (
		navn varchar(30) NOT NULL,
		skap varchar(5) NOT NULL
	);");
echo "OK! \n";

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
echo "OK! \n";

// Create 'errorlog' - should work
echo "Oppretter errorlog...";
$db->exec("
	CREATE TABLE IF NOT EXISTS errorlog (
		id 	int(4)	NOT NULL AUTO_INCREMENT,
		melding text NOT NULL,
		dato TIMESTAMP(8),
		PRIMARY KEY(id)
	);");
echo "OK! \n";

// Create 'semester'
// TO BE COMPLETED LATER
// -- V

print "Alle tabeller opprettet. \n";

// Populate 'skap'
if (($handle = fopen($skapfile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ":")) !== FALSE) {

    	$ins_skap = $data[0];
    	$ins_rom = $data[1];
    	$ins_bygg = $data[2];
    	$ins_pris = $data[3];

		$q = $db->prepare("INSERT INTO skap (skapnr, rom, bygg, pris) VALUES (:skap, :rom, :bygg, :pris)");
		$q->execute(array(
			'skap' => $ins_skap,
			'rom' => $ins_rom,
			'bygg' => $ins_bygg,
			'pris' => $ins_pris
			));
		//print '> '.$ins_skap.' '.$ins_rom.' '.$ins_bygg.' '.$ins_pris.' inserted.';

    }
    fclose($handle);
} 
print "All skaps added \n";


// Registering admin
$form = new PsyForms();
$form->registerUser('PsychAid', 'Administrator', 'admin@psychaid.no', 'cutTheLock', '00000000');
$q = $db->prepare("UPDATE brukere SET type=:type WHERE epost = :epost");
$q->execute(array(
	'type' => 4,
	'epost' => 'admin@psychaid.no'
	));
$form = null;

$dbs->showAllUsers();

$db = null;
$dbs = null;
print "Admin registered. \n";
print "Everything done; go ahead, have a nice day!";
?>