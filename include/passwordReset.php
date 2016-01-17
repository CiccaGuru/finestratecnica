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

$id=$_POST["id"];
$db=database_connect();
$result = $db->query("UPDATE utenti SET password=SHA1('123'), passwordOriginale = '1' WHERE id=$id") or  die('ERRORE: ' . $db->error);
echo 'SUCCESS';
$db->close();

?>
