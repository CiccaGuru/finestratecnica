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
  die("LOGINPROBLEM");
  $ora = $_POST["ora"];

  $db = database_connect();

  $result = $db->query("SELECT corsi.id as idcorso, lezioni.id as idlezione,
                                iscrizioni.partecipa, corsi.titolo, corsi.descrizione,
                                utenti.nome, utenti.cognome
                          from corsi, utenti, iscrizioni, lezioni
                          where iscrizioni.idUtente = '$utente' and lezioni.ora = '$ora' and
                          lezioni.id = iscrizioni.idLezione and corsi.id = iscrizioni.idCorso
                          and utenti.id = corsi.iddocente") or die($db->error);

  if($result->num_rows==0){
?>
<div class="container">
  <h3 class="condensed red-text thin center">Scegli il corso</h3>
<p class="space-below center-align">
  In quest'ora non sei iscritto a nessun corso. Scegli a quale corso vuoi partecipare.<br/>
  <span class="condensed red-text">ATTENZIONE! Vengono mostrati solo i corsi senza continuità. Verrai iscritto solo alla singola ora.</span>
</p>


<?php
$resultUtente = $db->query("SELECT 	classe
                      FROM 		utenti
                      WHERE 	id = '$utente'");
$dettagliUtente = $resultUtente->fetch_assoc();
$resultTmp = $db->query("SELECT * from
                              (SELECT lezioni.idCorso,
                                      lezioni.id, lezioni.maxIscritti,
                                      corsi.titolo,
                                      utenti.nome, utenti.cognome, corsi.descrizione,corsi_classi.classe,
                                      (SELECT COUNT(id) AS count FROM iscrizioni
                                          WHERE idLezione=lezioni.id AND partecipa='1')
                                      as contaTable
                                FROM corsi, lezioni, utenti, corsi_classi
                                WHERE lezioni.ora = '$ora' AND corsi.id = lezioni.idCorso
                                  and utenti.id = corsi.idDocente and corsi.continuita = '0'
                                  and corsi.id = corsi_classi.idCorso AND
                                  corsi_classi.classe = '".$dettagliUtente["classe"]."')
                          as sub where contaTable < maxIscritti");
  while($lezione = $resultTmp->fetch_assoc()){
    ?>
      <div class="row p-no-space">
        <div class="col s2 bold condensed valign-wrapper">
            <p style="valign">
              <?php echo strtoupper($lezione["titolo"])?>
            </p>
        </div>
        <div class="col s2 valign-wrapper">
          <p class="valign truncate">
            <?php echo $lezione["nome"][0].". ".$lezione["cognome"]?>
          </p>
        </div>
        <div class="col s5 valign-wrapper">
            <p class="valign truncate">
              <?php echo $lezione["descrizione"]?>
            </p>
        </div>
        <div class="col s3 valign-wrapper">
          <p class="valign">
            <a disabled id="trigger<?php echo $lezione["id"]?>" class="waves-effect waves-red btn-flat red-text iscriviOraModal" data-idora="<?php echo $lezione["id"]?>" data-idcorso="<?php echo $lezione["idCorso"]?>">ISCRIVITI</a>
          </p>
        </div>
      </div>

    <?php
  }
  }
  else{
    ?>
    <div class="container">
      <h3 class="condensed red-text thin center">Scegli il corso</h3>
    <p class="space-below center-align">
      In quest'ora sei iscritto a più di un corso senza continuità. Scegli a quale partecipare.
    </p>
    <?php
  while($lezione = $result->fetch_assoc()){
    ?>
      <div class="row" id="scegliCorso">
        <div class="col s6 m3">
          <p>
            <input name="group1" type="radio" onchange="aggiornaPartecipa()" data-ora="<?php echo $ora?>" data-idLezione = "<?php echo $lezione["idlezione"]?>" id="lezione<?php echo $lezione["idlezione"]?>" <?php if($lezione["partecipa"]=="1") echo "checked";?> />
            <label for="lezione<?php echo $lezione["idlezione"]?>" class="condensed bold"><?php echo strtoupper($lezione["titolo"])?></label>
          </p>
        </div>
        <div class="col s6 m3">
          <p>
            <?php echo $lezione["nome"][0].". ".$lezione["cognome"]?>
          </p>
        </div>
        <div class="col m6 hide-on-small-only truncate">
          <p>
            <?php echo $lezione["descrizione"];?>
          </p>
        </div>
      </div>
    <?php
  }
}
}
?>
</div>
