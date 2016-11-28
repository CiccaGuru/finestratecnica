<?php
include dirname(__FILE__).'/funzioni.php';

$utente = check_login();

if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
  header('Location: docente.php');
  if($user_level == 0)
  header('Location: userhome.php');
}

$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$ora = $_POST["ora"];
$db = database_connect();

$result = $db->query("SELECT id FROM utenti WHERE ((nome = '".trim(strtoupper(secure($nome)))."') AND (cognome = '".trim(strtoupper(secure($cognome)))."'))") or die('ERRORE: ' . $db->error);
if($result->num_rows==0){
  ?><span class="bold">Nessun utente con questo nome!</span><?php
}
if($result->num_rows==1){
  $utenti = $result->fetch_assoc();
  $resultOra = $db->query("SELECT idLezione, iscrizioni.idCorso FROM iscrizioni,lezioni WHERE ((idUtente = '".$utenti["id"]."') AND lezioni.id = iscrizioni.idLezione AND (lezioni.ora = '".$ora."') AND (partecipa = '1'))") or die('ERRORE: ' . $db->error);
  if($resultOra->num_rows == 0){
    ?> Ora buca<?php
  }
  else{
    $dettagliIscrizione = $resultOra->fetch_assoc();
    $resultCorso = $db->query("SELECT titolo, iddocente FROM corsi
            WHERE id='".$dettagliIscrizione["idCorso"]."'") or die('ERRORE: ' . $db->error);
    $dettagliCorso = $resultCorso->fetch_assoc();
    $resultLezione = $db->query("SELECT aula FROM lezioni
                            WHERE id='".$dettagliIscrizione["idLezione"]."'") or die('ERRORE: ' . $db->error);
    $dettagliLezione = $resultLezione->fetch_assoc();
    ?>
    <div class="row">
      <div class="col s4 bold"><?php echo $dettagliCorso["titolo"];?>  </div>
      <div class="col s4"><?php echo getStringaOra($ora);?>  </div>
      <div class="col s4">Aula: <?php echo $dettagliLezione["aula"];?>  </div>
    </div>
    <?php
  }
}
else{
  while($utenti = $result->fetch_assoc()){
    $resultOra = $db->query("SELECT iscrizioni.idLezione, iscrizioni.idCorso FROM iscrizioni, lezioni WHERE ((idUtente = '".$utenti["id"]."') AND (lezioni.ora = '".$ora."') AND lezioni.idLezione = lezioni.id AND (iscrizioni.partecipa = '1'))") or die('ERRORE: ' . $db->error);
    if($resultOra->num_rows == 0){
      ?> Ora buca<?php
    }
    else{
      $dettagliIscrizione = $resultOra->fetch_assoc();
      $resultCorso = $db->query("SELECT titolo, iddocente FROM corsi WHERE id='".$dettagliIscrizione["idCorso"]."'") or die('ERRORE: ' . $db->error);
      $dettagliCorso = $resultCorso->fetch_assoc();
      $resultLezione = $db->query("SELECT aula FROM lezioni WHERE id='".$dettagliIscrizione["idLezione"]."'") or die('ERRORE: ' . $db->error);
      $dettagliLezione = $resultLezione->fetch_assoc();
      ?>
      <div class="row">
        <div class="col s4 bold"><?php echo $dettagliCorso["titolo"];?>  </div>
        <div class="col s4"><?php echo getStringaOra($ora);?>  </div>
        <div class="col s4">Aula: <?php echo $dettagliLezione["aula"];?>  </div>
      </div>
      <?php
    }
  }
}
  ?>
