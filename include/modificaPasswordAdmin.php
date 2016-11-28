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
  $nuova = $_POST["nuova"];
  $attuale = $_POST["attuale"];
	$username = $_POST["username"];
	$db = database_connect();
	if($nuova == ""){
		if($username != ""){
			$result = $db->query("UPDATE utenti SET username='$username' WHERE id=$utente") or die('ERRORE: ' . $db->error);
		}
		else{
			echo "VUOTO";
		}
	}else if($nuova != ""){
		$attuale = hash("sha512", $db->escape_string($attuale));
		$nuova = hash("sha512", $db->escape_string($nuova));
		$result = $db->query("SELECT * from utenti where password='$attuale' AND id='$utente'") or	die('ERRORE: ' . $db->error);
		if($result->num_rows == 0){
			echo "ERRATA";
		}
		else{
			if($username == ""){
				$result = $db->query("UPDATE utenti SET password='$nuova' WHERE id=$utente") or die('ERRORE: ' . $db->error);
				echo "SUCCESS";
			}
			else{
				$result = $db->query("UPDATE utenti SET password='$nuova', username = '$username' WHERE id=$utente") or die('ERRORE: ' . $db->error);
				echo "SUCCESS";
			}
		}
	}
  $db->close();
?>
