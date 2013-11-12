<?php
// http://net.tutsplus.com/tutorials/php/secure-your-forms-with-form-keys/
class FormKey {
	private $formKey;
	private $old_formKey;

    function __construct() {  
        if(isset($_SESSION['form_key'])) {  
            $this->old_formKey = $_SESSION['form_key'];  
        }
    }
  
    //Function to generate the form key  
    private function generateKey() {  
        $ip = $_SERVER['REMOTE_ADDR'];  //Get the IP-address of the user  
        $uniqid = uniqid(mt_rand(), true);  
        //Return the hash  
        return md5($ip . $uniqid);  
    }

    //Function to output the form key  
    public function outputKey() {  
        $this->formKey = $this->generateKey();  //Generate the key and store it inside the class  
        $_SESSION['form_key'] = $this->formKey;  //Store the form key in the session
        return $this->formKey;  
    }

    //Function that validated the form key POST data  
    public function validate() {  
        //We use the old formKey and not the new generated version  
        if($_POST['form_key'] == $this->old_formKey) {  
            return true;  //The key is valid, return true.  
        } else  {
            return false;  //The key is invalid, return false.  
        }
    }
}
?>