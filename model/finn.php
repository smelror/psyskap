<?php
// if lookup
$lookup;
$skap;

if((isset($_POST['selSkap']) && $_POST['selSkap']) || (isset($_GET['skap']) && $_GET['skap'])) {
  if($_POST['selSkap']) $lookup = $_POST['selSkap'];
  else $lookup = $_GET['skap'];
}

if($lookup) $skap = new Skap($db->getSkap($lookup));
?>