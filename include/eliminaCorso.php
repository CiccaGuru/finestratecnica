<?php
include 'funzioni.php';
  $id = $_POST['id'];
  $db = database_connect();
  $db->query("DELETE FROM corsi WHERE id=$id") or die("Errore".$db->error);
  $db->query("DELETE FROM corsi_classi WHERE idCorso=$id") or die("Errore".$db->error);
  $db->query("DELETE FROM lezioni WHERE idCorso=$id") or die("Errore".$db->error);
  echo "SUCCESS";
  $db->close();
 ?>
