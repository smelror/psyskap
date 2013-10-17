<?php
/*
	Page: view/skap.php
	Desc: Lists all skap, and where the magic really happens (TODO: CHANGE DESC TEXT)
*/
echo '<h1>Skap</h1>';
echo '<h2>Nybygget</h2>';
createTable('nybygget', $skap_nybygg);

echo '<h2>Gamlebygget</h2>';
createTable('gamlebygget', $skap_gamleb);


/*
Plan:
0. To divs, 40/60 fordeling
1. Byggtables fra $db->getAllSkap()
2. Hver <tr> får egen id
3. Oppsett: skapnr, eier, pris
4. Klikk på <tr>, laste skjema inn i toolbox-div
5. Post endringer via jquery (ajax)
6. oppdater tabell
*/



function createTable($id, $skap_array) {
	echo '<table id="'.$id.'">';
	echo '<thead>
			<tr>
				<td>Skapnr</td>
				<td>Etg</td>
				<td>Eier</td>
				<td>Betalt</td>
				<td>Merknad</td>
			</tr>
		  </thead>
		  <tbody>';
	foreach ($skap_array as $skap) {
		echo '<tr id="'.$skap->getNr().'">';
		echo '<td>'.$skap->getNr().'</td>';
		echo '<td>'.$skap->getRom().'</td>';
		echo '<td>'.$skap->getEier().'</td>';
		if($skap->isBetalt()) {
			echo '<td>Ja.</td>';	
		} else {
			echo '<td><button type="button" class="btn-betal" id="betal-'.$skap->getNr().'">Betalt</button></td>';	
		}
		echo '<td>'.$skap->getMerknad().'</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';	
}
?>