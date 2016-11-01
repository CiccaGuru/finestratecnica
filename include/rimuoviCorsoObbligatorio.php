<?php

include 'funzioni.php';
$utente = check_login();
if($utente==-1){
	header('Location: index.php');
}
else{
	$user_level = get_user_level($utente);
	if($user_level == 0)
		header('Location: userhome.php');
	if($user_level == 1)
		header('Location: docente.php');
}
$db = database_connect();
$idCorso = $_POST["idCorso"];
$idClasse = $_POST["idClasse"];
$result = $db->query("DELETE FROM corsi_obbligatori WHERE idCorso = $idCorso AND idClasse = $idClasse") or die($db->error);
echo "SUCCESS";
$db->close();

?>
