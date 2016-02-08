<?php

include_once "funzioni.php";

$utente = check_login();
$error = 0;
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
$corso = $_POST["id_corso"];

$result = $db->query("SELECT * FROM corsi WHERE id='$corso' ") or die($db->error);
$dettagliCorso = $result->fetch_assoc();
$result = $db->query("SELECT id from lezioni where idCorso = '$corso'");

$conta = 0;
$errori = 0;
$giaIscritto = 0;
$pieno = 0;
$coincide = 0;

while($lezione = $result->fetch_assoc()){
  $conta ++;
  $a = iscriviOra($lezione["id"], $corso, $db);
  switch ($a) {
    case 0:
      break;
    case 1:
      $errori++;
      $giaIscritto++;
      break;
    case 2:
      $errori++;
      $pieno++;
      break;
    case 3:
      $errori++;
      $coincide++;
      break;
    }
}

if($errori > 0){
  if($dettagliCorso["continuita"]){
    $result = $db->query("SELECT id FROM lezioni  WHERE idcorso='$corso' ") or die('ERRORE: ' . $db->error);
    while($lezione = $result->fetch_assoc()){
      rimuoviOra($lezione["id"], $corso, $db);
    }
    echo "Non posso iscriverti, si sono verificati degli errori.";
  }
  else{
    if($errori < $conta){
      echo "SOME";
    }
    else{
      echo "Non posso iscriverti, si sono verificati degli errori.";
    }
  }
}
else{
  echo "SUCCESS";
}























/*
$posso = 1;
if($dettagliCorso["continuita"]){
  $result = $db->query("SELECT id, ora FROM lezioni  WHERE idcorso='$corso'") or die('ERRORE: ' . $db->error);
  while($lezione = $result->fetch_assoc()){
    $resultA = $db->query("SELECT * FROM iscrizioni  WHERE ((idUtente='$utente')   AND (continuita = '1') AND (ora='".$lezione["ora"]."'))") or die('ERRORE ASD: ' . $db->error);
    if(($resultA->num_rows)>0){
      $posso = 0;
      die("Corso coincidente");
    }
    if((troppiIscritti($lezione["id"], $db))){
      $posso = 0;
      die("Corso con troppi iscritti");
    }
  }
}

if($posso = 1){
  $a = 1;
  $result = $db->query("SELECT * FROM lezioni  WHERE idcorso='$corso'") or die('ERRORE: ' . $db->error);
  while($lezione = $result->fetch_assoc()){
    $a = $a * iscriviOra($lezione["id"], $corso, $db);
  }
    echo "SUCCESS";

}*/

$db->close();
}
?>
