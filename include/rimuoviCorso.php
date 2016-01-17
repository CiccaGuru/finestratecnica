<?php

include_once "funzioni.php";

$utente = check_login();

if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
  header('Location: docente.php');
  if($user_level == 2)
  header('Location: admin.php');
}
$a = 1;
$corso = $_POST["id_corso"];
$db = database_connect();
$result = $db->query("SELECT * FROM lezioni  WHERE idcorso='$corso' ") or die('ERRORE: ' . $db->error);
while($lezione = $result->fetch_assoc()){
  $a = $a*rimuoviOra($lezione["id"], $corso, $db);
}
if($a){
  echo "SUCCESS";
}
$db->close();
?>
