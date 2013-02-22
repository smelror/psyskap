<?php
  /*
   Page: class.forms.php
   Desc: All forms are generated and validated here. Validation requires database connection.
  */
class PsyForms {
	
	public function finnSkap($id, $target, $db) {
	    $this->form_open($id, $target);
		$this->skapTree($db);
		$this->resetKey('skap');
		$this->input_hidden('skap', ''); // JS from skapTree() sets the value
		$this->resetKey('selSkap');
		$this->input_hidden("selSkap", '');
		// $this->input_submit('Se p&aring; skap'); // JS submits from select
	    $this->form_close();
	}

	public function login($errors = array()) {
		$this->form_open('formLogin', 'login');
		echo "<fieldset>";
		if($errors) {
			echo '<ul class="warning"><li>';
			echo implode('</li><li>',$errors);
			echo '</li></ul>';
		}
		$this->resetKey('usr');
		$this->input_text('usr', $_POST, "Brukernavn");
		$this->resetKey('pwd');
		$this->input_password('pwd', $_POST, "Passord");
		$this->input_submit('Logg inn');
		$this->input_hidden('_login_check', 1);
		echo "</fieldset>";
		$this->form_close();
	}
	public function validate_login($usr, $pwd, $db) {
		$errors = array();
		if($usr && $pwd) {
			try {
				$con = $db->getCon();
				$ps = $con->prepare("SELECT password, salt FROM users WHERE username = :user");
				$ps->execute(array(':user' => $usr));
				$userData = $ps->fetch(PDO::FETCH_ASSOC);
			} catch (PDOException $e) {
				$errors[] = "Validate.Login error: " . $e->getMessage() . "<br/>";
				die();
			}
			$hash = hash('sha256', $userData['salt'] . $pwd);
			if($hash != $userData['password']) {
				$errors[] = 'Feil brukernavn eller passord.';
			}
		} else {
			($username == '') ? $errors[] = "Vennligst fyll inn brukernavn." : null;
			($password == '') ? $errors[] = "Vennligst fyll inn passord.": null;
		}
		return $errors;
	}
	public function addMod($errors = array(), $id, $target) {
		$this->form_open($id, $target);
		if($errors) {
			echo '<ul class="warning"><li>';
			echo implode('</li><li>',$errors);
			echo '</li></ul>';
		}
		$this->resetKey('mod');
		$this->input_text('mod', $_POST, 'Brukernavn');
		$this->resetKey('pwd');
		$this->input_password('pwd', $_POST, 'Passord');
		$this->resetKey('epost');
		$this->input_text('epost', $_POST, 'E-post');
		$this->input_hidden('addModerator', 1);
		$this->input_submit('Opprett moderator');
		$this->form_close();
	}
	public function validate_addMod($mod, $pwd, $epost, $db) {
		$errors = array();
		if(!isset($mod, $pwd, $epost, $db)) { $errors[] = "Alle felt m&aring; fylles ut."; }
		if(!$this->validEmail($epost)) $errors[] = "Ugylding epostadresse.";
		if(strlen($pwd) < 5) $errors[] = "Passord m&aring; v&aelig;re lengre enn fem karakterer.";
		$q = $db->prepare("SELECT FROM users WHERE username = :user");
		$q->execute(array('user' => $mod));
		$count = $q->rowCount();
		if($count) $errors[] = "Brukernavnet er allerede registrert.";
		return $errors;
	}

	public function register($id, $target, $db, $errors = array()) {
		$this->form_open($id, $target);
		echo '<fieldset>';
		if($errors) {
			echo '<ul class="warning"><li>';
			echo implode('</li><li>',$errors);
			echo '</li></ul>';
		}
		$this->resetKey('eier');
		$this->input_text('eier', $_POST, 'UiO-brukernavn (ikke @student.sv.uio.no)');
		$this->skapTree($db);
		$this->resetKey('skap');
		$this->input_hidden('skap', ''); // JS from skapTree() sets the value
		$this->resetKey('selSkap');
		$this->input_text("selSkap", $_POST, 'Valgt skap');
		$this->input_submit('Registrer');
		echo '</fieldset>';
		$this->form_close();
	}

	public function valdiate_register($eier, $skapnr, $db) {
		$errors = array();
		if(empty($eier)) $errors[] = "UiO-brukernavn m&aring; fylles ut.";
		if(empty($skapnr)) $errors[] = "Skapnummer m&aring; velges.";
		$alleSkap = $db->getAllSkap();
		$alleSkapNr = array();
		foreach ($alleSkap as $s) {
			$alleSkapNr[] = $s['skapnr'];
		}
		if(!in_array($skapnr, $alleSkapNr)) $errors[] = "Ugyldig skapnummer.";
		if($this->validEmail($eier)) $errors[] = "Kun UiO-brukernavn skal fylles ut, ikke @&lt;uio-adresse&gt;.";
		if(preg_match("/[0-9]+/", $eier)) $errors[] = "UiO-brukernavn inneholder ikke tall.";
		return $errors;
	}

	private function skapTree($db) {
		$alleSkap = $db->getAllSkap();
		$skap_nybygg = array();
		$skap_gamleb = array();
		foreach ($alleSkap as $s) {
			$tempSkap = new Skap($s);
			if($s['bygg'] == 'Nybygg') $skap_nybygg[] = $tempSkap;
			else $skap_gamleb[] = $tempSkap;
		}
		// Set up building tabs

		echo '<div class="building-container">';
		echo '<p class="building-tabs-list">';
			echo '<span class="building-tab selected-tab" id="tab-ny">Nybygget</span>';
			echo '<span class="building-tab" id="tab-gamle">Gamlebygget</span>';
		echo '</p>'; // .building-tabs-list
		
		// Nybygget
		$etg_1 = array();
		$etg_2 = array();
		foreach ($skap_nybygg as $s) {
			if($s->getRom() == "1. etg") $etg_1[] = $s;
			else $etg_2[] = $s;
		}
		echo '<div id="nybygget">';
		echo '<div class="etasje clearfix"><p class="etg-button">1. Etasje</p>';
		echo '<ul class="skapListe clearfix" id="etg1" style="display: none">';
		foreach ($etg_1 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '<div class="etasje clearfix"><p class="etg-button">2. Etasje</p>';
		echo '<ul class="skapListe clearfix" id="etg2" style="display: none">';
		foreach ($etg_2 as $s) {
			echo '<li class="skap" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '</div>'; // #nybygget

		// Gamlebygget
		$V01_07	= array();
		$V01_01 = array();
		$N02_01 = array();
		$V01_06 = array();
		$V1U_18 = array();
		foreach ($skap_gamleb as $s) {
			if($s->getRom() == "V01-07") $V01_07[] = $s;
			elseif($s->getRom() == "V01-01") $V01_01[] = $s;
			elseif($s->getRom() == "N02-01") $N02_01[] = $s;
			elseif($s->getRom() == "V01-06") $V01_06[] = $s;
			else $V1U_18[] = $s;
		}
		echo '<div id="gamlebygget" style="display: none">';
		echo '<div class="etasje clearfix"><p class="etg-button">V01-07</p>';
		echo '<ul class="skapListe clearfix" id="V01-07" style="display: none">';
		foreach ($V01_07 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '<div class="etasje clearfix"><p class="etg-button">V01-01</p>';
		echo '<ul class="skapListe clearfix" id="V01-07" style="display: none">';
		foreach ($V01_01 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '<div class="etasje clearfix"><p class="etg-button">N02-01</p>';
		echo '<ul class="skapListe clearfix" id="V01-07" style="display: none">';
		foreach ($N02_01 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '<div class="etasje clearfix"><p class="etg-button">V01-06</p>';
		echo '<ul class="skapListe clearfix" id="V01-07" style="display: none">';
		foreach ($V01_06 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '<div class="etasje clearfix"><p class="etg-button">V1U-18</p>';
		echo '<ul class="skapListe clearfix" id="V01-07" style="display: none">';
		foreach ($V1U_18 as $s) {
			echo '<li class="skap transBackground" id="'.$s->getNr().'">'.$s->getNr().'</li>';
		}
		echo '</ul></div>';
		echo '</div>'; // #gamlebygget
		echo '<div class="clearer clearfix grayTopLine">&nbsp;</div>'; // Boxes it in
		echo '</div>'; // .building-container
		?>
		<script type="text/javascript">
			$("span#tab-ny").click(function () {
				$(this).addClass("selected-tab");
				$("span#tab-gamle").removeClass("selected-tab");
				$("div#gamlebygget").hide("slow");
				$("div#nybygget").show("slow");
			});
			$("span#tab-gamle").click(function () {
				$(this).addClass("selected-tab");
				$("span#tab-ny").removeClass("selected-tab");
				$("div#nybygget").hide("slow");
				$("div#gamlebygget").show("slow");
			});
			$("p.etg-button").click(function () {
				$(this).parent().children("ul").toggle("slow");
			});
			$("li.skap").click(function () {
				$('#skap').attr('value', $(this).attr("id"));
				$('#selSkap').attr('value', $(this).attr("id"));
				if($(this).parents('#finnSkap').length) {
					$("#finnSkap").submit();
				}
				else { $(this).parent().slideUp(); }
    		});
		</script>
		<?php
	}


	/*
	Internal tools
	*/
	private function form_open($id, $target) {
		return print '<form id="'.$id.'" method="POST" action="'.$_SERVER['PATH_INFO'].'">'; // ?p='.$target.'
	}
	private function form_close() {
		return print '</form>';
	}
	private function input_submit($text, $class='') {
		$submit = '<p>';
		($class) ? $submit .= '<input type="submit" class="'.$class.'" ' : $submit .= '<input type="submit" ';
		$submit .= 'value="'.$text.'"></p>';
		return print $submit;
	}
	private function input_hidden($name, $val) {
		return print '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$val.'">';
	}
	private function input_area($class = '', $name, $values) {
		if($class) $class = ' class="'.$class.'"';
		return print '<textarea'.$class.' id="'.$name.'" name="'.$name.'">'.htmlentities($values[$name]).'</textarea>';
	}
	private function input_text($field_name, $values, $labelname, $class = '') {
		$text = '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		$text .= '<input type="text" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		$text .= htmlentities($values[$field_name]) . '"></p>';
		return print $text;
	}
	private function input_password($field_name, $values, $labelname, $class = '') {
		$pwd = '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		$pwd .= '<input type="password" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		$pwd .= htmlentities($values[$field_name]) . '"></p>';
		return print $pwd;
	}
	private function resetKey($key) {
 		if(!isset($_POST[$key]))
 			$_POST[$key] = '';
	}
	private function validEmail($email) {
	  //filter_var() sanitizes the e-mail
	  //address using FILTER_SANITIZE_EMAIL
	  $email = filter_var($email ,  FILTER_SANITIZE_EMAIL);

	  //filter_var() validates the e-mail
	  //address using FILTER_VALIDATE_EMAIL
	  if(filter_var($email ,  FILTER_VALIDATE_EMAIL)) { return true; }
	  else { return false; }
	}
} // Forms
?>