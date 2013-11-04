<?php
$user = $db->getUser($_SESSION['userid']);
$sem = $db->get_current_semester();
$alleSkap = $db->getAllSkap();

function skapStatsTable($alleSkap) {
	$nybygg = array('eiere' => 0, 'betalt' => 0, 'klipp' => 0, 'ledig' => 0);
	$gamleb = array('eiere' => 0, 'betalt' => 0, 'klipp' => 0, 'ledig' => 0);
	foreach ($alleSkap as $s) {
		if(strlen($s['eier']) == 0) { ($s['bygg'] == 'Nybygg' ? $nybygg['ledig']++ : $gamleb['ledig']++); }
		else { ($s['bygg'] == 'Nybygg' ? $nybygg['eiere']++ : $gamleb['eiere']++); }
		if($s['status'] == 1) { ($s['bygg'] == 'Nybygg' ? $nybygg['betalt']++ : $gamleb['betalt']++); }
		if($s['status'] == 2) { ($s['bygg'] == 'Nybygg' ? $nybygg['klipp']++ : $gamleb['klipp']++); }
	}
	print '<table class="alignCenter">
			<thead>
				<tr>
					<td>&nbsp;</td>
					<th class="medium">Nybygget</th>
					<th class="medium">Gamlebygget</th>
					<th class="medium">TOTALT</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th class="liten">Ledige</th>
					<td>'.$nybygg["ledig"].'</td>
					<td>'.$gamleb["ledig"].'</td>
					<td>'.($nybygg["ledig"] + $gamleb["ledig"]).'</td>
				</tr>
				<tr>
					<th class="liten">Eiere</th>
					<td>'.$nybygg["eiere"].'</td>
					<td>'.$gamleb["eiere"].'</td>
					<td>'.($nybygg["eiere"] + $gamleb["eiere"]).'</td>
				</tr>
				<tr class="success">
					<th class="liten">Betalt</th>
					<td>'.$nybygg["betalt"].'</td>
					<td>'.$gamleb["betalt"].'</td>
					<td>'.($nybygg["betalt"] + $gamleb["betalt"]).'</td>
				</tr>
				<tr class="error">
					<th class="liten">Klipp</th>
					<td>'.$nybygg["klipp"].'</td>
					<td>'.$gamleb["klipp"].'</td>
					<td>'.($nybygg["klipp"] + $gamleb["klipp"]).'</td>
				</tr>
			</tbody>
		</table>';
}
?>