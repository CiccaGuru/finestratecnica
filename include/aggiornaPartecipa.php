<?php
include 'funzioni.php';
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
$ora = $_POST["ora"];
$idLezione = $_POST["idLezione"];

$db->query("UPDATE iscrizioni, lezioni, corsi set iscrizioni.partecipa='0' where iscrizioni.idUtente = '$utente'
                and lezioni.id=iscrizioni.idLezione and corsi.id = iscrizioni.idCorso
                and lezioni.ora = '$ora' and corsi.continuita = '0'") or die("AAA: ".$db->error);
$db->query("UPDATE iscrizioni, lezioni SET iscrizioni.partecipa='1' WHERE iscrizioni.idUtente = '$utente'
                and lezioni.id = iscrizioni.idLezione and lezioni.ora = '$ora' and iscrizioni.idLezione = '$idLezione'") or  die("AGG PART: ".$db->error);
$db->close();
echo "SUCCESS";
?>
