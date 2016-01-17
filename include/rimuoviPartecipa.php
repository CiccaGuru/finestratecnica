<?php
include 'funzioni.php';
global $_CONFIG;
$utente = check_login();

if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die("LOGINPROBLEM");
}
$db = database_connect();
$ora = $_POST["idOra"];
$idCorso = $_POST["idCorso"];

$db->query("UPDATE iscrizioni SET partecipa='0' WHERE ((ora = '$ora') AND (idUtente = '$utente') AND (continuita='0'))") or  die("RIM PART: ".$db->error);
$db->close();
echo "SUCCESS";
?>
