<?php

$alleSkap = $db->getAllSkap();
$skap_nybygg = array();
$skap_gamleb = array();
foreach ($alleSkap as $s) {
	$tempSkap = new Skap($s);
	if($s['bygg'] == 'Nybygg') $skap_nybygg[] = $tempSkap;
	else $skap_gamleb[] = $tempSkap;
}

function createTable($id, $skap_array) {
	echo '<table class="skapTable" id="'.$id.'">';
	echo '<thead>
			<tr>
				<th class="alignCenter">Skapnr</th>
				<th class="alignCenter">Etg</th>
				<th>Eier</th>
				<th>Betalt</th>
				<th class="noBorder">Merknad</th>
			</tr>
		  </thead>
		  <tbody>';
	foreach ($skap_array as $skap) {
		echo '<tr id="'.$skap->getNr().'">';
		echo '<td class="nummer alignCenter">'.$skap->getNr().'</td>';
		echo '<td class="rom alignCenter">'.$skap->getRom().'</td>';
		echo '<td>'.$skap->getEier().'</td>';
		if($skap->isBetalt()) {
			echo '<td>Ja.</td>';	
		} else {
			echo '<td><button type="button" class="btn-green" id="betal-'.$skap->getNr().'">Betalt</button></td>';	
		}
		echo '<td>'.$skap->getMerknad().'</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';	
}
?>