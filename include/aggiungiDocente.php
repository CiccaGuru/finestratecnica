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

if($_POST["submit"]){
	$nome = secure($_POST["nome"]);
	$cognome = secure($_POST["cognome"]);
	$username = secure($_POST["username"]);
	$password = hash("sha512", secure($_POST["password"]));
	$db = database_connect();
	if(!$result = $db->query("select * from utenti where username='$username'")){
		die('ERRORE: ' . $db->error);
	}

	$rows = $result->num_rows;
	if ($rows == 0) {
		if(!$db->query("INSERT INTO utenti
				(nome, cognome, username, password, classe, level) VALUES
				('".$nome."', '".$cognome."', '".$username."', '".$password."', 6, 1)"))
			echo "Errore".$db->error;
		else
			echo "SUCCESS";
	}
	else
		echo "EXISTS";
}
$db->close();

?>
