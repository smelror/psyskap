<?php
/*
 Page: velkommen.php
 Desc: Velkomstsiden til PsySkap. Gir brukeren valg i form av action buttons.
 */
?>
<h1>Velkommen til PsySkap</h1>
<p>PsySkap er en tjeneste for deg som benytter bokskap p&aring; PSI/UiO.</p>
<p>Om du ikke har et bokskap, m&aring; du f&oslash;rst ta ett ved &aring; l&aring;se det med hengel&aring;s.</p>
<?php
	acb_o();
		acb("finn", "btnFinn", "Sjekk skapstatus", "Se om skapet er registrert og betalt");
		acb("register", "btnReg", "Registrer ditt skap", "Forenkler betalingen for semestert");
	acb_c();
?>
<p>Om du ikke finner det du leter etter, vennligst <a href="http://www.psychaid.no/kontakt/">ta kontakt med oss</a>.</p>