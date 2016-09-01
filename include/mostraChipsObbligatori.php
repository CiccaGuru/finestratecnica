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

$result = $db->query("SELECT sezioni.id, sezioni.classe, sezioni.sezione, corsi_obbligatori.idClasse
                        from sezioni, corsi_obbligatori
                        WHERE corsi_obbligatori.idCorso = $idCorso AND sezioni.id = corsi_obbligatori.idClasse");

if($result->num_rows == 0){
  ?><span class="italic center">Nessuna classe</span><?php
}
else{
while($sezione = $result->fetch_assoc()){
?>

          <div class="chip">
            <?php echo $sezione["classe"].$sezione["sezione"];?>
            <i class="material-icons" onclick="eliminaObbligatori(<?php echo $sezione["idClasse"].", ".$idCorso;?>)">close</i>
          </div>
<?php
}
}
?>
