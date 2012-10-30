<?php
/*
	Page: class.forms.php
	Desc: Forms-class, where all 
*/
class Forms {


/*
	Internal tools
*/
	private function form_open($id, $page, $legend = '') {
		$form_open = '<form id="'.$id.'" method="POST" action="'.$_SERVER['PHP_SELF'].'?page='.$page.'">';
		($legend) ? $form_open .= '<fieldset><legend>'.$legend.'</legend>' : $form_open .= '<fieldset>';
		return print $form_open;
	}
	private function form_close() {
		return print '</fieldset></form>';
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
	private function input_text($field_name, $values, $labelname, $helptext = '', $class = '') {
		echo '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		echo '<input type="text" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		echo htmlentities($values[$field_name]) . '" />';
		if($helptext) {
			echo '<br /> <span class="helptext">'.$helptext.'</span>';
		}
		echo '</p>';
	}
	private function input_password($field_name, $values, $labelname, $helptext = '', $class = '') {
		echo '<p><label for="'. $field_name .'">'. $labelname .'</label>';
		echo '<input type="password" name="' . $field_name .'" id="' . $field_name .'" class="'.$class.'" value="';
		echo htmlentities($values[$field_name]) . '" />';
		if($helptext) {
			echo '<br /> <span class="helptext">'.$helptext.'</span>';
		}
		echo '</p>';
	}
} // Forms
?>