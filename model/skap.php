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
				<th class="nummer alignCenter">#</th>
				<th class="rom alignCenter">Etg</th>
				<th class="eier">Eier</th>
				<th class="bet">Betalt</th>
				<th class="merknad noBorder">Merknad</th>
			</tr>
		  </thead>
		  <tbody class="skapTableBody">';
	foreach ($skap_array as $skap) {
		echo '<tr id="'.$skap->getNr().'"';
		if($skap->getStatus() == 1) echo 'class="success"';
		elseif($skap->getStatus() == 2) echo 'class="error"';
		echo '>';
		echo '<td class="alignCenter">'.$skap->getNr().'</td>';
		echo '<td class="alignCenter">'.$skap->getRom().'</td>';
		echo '<td class="owner">'.$skap->getEier().'</td>';
		echo '<td class="betaling">';
		if($skap->getStatus() == 0) {
			echo '<button type="button" class="btn-green" id="betal-'.$skap->getNr().'">Betalt</button>';
			echo '<button type="button" class="btn-red" id="klipp-'.$skap->getNr().'">Klipp</button>';
			echo '<button type="button" style="display: none;" class="btn-grey" id="angre-'.$skap->getNr().'">Angre</button></td>';	
		} else {
			echo '<button type="button" style="display: none;" class="btn-green" id="betal-'.$skap->getNr().'">Betalt</button>';
			echo '<button type="button" style="display: none;" class="btn-red" id="klipp-'.$skap->getNr().'">Klipp</button>';
			echo '<button type="button" class="btn-grey" id="angre-'.$skap->getNr().'">Angre</button></td>';	
		}
		echo '</td>';
		echo '<td>'.$skap->getMerknad().'</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';	
}
?>