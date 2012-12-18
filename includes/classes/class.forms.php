<?php
  /*
   Page: class.forms.php
   Desc: All forms are generated and validated here. Validation requires database connection.
  */
class PsyForms {
	
	public function finnSkap($id, $target) {
	    $this->form_open($id, $target);
	 	$this->resetKey('skapnr');
	    $this->input_text('skapnr', $_POST, 'Skapnummer');
	    $this->input_hidden('s', 1);
	    $this->input_submit('Finn');
	    $this->form_close();
	}



	public function login($errors = array()) {
		$this->form_open('formLogin', 'login');
		if($errors) {
			echo '<ul class="errorBox"><li>';
			echo implode('</li><li>',$errors);
			echo '</li></ul>';
		}
		$this->resetKey('usr');
		$this->input_text('usr', $_POST, "Brukernavn");
		$this->resetKey('pwd');
		$this->input_password('pwd', $_POST, "Passord");
		$this->input_submit('Logg inn');
		$this->input_hidden('_login_check', 1);
		$this->form_close();
	}
	public function validate_login($usr, $pwd, $db) {
		$errors = array();
		if($usr && $pwd) {
			try {
				$ps = $db->prepare("SELECT password, salt FROM users WHERE username = :user");
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

	



	/*
	Internal tools
	*/
	private function form_open($id, $target) {
		return print '<form id="'.$id.'" method="POST" action="'.$_SERVER['PHP_SELF'].'?p='.$target.'">';
	}
	private function form_close() {
		return print '</form>';
	}
	private function input_submit($text, $class='') {
		$submit = '<p>';
		($class) ? $submit .= '<input type="submit" class="'.$class.'" ' : $submit .= '<input type="submit" ';
		$submit .= 'value="'.$text.'" /></p>';
		return print $submit;
	}
	private function input_hidden($name, $val) {
		return print '<input type="hidden" name="'.$name.'" value="'.$val.'" />';
	}
	private function input_area($class = '', $name, $values) {
		if($class) $class = ' class="'.$class.'"';
		return print '<textarea'.$class.' id="'.$name.'" name="'.$name.'">'.htmlentities($values[$name]).'</textarea>';
	}
	private function input_text($field_name, $values, $labelname, $class = '') {
		$text = '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		$text .= '<input type="text" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		$text .= htmlentities($values[$field_name]) . '" /></p>';
		return print $text;
	}
	private function input_password($field_name, $values, $labelname, $class = '') {
		$pwd = '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		$pwd .= '<input type="password" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		$pwd .= htmlentities($values[$field_name]) . '" /></p>';
		return print $pwd;
	}
	private function resetKey($key) {
 		if(!isset($_POST[$key]))
 			$_POST[$key] = '';
	}
} // Forms
?>