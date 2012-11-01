<?php
  /*
   Page: class.forms.php
   Desc: All forms are generated and validated here. Validation requires database connection.
  */
class PsyForms {
  

  public function skap_form($id, $target) {
    $this->form_open($id, $target);
    $this->input_text('skapnr', $_POST, 'Skapnummer');
    $this->input_hidden('s', 1);
    $this->input_submit('Finn');
    $this->form_close();
  }

  /*
   Internal tools
  */
	private function form_open($id, $target) {
		$form_open = '<form id="'.$id.'" method="POST" action="'.$_SERVER['PHP_SELF'].'?p='.$target.'">';
		return print $form_open;
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
} // Forms
?>