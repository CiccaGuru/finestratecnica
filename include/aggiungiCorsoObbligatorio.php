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
$idClasse = $_POST["idClasse"];
$idCorso = $_POST["idCorso"];

$result = $db->query("INSERT INTO corsi_obbligatori (idClasse, idCorso) VALUES ($idClasse, $idCorso)") or die($db->error);

$result = $db->query("INSERT INTO iscrizioni (idUtente, idCorso, idLezione, partecipa)
													SELECT utenti.id, corsi.id, lezioni.id, '1'
													FROM utenti, lezioni, corsi
													where utenti.idClasse = $idClasse and
																lezioni.idCorso = corsi.id and
																corsi.id = $idCorso") or die($db->error);

echo "SUCCESS";
$db->close();

?>
