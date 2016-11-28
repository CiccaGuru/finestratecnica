<?php
include dirname(__FILE__).'/funzioni.php';
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

$idOra = $_POST["id_ora"];
$idCorso = $_POST["id_corso"];
$db = database_connect();
$a = rimuoviOra($idOra, $idCorso, $db);
if($a){
  echo "SUCCESS";
}
$db->close();
?>
