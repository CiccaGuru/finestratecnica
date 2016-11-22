<?php
  include_once "funzioni.php";

  $result = unserialize(getProp("giorni"));
  array_unshift($result, getProp("ore_per_giorno"));
  echo json_encode($result);
?>
