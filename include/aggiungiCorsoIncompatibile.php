<?php

include dirname(__FILE__).'/funzioni.php';
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

$result = $db->query("INSERT INTO corsi_incompatibili (idCorso1, idCorso2) VALUES ($idCorso1, $idCorso2)") or die($db->error);
$result = $db->query("INSERT INTO corsi_incompatibili (idCorso1, idCorso2) VALUES ($idCorso2, $idCorso1)") or die($db->error);

echo "SUCCESS";
$db->close();

?>
