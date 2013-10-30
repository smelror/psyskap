<?php
/*
 Page: velkommen.php
 Desc: Velkomstsiden til PsySkap. Gir brukeren valg i form av action buttons.
 */
?>
<h1>Velkommen til PsySkap <span class="greyed"><?php echo VERSION; ?></span></h1>
<p>PsySkap er en tjeneste for deg som benytter bokskap p&aring; PSI/UiO.</p>
<?php
	acb_o();
		acb("finn", "btnFinn", "Sjekk skapstatus", "Sjekk statusen til ditt skap");
		acb("register", "btnReg", "Registrer ditt skap", "");
	acb_c();
?>
<p>Om du ikke finner det du leter etter, vennligst <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.</p>