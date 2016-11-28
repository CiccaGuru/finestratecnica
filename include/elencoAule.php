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
    $aule = '';
    $resultAule = $db->query('SELECT * from aule') or die('Error: '.$db->error);
    while ($aula = $resultAule->fetch_assoc()) {
      if($aula["id"] == $lezione["idAula"]){
        $aule .= '<option value="'.$aula['id'].'" selected>Aula '.$aula['nomeAula'].', '.$aula['maxStudenti'].' alunni</option>';
      }else{
        $aule .= '<option value="'.$aula['id'].'">Aula '.$aula['nomeAula'].', '.$aula['maxStudenti'].' alunni</option>';
      }
    }

    echo $aule;
  }
}
?>
