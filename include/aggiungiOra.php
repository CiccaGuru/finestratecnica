<?php
include 'funzioni.php';
$db = database_connect();
$idcorso=$_POST["idcorso"];
$titolo=$_POST["titolo"];
$ora=$_POST["ora"];
$aula=$_POST["aula"];
$maxIscritti=$_POST["maxIscritti"];

if($titolo==""){
  $titolo = "<span class=\'italic\'>Nessuna descrizione inserita</span>";
}
if(!$result = $db->query("INSERT INTO lezioni (idcorso, titolo, ora, aula, maxIscritti)
                          VALUES ('$idcorso', '$titolo', $ora, '$aula', '$maxIscritti') ")){
  die('ERRORE: 15' . $db->error);
}
else {
  echo "SUCCESS INSERT ORA";
}
$db->close();
?>
