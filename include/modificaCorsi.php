<?php
require_once('funzioni.php'); // Includes Login Script
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
$titolo = $_POST["titolo"];
$descrizione = $_POST["descrizione"];
$continuita = $_POST["continuita"];
$tipo = $_POST["tipo"];

$result = $db->query("UPDATE corsi
                      SET titolo = '".secure($titolo)."',
                          descrizione = '".secure($descrizione)."',
                          continuita = '".secure($continuita)."',
                          tipo = '".secure($tipo)."'
                      WHERE id = '".secure($idCorso)."'") or die($db->error);//*/
echo "SUCCESS";
?>
