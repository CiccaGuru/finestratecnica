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

if($titolo=="Nessuna descrizione inserita"){
  $titolo = "";
}

$result = $db->query("UPDATE  lezioni
                          SET titolo='$titolo',
                              ora='$ora',
                              idAula='$aula'
                          WHERE id = '$idOra' ") or die('ERRORE: ' . $db->error);

  echo "SUCCESS";
$db->close();
}
?>
