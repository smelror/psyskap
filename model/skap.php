<?php

$alleSkap = $db->getAllSkap();
$skap_nybygg = array();
$skap_gamleb = array();
foreach ($alleSkap as $s) {
	$tempSkap = new Skap($s);
	if($s['bygg'] == 'Nybygg') $skap_nybygg[] = $tempSkap;
	else $skap_gamleb[] = $tempSkap;
}

?>