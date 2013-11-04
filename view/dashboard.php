<?php
/*
	Page: dashboard.php
	Desc: Dashboard, containing current tasks and messages.
*/
?>
<h1>God ettermiddag, <?php echo $user['username']; ?>.</h1>
<div class="module-stor">
	<h2>Skap, <?php echo $sem['id']; ?></h2>
	<?php skapStatsTable($alleSkap); ?>
</div>
<div class="module-liten">
	<?php 
		acb_o();
			acb("skap","", "Skapoversikt", "Registrer betaling, klipping");
			acb("semester", "", "Semesteroversikt", "Se skaphistorikk for tidligere semestre");
			acb("innstillinger", "", "Endre brukerinfo", "Endre e-post/passord");
		acb_c();
	?>
</div>
<p class="grayTopLine clearer">&nbsp;</p>
<p class="greyed">Awesome Testere: Thomas, Robin, Erik, Eirik og Jone</p>