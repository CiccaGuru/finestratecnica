<?php
include 'funzioni.php';
global $_CONFIG;
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
$db = database_connect();
$ora = $_POST["idOra"];
$idCorso = $_POST["idCorso"];
$db->query("UPDATE iscrizioni SET partecipa='1' WHERE ((idCorso = '$idCorso') AND (ora = '$ora') AND (idUtente = '$utente'))") or  die("AGG PART: ".$db->error);
$db->close();
echo "SUCCESS";
?>
