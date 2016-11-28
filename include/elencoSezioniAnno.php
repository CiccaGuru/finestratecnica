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
  else{
    $db = database_connect();
    $anno = $_POST["anno"];
    $sezioni = '<option value="Scegli" disabled selected>Scegli</option>';
    $result = $db->query("SELECT * from sezioni where classe = $anno") or die('Error: '.$db->error);
    while($sezione = $result->fetch_assoc()) {
        $sezioni .= '<option value="'.$sezione['id'].'">'.$sezione['sezione'].'</option>';
    }
    echo $sezioni;
  }
}
?>
