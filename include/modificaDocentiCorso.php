<?php
include dirname(__FILE__).'/funzioni.php';
$utente = check_login();
if ($utente == -1) {
    header('Location: index.php');
} else {
    $user_level = get_user_level($utente);
    if ($user_level == 0) {
        header('Location: userhome.php');
    }
    if ($user_level == 1) {
        header('Location: docente.php');
    }
}

$db = database_connect();
$idCorso = $_POST['idCorso'];
$docenti = $_POST["docenti"];

$result = $db->query("DELETE FROM corsi_docenti WHERE idCorso = $idCorso");

foreach ($docenti as $docente) {
  $result = $db->query("INSERT INTO corsi_docenti (idCorso, idDocente) VALUES($idCorso, $docente)") or die($db->error);
}

echo 'SUCCESS';
