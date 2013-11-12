<?php
/*
	Page: view/skap.php
	Desc: Lists all skap, and where the magic really happens (TODO: CHANGE DESC TEXT)
*/
echo '<p class="building-tabs-list">';
    echo '<span class="building-tab selected-tab" id="tab-ny">Nybygget</span>';
    echo '<span class="building-tab" id="tab-gamle">Gamlebygget</span>';
echo '</p>'; // .building-tabs-list

// Tools
echo '<div id="toolbox" class="clearfix">';
echo '<p><strong>Vis</strong><br>';
    echo '<input type="radio" name="visSkap" id="visAlle" value="all" checked="checked"><label for="visAlle">Alle</label><br>';
    echo '<input type="radio" name="visSkap" id="visUbetalt" value="ubet"><label for="visUbetalt">Ubetalte</label><br>';
    echo '<input type="radio" name="visSkap" id="vissuccess" value="bet"><label for="vissuccess">Betalte</label><br>';
    echo '<input type="radio" name="visSkap" id="viserror" value="kli"><label for="viserror">Klippet</label></p>';
echo '<p><label for="skapFilter"><strong>Finn</strong></label><br><input placeholder="Eks: 231" type="text" id="skapFilter" value=""></p>';
echo '</div>'; // .toolbox

echo '<div id="nybygget" class="clearfix">';
createTable('nybygget', $skap_nybygg);
echo '</div>';

echo '<div id="gamlebygget" class="clearix" style="display: none;">';
createTable('gamlebygget', $skap_gamleb);
echo '</div>';

echo '<div class="clearer clearfix grayTopLine">&nbsp;</div>'; // Boxes it in
?>