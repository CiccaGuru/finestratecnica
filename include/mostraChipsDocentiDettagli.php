<?php
require_once 'funzioni.php'; // Includes Login Script
include 'config.php';
global $_CONFIG;
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

$idCorso = $_POST["idCorso"];
$db = database_connect();

$result = $db->query("SELECT utenti.nome, utenti.cognome, corsi_docenti.idDocente FROM utenti, corsi_docenti where utenti.id = corsi_docenti.idDocente and corsi_docenti.idCorso = $idCorso");

if($result->num_rows == 0){
  ?><span class="italic center">Nessuna docente</span><?php
}
else{
while($docente = $result->fetch_assoc()){
?>

          <div class="chip chip-persist" data-iddocente="<?php echo $docente["idDocente"];?>">
            <?php echo $docente["nome"][0].". ".$docente["cognome"];?>
            <i class="close material-icons">close</i>
          </div>
<?php
}
}
?>
