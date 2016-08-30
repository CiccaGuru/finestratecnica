<?php
include 'funzioni.php';
$titolo = secure($_POST["titolo"]);
$descrizione = secure($_POST["descriz"]);
$iddocente = secure($_POST["iddocente"]);
$tipo = secure($_POST["tipo"]);
$continuita = secure($_POST["continuita"]);
$classi = $_POST["classi"];
$db = database_connect();
$result = $db->query("INSERT INTO corsi (titolo, iddocente, descrizione, continuita, tipo)
                          VALUES ('$titolo', '$iddocente', '$descrizione', '$continuita', '$tipo') ") or  die('ERRORE: 23 ' . $db->error);

$id_corso= $db->insert_id;
foreach($classi as $classe){
  $result = $db->query("INSERT INTO corsi_classi (id_corso, classe, continuita, tipo) VALUES ('$id_corso', '$classe', '$continuita', '$tipo') ") or  die('ERRORE: 15' . $db->error);
}
$db->close();
echo $id_corso;

?>
