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

  switch ($a) {
    case 0:
      echo "SUCCESS";
      break;
    case 1:
      echo "Sei già iscritto a questo corso!";
      break;
    case 2:
      echo "Il corso non ha più posti disponibili!";
      break;
    case 3:
      echo "Questo corso coincide con uno a cui sei già iscritto!";
      break;
    }
  $db->close();
}
?>
