<?php
	include_once('include/funzioni.php');
	$utente = check_login();
	if($utente==-1){
		header('Location: index.php');
	}
	session_start();
	$db = database_connect();
	$db->query("DELETE FROM sessioni WHERE user = '$utente'");
	$_SESSION['login_user']=-1;
	header('Location: index.php');
?>
