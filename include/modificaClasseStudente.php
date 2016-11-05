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
	if($user_level == 0)
	header('Location: userhome.php');
}

  $idUtente = $_POST["idUtente"];
  $classe = $_POST["classe"];
  $idSezione = $_POST["idSezione"];
  $db = database_connect();
  $result = $db->query("UPDATE utenti SET classe='$classe', idClasse='$idSezione' WHERE id=$idUtente") or die('ERRORE: ' . $db->error);
  echo "SUCCESS";
  $db->close();
?>
