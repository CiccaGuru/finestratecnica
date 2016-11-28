<?php

include dirname(__FILE__).'/funzioni.php';
$utente = check_login();
if($utente==-1){
	header('Location: index.php');
}
else{
	$user_level = get_user_level($utente);
	if($user_level == 0){
		header('Location: userhome.php');
	}
	else{
			$db = database_connect();
			if($user_level == 2){
				$docenti = $_POST["docenti"];
			}
			else{
				$docenti = array($utente);
			}
			$idCorso = $_POST["idCorso"];

			foreach ($docenti as $docente) {
				$result = $db->query("INSERT INTO corsi_docenti (idCorso, idDocente) VALUES ($idCorso, $docente)") or die($db->error);
			}

			echo "SUCCESS";
			$db->close();

		}
}

?>
