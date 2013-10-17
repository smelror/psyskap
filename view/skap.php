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
?>
<script type="text/javascript">
$("button.btn-green").click(function () {
	var skap = $(this).attr("id");
	$(this).text(" Laster ");
	alert(skap);
});
</script>