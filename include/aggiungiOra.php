<?php
include 'funzioni.php';
$db = database_connect();
$idcorso=$_POST["idcorso"];
$titolo=$_POST["titolo"];
$ora=$_POST["ora"];
$idAula=$_POST["idAula"];

if($titolo==""){
  $titolo = "<span class=\'italic\'>Nessuna descrizione inserita</span>";
}
if(!$result = $db->query("INSERT INTO lezioni (idcorso, titolo, ora, idAula)
                          VALUES ('$idcorso', '$titolo', $ora, '$idAula') ")){
  die('ERRORE: 25' . $db->error);
}
else {
  echo "SUCCESS INSERT ORA";
}
$db->close();
?>
