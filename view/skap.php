<?php
/*
	Page: view/skap.php
	Desc: Lists all skap, and where the magic really happens (TODO: CHANGE DESC TEXT)
*/
echo '<h1>Skap</h1>';

echo '<p>Halla!</p>';

echo '<p class="building-tabs-list">';
    echo '<span class="building-tab selected-tab" id="tab-ny">Nybygget</span>';
    echo '<span class="building-tab" id="tab-gamle">Gamlebygget</span>';
echo '</p>'; // .building-tabs-list

// Tools
echo '<div id="toolbox">';
echo '<p class="floatLeft">';
    echo '<input type="radio" name="visSkap" id="visAlle" value="all" checked="checked"><label for="visAlle">Alle</label>';
    echo '<input type="radio" name="visSkap" id="visUbetalt" value="ubet"><label for="visUbetalt">Ubetalte</label>';
    echo '<input type="radio" name="visSkap" id="visBetalt" value="bet"><label for="visBetalt">Betalte</label>';
    echo '<input type="radio" name="visSkap" id="visKlippet" value="kli"><label for="visKlippet">Klippet</label></p>';
echo '<p class="floatRight"><label for="skapFilter">S&oslash;k: </label><input type="text" id="skapFilter" value=""></p>';
echo '</div>'; // .toolbox

echo '<div id="nybygget" class="clearfix">';
createTable('nybygget', $skap_nybygg);
echo '</div>';

echo '<div id="gamlebygget" style="display: none;">';
createTable('gamlebygget', $skap_gamleb);
echo '</div>';

echo '<div class="clearer clearfix grayTopLine">&nbsp;</div>'; // Boxes it in
?>
<script type="text/javascript">
$("button").on("click", function() {
    var id = $(this).attr("id").substring(6);
    var parTr = $(this).closest("tr");
    var parTd = $(this).closest("td");
    
    // Everything set, now's the time to find out what button it is
    var butClass = $(this).attr("class"); // btn-grey, btn-red, btn-green
    var butAction = "";
    switch (butClass) {
        case 'btn-green':
            butAction = "betal";
            break;
        case 'btn-red':
            butAction = "klipp";
            break;
        case 'btn-grey':
            butAction = "angre";
            break;
        default:
            butAction = "angre";
            break;
    }
    var skapAction = {
        skapnr: id,
        token: '<?php echo $_SESSION['token']; ?>',
        action: butAction
    };
    $.ajaxSetup ({
        type: "POST",
        url: 'http://psychaid.no/skap/psyjax.php' // Does not like requests from www-subdomain!
    });
    $.ajax({
        data: skapAction
    }).done(function( response ) {
        switch (response) {
            case 'BETALT':
                parTr.addClass("success");
                parTd.children("button.btn-green").hide();
                parTd.children("button.btn-red").hide();
                parTd.children("button.btn-grey").show();
                break;
            case 'KLIPPET':
                parTr.addClass("error");
                parTd.children("button.btn-green").hide();
                parTd.children("button.btn-red").hide();
                parTd.children("button.btn-grey").show();
                break;
            case 'ANGRET':
                parTr.removeClass(); // fjerner .success/.error om det finnes
                parTd.children("button.btn-green").show();
                parTd.children("button.btn-red").show();
                parTd.children("button.btn-grey").hide();
                break;
            case 'FEIL':
                alert("Det oppstod en feil i systemet!");
                break;
            default:
                break;
        }
    });
});

// Filter search
$("#skapFilter").keyup(function () {
    //split the current value of searchInput
    var data = this.value.split(" ");
    //create a jquery object of the rows
    var jo = $(".skapTableBody").find("tr");
    if (this.value == "") {
        jo.show();
        return;
    }
    //hide all the rows
    jo.hide();

    //Recusively filter the jquery object to get results.
    jo.filter(function (i, v) {
        var $t = $(this);
        for (var d = 0; d < data.length; ++d) {
            if ($t.is(":contains('" + data[d] + "')")) {
                return true;
            }
        }
        return false;
    })
    //show the rows that match.
    .show();
}).focus(function () {
    this.value = "";
    $(this).unbind('focus');
});

// Tabs
$("#tab-ny").click(function () {
    $(this).addClass("selected-tab");
    $("#tab-gamle").removeClass("selected-tab");
    $("div#gamlebygget").hide();
    $("div#nybygget").show();
    retur
});
$("#tab-gamle").click(function () {
    $(this).addClass("selected-tab");
    $("#tab-ny").removeClass("selected-tab");
    $("div#nybygget").hide();
    $("div#gamlebygget").show();
});
</script>