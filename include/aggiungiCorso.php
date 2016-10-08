<?php
include 'funzioni.php';
$titolo = secure($_POST["titolo"]);
$descrizione = secure($_POST["descriz"]);
$tipo = secure($_POST["tipo"]);
$continuita = secure($_POST["continuita"]);
$classi = $_POST["classi"];
$db = database_connect();
$result = $db->query("INSERT INTO corsi (titolo, descrizione, continuita, tipo)
                          VALUES ('$titolo', '$descrizione', '$continuita', '$tipo') ") or  die('ERRORE: 23 ' . $db->error);
$id_corso= $db->insert_id;
foreach($classi as $classe){
  $result = $db->query("INSERT INTO corsi_classi (idCorso, classe) VALUES ('$id_corso', '$classe') ") or  die('ERRORE: 15' . $db->error);
}
$db->close();
echo $id_corso;

?>
