<?php
include 'funzioni.php';

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


$db = database_connect();
$idOra = $_POST["id_ora"];
$idCorso = $_POST["id_corso"];

$a = iscriviOra($idOra, $idCorso, $db);
echo "SUCCESS";
$db->close();
}
?>
