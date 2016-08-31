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
$idCorso1 = $_POST["idCorso1"];
$idCorso2 = $_POST["idCorso2"];

$result = $db->query("DELETE FROM corsi_incompatibili WHERE idCorso1 = $idCorso1 AND idCorso2 = $idCorso2") or die($db->error);
$result = $db->query("DELETE FROM corsi_incompatibili WHERE idCorso2 = $idCorso2 AND idCorso1 = $idCorso1") or die($db->error);
echo "SUCCESS";
$db->close();

?>
