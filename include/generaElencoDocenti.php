<?php
include "funzioni.php";
$db = database_connect();
$result = $db->query("SELECT id, nome, cognome FROM utenti WHERE level=1") or die('ERRORE: ' . $db->error);
 while ($row = $result->fetch_assoc()) {
  echo '<option value="'.$row["id"].'">'.$row["nome"][0].'. '.$row["cognome"].'</option>';
}
$db->close()
?>
