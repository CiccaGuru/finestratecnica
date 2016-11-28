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

if($_POST["submit"]){
	$nomeAula = secure($_POST["nomeAula"]);
	$maxStudenti = secure($_POST["maxStudenti"]);
	$db = database_connect();
	$result = $db->query("select * from aule where nomeAula='$nomeAula'") or die('ERRORE: ' . $db->error);
	$rows = $result->num_rows;
	if ($rows == 0) {
		if(!$db->query("INSERT INTO aule
												(nomeAula, maxStudenti) VALUES
												('$nomeAula', '$maxStudenti')"))
			echo "Errore".$db->error;
		else
			echo "SUCCESS";
	}
	else
		echo "EXISTS";
}
$db->close();

?>
