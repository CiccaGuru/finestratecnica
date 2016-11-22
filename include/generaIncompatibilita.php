<?php
include 'funzioni.php';
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
$result = $db->query("SELECT  corsi.id, corsi.titolo as titolo
                        FROM  corsi, corsi_incompatibili
                        WHERE corsi_incompatibili.idCorso1 = $idCorso and
                              corsi.id=corsi_incompatibili.idCorso2 and
                              ( SELECT COUNT(*) as c
                                FROM iscrizioni
                                WHERE idUtente = $utente and
                                      idCorso = corsi.id
                              ) > 0
                              ") or die('ERRORE: coincidenze 19' . $db->error);
while($dettagliCorso = $result->fetch_assoc()){
      ?>
        <div class="row valign-wrapper">
          <div class="col s6 bold valign">
            <?php echo $dettagliCorso["titolo"]; ?>
          </div>
          <?php
            if(in_array($dettagliCorso["id"], $corsi_obbligati)){?>
              <div class="col s6 offset-s4 valign">
                <a class="waves-effect disabled waves-light btn valign accent">Obbligatorio</a>
              </div>
          <?php  }else {?>
          <div class="col s6 offset-s4 valign">
            <a onclick="rimuoviCorso(<?php echo $dettagliCorso["id"]; ?>)" class="waves-effect waves-accent btn-flat condensed valign accent-text ">Elimina corso</a>
          </div>
          <?php } ?>
        </div>
      <?php
    }
  }
?>
</div>
