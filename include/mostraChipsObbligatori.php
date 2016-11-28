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

          <div class="chip chip-persist">
            <?php echo $sezione["classe"].$sezione["sezione"];?>
            <i class="close material-icons" onclick="eliminaObbligatori(<?php echo $sezione["idClasse"].", ".$idCorso;?>)">close</i>
          </div>
<?php
}
}
?>
