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
				<th class="merknad noBorder alignCenter">Merknad</th>
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
			echo '<button type="button" style="display: none;" class="btn-grey" id="angre-'.$skap->getNr().'">Angre</button>';	
		} else {
			echo '<button type="button" style="display: none;" class="btn-green" id="betal-'.$skap->getNr().'">Betalt</button>';
			echo '<button type="button" style="display: none;" class="btn-red" id="klipp-'.$skap->getNr().'">Klipp</button>';
			echo '<button type="button" class="btn-grey" id="angre-'.$skap->getNr().'">Angre</button>';
		}
		echo '</td>';
		echo '<td class="merknad ';
		if($skap->getMerknad() != '') echo 'has-comment" title="'.$skap->getMerknad();
		else echo 'no-comment';
		echo '"></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';	
}

function load_js() { ?>
	<script type="text/javascript">
	$.ajaxSetup ({
	    type: "POST",
	    url: 'http://psychaid.no/skap/psyjax.php' // Does not like requests from www-subdomain!
	});
	$("button").on("click", function() {
	    var id = $(this).attr("id").substring(6);
	    var parTr = $(this).closest("tr");
	    var parTd = $(this).closest("td");
	    
	    // Everything set, now's the time to find out what button it is
	    var butClass = $(this).attr("class"); // btn-grey, btn-red, btn-green
	    var butAction;
	    switch (butClass) {
	        case 'btn-green': butAction = "betal"; break;
	        case 'btn-red': butAction = "klipp"; break;
	        case 'btn-grey': butAction = "angre";break;
	        default: butAction = "angre"; break;
	    }
	    var skapAction = {
	        skapnr: id,
	        token: '<?php echo $_SESSION['token']; ?>',
	        action: butAction
	    };
	    $.ajax({
	        data: skapAction
	    })
	    .done(function( response ) {
	        var rdiofiltr = $("input[name='visSkap']:checked").attr('id').substring(3);
	        switch (response) {
	            case 'BETALT':
	                parTr.addClass("success");
	                parTd.children("button.btn-green").hide();
	                parTd.children("button.btn-red").hide();
	                parTd.children("button.btn-grey").show();
	                if( (rdiofiltr !== 'betalt') && (rdiofiltr !== 'Alle') )  { parTr.fadeOut(); }
	                break;
	            case 'KLIPPET':
	                parTr.addClass("error");
	                parTd.children("button.btn-green").hide();
	                parTd.children("button.btn-red").hide();
	                parTd.children("button.btn-grey").show();
	                if( (rdiofiltr !== 'error') && (rdiofiltr !== 'Alle') )  { parTr.fadeOut(); }
	                break;
	            case 'ANGRET':
	                parTr.removeClass(); // fjerner .success/.error om det finnes
	                parTd.children("button.btn-green").show();
	                parTd.children("button.btn-red").show();
	                parTd.children("button.btn-grey").hide();
	                if( (rdiofiltr !== 'Ubetalt') && (rdiofiltr !== 'Alle') )  { parTr.fadeOut(); }
	                break;
	            case 'FEIL':
	                alert("Det oppstod en feil i systemet!");
	                break;
	            default:
	                break;
	        }
	    });
	});
	// Adds textarea for adding/editing owner comments
	$("td.merknad").on('click', function() {
		if( $("#editComment").length ) { return; } // Only one at a time!
		if( !$(this).hasClass("has-comment") ) {
			$(this).append('<div class="triangle-border left" id="comBubble"><textarea id="editComment"cols="30" rows="4"></textarea></div>');
		} else {
			var comment = $(this).attr('title');
			$(this).append('<div class="triangle-border left" id="comBubble"><textarea id="editComment" cols="30" rows="4">'+comment+'</textarea></div>');
		}
		$("#editComment").putCursorAtEnd();
	});

	$(document).on('blur', '#editComment', function() {
		var com = $("#editComment").val();
		var parTr = $("#editComment").closest("tr");
	    var parTd = $("#editComment").closest("td");
	    if( parTd.attr('title') === com) { 
	    	$('div#comBubble').remove();
	    	return;
	    }
	    if( parTd.hasClass('no-comment') && com.length) {
	    	parTd.removeClass('no-comment');
	    	parTd.addClass('has-comment');
	    }
	    if( parTd.hasClass('has-comment') && !com.length) {
	    	parTd.removeClass('has-comment');
	    	parTd.addClass('no-comment');
	    	parTd.removeAttr('title');
	    }
		var skapAction = {
			skapnr: parTr.attr('id'),
			token: '<?php echo $_SESSION['token']; ?>',
	        action: 'setMerknad',
	        merknad: com
		};
		$.ajax({
			data: skapAction
		})
		.done(function (response) {
			console.log(response+" merknad: " + com);
			parTd.attr('title', com);
		})
		.fail(function (response) {
			alert("Merknad ble ikke lagret!");
		});
		$('div#comBubble').remove(); // Removes both #comBubble and #editComment
	});

	// Add input field for adding/editing owner name
	$("td.owner").on('click', function() {
	    if( $("#editOwner").length ) { return; }
	    if( !$(this).html() )  {
	        $(this).append('<input type="text" id="editOwner" value="">');
	    } else {
	        var name = $(this).text();
	        $(this).attr('id', name);
	        $(this).empty();
	        $(this).append('<input type="text" id="editOwner" value="'+name+'">');
	    }
	    $("#editOwner").putCursorAtEnd();
	});
	$(document).on('blur','input#editOwner', function() {
	    var parTr = $(this).closest("tr");
	    var parTd = $(this).closest("td");
	    var name = $(this).val().replace(/[^A-Za-z]+/g, ''); // Allows only a-z and no spacing
	    name = name.toLowerCase();
	    if(parTd.attr('id') === name) { 
	        $(this).remove();
	        parTd.removeAttr('id');
	        parTd.append(name);
	        return;
	    }
	    var skapAction = {
	        skapnr: parTr.attr('id'),
	        token: '<?php echo $_SESSION['token']; ?>',
	        action: 'setName',
	        name: name
	    };
	    $.ajax({
	        data: skapAction
	    })
	    .done(function( response ) {
	        parTd.append(name);
	    })
	    .fail(function( response ) {
	        alert("Navnendring skjedde ikke: " + response);
	    });
	    parTd.removeAttr('id');
	    $(this).remove();
	});
	$(document).on('keypress', 'input#editOwner', function(e) {
	    if(e.which == 13) {
	        $('input#editOwner').blur();
	    }
	});
	// Filter radio buttons
	$("input[name='visSkap']").on('change', function() {
	    var selAct = $(this).attr('id').substring(3); // Selected action
	    var allRows = $(".skapTableBody").find("tr");
	    if(selAct === "Alle") {
	        allRows.show();
	        return; // Breaks the filtering process
	    }
	    allRows.hide();
	    if(selAct === "Ubetalt") {
	        allRows.show();
	        allRows.filter(".error").hide();
	        allRows.filter(".success").hide();
	    } else {
	        allRows.filter("."+selAct).show();
	    }
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
	});
	$("#tab-gamle").click(function () {
	    $(this).addClass("selected-tab");
	    $("#tab-ny").removeClass("selected-tab");
	    $("div#nybygget").hide();
	    $("div#gamlebygget").show();
	});
	// http://css-tricks.com/snippets/jquery/move-cursor-to-end-of-textarea-or-input/
	jQuery.fn.putCursorAtEnd = function() {
	  return this.each(function() {
	    $(this).focus()
	    // If this function exists...
	    if (this.setSelectionRange) {
	      // ... then use it (Doesn't work in IE)
	      // Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
	      var len = $(this).val().length * 2;
	      this.setSelectionRange(len, len);
	    } else {
	    // ... otherwise replace the contents with itself
	    // (Doesn't work in Google Chrome)
	      $(this).val($(this).val());
	    }
	    // Scroll to the bottom, in case we're in a tall textarea
	    // (Necessary for Firefox and Google Chrome)
	    this.scrollTop = 999999;
	  });
	};
	</script><?php
}
?>