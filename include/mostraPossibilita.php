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

  $result = $db->query("SELECT corsi.id as idcorso, lezioni.id as idlezione, iscrizioni.partecipa, corsi.titolo, corsi.descrizione, utenti.nome, utenti.cognome
                          from corsi, utenti, iscrizioni, lezioni
                          where iscrizioni.idUtente = '$utente' and lezioni.ora = '$ora' and
                          lezioni.id = iscrizioni.idLezione and corsi.id = iscrizioni.idCorso
                          and utenti.id = corsi.iddocente") or die($db->error);
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
?>
</div>
