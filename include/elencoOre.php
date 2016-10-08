<?php
require_once 'funzioni.php';
require_once 'config.php';
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
  if ($user_level == 2){

    $db = database_connect();
    $idCorso = $_POST["idCorso"];
    $result = $db->query("SELECT lezioni.id, lezioni.ora, lezioni.titolo, aule.id as idAula, aule.nomeAula as aula, aule.maxStudenti as maxIscritti
      from lezioni, aule
      WHERE idCorso = $idCorso and lezioni.idAula = aule.id
      order by ora asc") or die($db->error);


      $giorni = '';
      $ore_elenco = '';
      $numGiorno = (int) ($lezione['ora'] / $_CONFIG['ore_per_giorno']) + 1;
      $numOra = $lezione['ora'] % $_CONFIG['ore_per_giorno'];

      if ($numOra == 0) {
        $numOra = 6;
        --$numGiorno;
      }
      foreach ($_CONFIG['giorni'] as $num => $nome) {
        if ($num == $numGiorno) {
          $giorni .= '<option value="'.$num.'" selected>'.$nome.'</option>';
        } else {
          $giorni .= '<option value="'.$num.'" >'.$nome.'</option>';
        }
      }

      for ($j = 1;$j <= $_CONFIG['ore_per_giorno'];++$j) {
        if ($j == $numOra) {
          $ore_elenco .= '<option value="'.$j.'" selected>'.$j.'^a ora</option>';
        } else {
          $ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
        }
      }

      $i = 0;
      while ($lezione = $result->fetch_assoc()) {
        $resultConta = $db->query("SELECT COUNT(*) as count from iscrizioni  WHERE idLezione = '".$lezione['id']."' and partecipa = '1'") or die($db->error);
        $conta = $resultConta->fetch_assoc();
        ?>
        <div class="oraLista row valign-wrapper" id="row<?php echo $i; ?>" data-idCorso="<?php echo $idCorso; ?>" data-idLezione="<?php echo $lezione['id']; ?>">
          <div class="col s1">
            <i class="material-icons waves-effect accent-text waves-accent-text waves-circle" style="border-radius:50%; margin-right:10px; display:inline;">close</i>
            <span class="valign bold condensed" style="display:inline;">   ID: <?php echo $lezione['id']; ?></span>
          </div>
          <div class="input-field col valign s3">
            <input id="titoloModificaOre<?php echo $i; ?>" type="text" class="validate" value='<?php echo $lezione['titolo'] == '' ? 'Nessuna descrizione' : $lezione['titolo']; ?>' required>
            <label class="active condensed" for="titoloModificaOre<?php echo $lezione['id']; ?>">Titolo</label>
          </div>
          <div class="input-field col s3">
            <select class="slelezionaAula" id="selezionaAulaModificaOre<?php echo $i; ?>">
            </select>
            <label>Aula</label>
          </div>
          <div class="input-field col valign s1">
            <input disabled style="color:black" id="iscrittiModificaOre<?php echo $i; ?>" type="text" class="validate" value="<?php echo $conta['count'].'/'.$lezione['maxIscritti']; ?>" required>
            <label class="active condensed" style="color:black" for="iscrittiModificaOre<?php echo $i; ?>">Iscritti</label>
          </div>
          <div class="input-field col s2">
            <select id="selezionaGiornoModificaOre<?php echo $i?>">
              <?php echo $giorni; ?>
            </select>
            <label>Giorno</label>
          </div>
          <div class="col s2 input-field">
            <select id="selezionaOraModificaOre<?php echo $i?>">
              <?php echo $ore_elenco; ?>
            </select>
            <label>Ora</label>
          </div>
        </div>
        <?php
        $i++;
      }
    }
  }
  ?>
