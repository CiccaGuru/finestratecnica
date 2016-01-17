<?php
include 'funzioni.php';
$db = database_connect();
$utente = check_login();
if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
    die("LOGINPROBLEM");
  if($user_level == 1)
    die('LOGINPROBLEM');

$idOra=$_POST["idLezione"];
$titolo=$_POST["titolo"];
$ora=$_POST["ora"];
$aula=$_POST["aula"];
$maxIscritti=$_POST["maxIscritti"];
$maxIscrittiOLD=$_POST["maxIscritti"];

if($titolo=="Nessuna descrizione inserita"){
  $titolo = "<span class=\'italic\'>Nessuna descrizione inserita</span>";
}
$result = $db->query("SELECT ora from iscrizioni where idLezione = '$idOra'");
$resultFetch = $result->fetch_assoc();
$result = $db->query("SELECT COUNT(*) as conta from iscrizioni where idLezione = '$idOra' AND partecipa = '1'");
$iscritti = $result->fetch_assoc();
if($iscritti["conta"]>$maxIscritti){
  echo "MAXISCRITTI_ERROR";
}
if(($iscritti>0)&&!($resultFetch["ora"]== $ora )){
  echo "DOMENICA_ERROR";
}
$result = $db->query("UPDATE  lezioni
                          SET titolo='$titolo',
                              ora='$ora',
                              aula='$aula',
                              maxIscritti='$maxIscritti'
                          WHERE id = '$idOra' ") or die('ERRORE: ' . $db->error);

  echo "SUCCESS";
$db->close();
}
?>
