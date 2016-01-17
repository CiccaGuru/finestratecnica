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

  $nome = $_POST["nome"];
  $id = $_POST["id"];
  $cognome = $_POST["cognome"];
  $username = $_POST["username"];
  $db = database_connect();
if(isset($_POST["classe"])){
  $classe = $_POST["classe"];
  $result = $db->query("UPDATE utenti SET nome='$nome', cognome='$cognome', username='$username', classe='$classe' WHERE id=$id") or die('ERRORE: ' . $db->error);
}
  else{
  $result = $db->query("UPDATE utenti SET nome='$nome', cognome='$cognome', username='$username' WHERE id=$id") or die('ERRORE: ' . $db->error);
}
  echo "SUCCESS";
  $db->close();
?>
