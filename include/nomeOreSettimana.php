<?php
  include_once "funzioni.php";
  global $_CONFIG;
  $result = $_CONFIG["giorni"];
  array_unshift($result, $_CONFIG["ore_per_giorno"]);
  //print_r($result);
  echo json_encode($result);
?>
