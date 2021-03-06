<?php
include dirname(__FILE__).'/funzioni.php';
$utente = check_login();

if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die('LOGINPROBLEM');


$db = database_connect();
$idCorso = $_POST["idCorso"];
?><div id="container">
  <h4 class="condensed primary-text center">Corsi coincidenti</h4>
<?php
$result = $db->query("SELECT  corsi_obbligatori.idCorso
                        from  corsi_obbligatori, utenti
                        where corsi_obbligatori.idClasse = utenti.idClasse and
                              utenti.id = $utente") or die($db->error);
while($corsoObbl = $result->fetch_assoc()){
  $corsi_obbligati[] = $corsoObbl["idCorso"];
}
$result = $db->query("SELECT * FROM lezioni WHERE idCorso = '$idCorso'") or die('ERRORE: coincidenze 19' . $db->error);
while($lezione = $result->fetch_assoc()){
  $resultA =  $db->query("SELECT  iscrizioni.idCorso, lezioni.ora
                          FROM    iscrizioni, corsi, lezioni
                          WHERE   lezioni.ora = '".$lezione["ora"]."' AND
                                  iscrizioni.idUtente = '$utente' AND
                                  corsi.continuita='1' AND
                                  lezioni.id = iscrizioni.idLezione AND
                                  NOT iscrizioni.idCorso = $idCorso AND
                                  corsi.id = iscrizioni.idCorso") or die('ERRORE: coincidenze 21' . $db->error);
  if($resultA->num_rows>0){
    while($lezioneCoincidente = $resultA->fetch_assoc()){
      $resultB = $db->query("SELECT id, titolo FROM corsi WHERE id = '".$lezioneCoincidente["idCorso"]."'") or die ('ERRORE: coincidenze 24' . $db->error);;
      $dettagliCorso = $resultB->fetch_assoc();
      ?>
        <div class="row valign-wrapper">
          <div class="col m4 s12 bold valign">
            <?php echo $dettagliCorso["titolo"]."\n"; ?>
          </div>
          <div class="col hide-on-small-only m4 s8 offset-s4 valign">
            <?php
              echo getStringaOra($lezioneCoincidente["ora"]);
            ?>
          </div>
          <?php
            if(in_array($dettagliCorso["id"], $corsi_obbligati)){?>
              <div class="col m4 s8 offset-s4 valign">
                <a class="waves-effect disabled waves-light btn valign accent">Obbligatorio</a>
              </div>
          <?php  }else {?>
          <div class="col m4 s8 offset-s4 valign">
            <a onclick="rimuoviCorso(<?php echo $dettagliCorso["id"]; ?>)" class="waves-effect waves-light btn valign accent">Elimina corso</a>
          </div>
          <?php } ?>
        </div>
      <?php
    }
  }
}
}
?>
</div>
