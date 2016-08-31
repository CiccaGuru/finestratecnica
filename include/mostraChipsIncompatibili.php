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

$result = $db->query("SELECT corsi_incompatibili.idCorso2, corsi.titolo
                        from corsi_incompatibili, corsi
                        WHERE idCorso1 = $idCorso AND corsi.id = corsi_incompatibili.idCorso2");

if($result->num_rows == 0){
  ?><span class="italic center">Nessun corso</span><?php
}
else{
while($corso = $result->fetch_assoc()){
?>

          <div class="chip">
            <?php echo $corso["titolo"];?>
            <i class="material-icons" onclick="eliminaIncompatibilita(<?php echo $idCorso.", ".$corso["idCorso2"];?>)">close</i>
          </div>
<?php
}
}
?>
