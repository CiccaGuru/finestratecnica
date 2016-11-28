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
$id = secure($_POST['id']);
$titolo = secure($_POST['titolo']);
$tipo = secure($_POST['tipo']);
$continuita = secure($_POST['continuita']);
$descrizione = secure($_POST['descrizione']);
$classi = $_POST['classi'];

$result = $db->query("UPDATE corsi
                      SET titolo = '$titolo',
                          descrizione = '$descrizione',
                          continuita = '$continuita',
                          tipo = '$tipo'
                      WHERE id = $id") or die($db->error);

$result = $db->query("DELETE from corsi_classi where idCorso = $id");
foreach ($classi as $classe) {
    $result = $db->query("INSERT INTO corsi_classi (idCorso, classe) VALUES ('$id', '$classe') ") or  die('ERRORE: 15'.$db->error);
}
echo 'SUCCESS';
