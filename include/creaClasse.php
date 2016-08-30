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
$classe = $_POST["classe"];
$sezione = $_POST["sezione"];

$result = $db->query("INSERT into sezioni (classe, sezione) values ($classe, '$sezione')") or die($db->error);
echo "SUCCESS";
?>
