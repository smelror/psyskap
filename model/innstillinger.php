<?php

// Gets subpage (edit, mods, logg)
$sub = ''; 
if(isset($_GET['sub']) && $_GET['sub']) $sub = $_GET['sub'];
else $sub = 'setting';
$curUser = $db->getUser($_SESSION['userid']);

?>