<?php
include dirname(__FILE__).'/funzioni.php';
  $id = $_POST['id'];
  $db = database_connect();
  if(!$db->query("DELETE FROM sezioni WHERE id=$id"))
    echo "Errore".$db->error;
  else
    echo "SUCCESS";
  $db->close();
 ?>
