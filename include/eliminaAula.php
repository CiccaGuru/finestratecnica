<?php
include dirname(__FILE__).'/funzioni.php';
  $id = $_POST['id'];
  $db = database_connect();
  $db->query("DELETE FROM aule WHERE id=$id") or die("Errore".$db->error);
  echo "SUCCESS";
  $db->close();
 ?>
