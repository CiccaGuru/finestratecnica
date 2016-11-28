<?php
include dirname(__FILE__).'/funzioni.php';

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

  $nomeAula = $_POST["nomeAula"];
  $id = $_POST["id"];
  $maxStudenti = $_POST["maxStudenti"];
  $db = database_connect();
  $result = $db->query("UPDATE aule SET nomeAula='$nomeAula', maxStudenti='$maxStudenti' WHERE id=$id") or die('ERRORE: ' . $db->error);
  echo "SUCCESS";
  $db->close();
?>
