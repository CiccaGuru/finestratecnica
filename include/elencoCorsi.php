<?php
require_once 'funzioni.php'; // Includes Login Script
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

if (isset($_POST['min'])) {
  $min = $_POST['min'];
} else {
  $min = 0;
}

if (isset($_POST['max'])) {
  $max = $_POST['max'];
} else {
  $max = 30;
}
$condizione = '';
if (isset($_POST['action'])) {
  if (isset($_POST['concontinuita']) and !(isset($_POST['senzacontinuita']))) {
    $condizione .= " corsi.continuita = '1' AND ";
  }
  if (isset($_POST['senzacontinuita']) and !(isset($_POST['concontinuita']))) {
    $condizione .= " corsi.continuita = '0' AND ";
  }
  if (isset($_POST['recupero']) and !(isset($_POST['approfondimento']))) {
    $condizione .= " corsi.tipo = '0' AND ";
  }
  if (isset($_POST['approfondimento']) and !(isset($_POST['recupero']))) {
    $condizione .= " corsi.tipo = '1' AND ";
  }
  if (!($_POST['professore'] == '')) {
    $professore = str_replace('à', '&agrave', $_POST['professore']);
    $professore = str_replace('è', '&egrave', $professore);
    $professore = str_replace('ì', '&igrave', $professore);
    $professore = str_replace('ò', '&ograve', $professore);
    $professore = str_replace('é', '&eacuto', $professore);
    $condizione .= "(utenti.nome LIKE '$professore' OR utenti.cognome LIKE '$professore') AND ";
  }
  if (!($_POST['titolo'] == '')) {
    $titolo = str_replace('à', '&agrave', $_POST['titolo']);
    $titolo = str_replace('è', '&egrave', $titolo);
    $titolo = str_replace('ì', '&igrave', $titolo);
    $titolo = str_replace('ò', '&ograve', $titolo);
    $titolo = str_replace('é', '&eacuto', $titolo);
    $condizione .= "(corsi.titolo LIKE '%$titolo%' OR corsi.descrizione LIKE '%$titolo%')";
  } else {
    $condizione .= '1';
  }
}

if ($condizione == '') {
  $condizione = '1';
} else {
  $condizione .= ')';
}
$giorni = '';
$ore_elenco = '';
foreach ($_CONFIG['giorni'] as $num => $nome) {
  $giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for ($j = 1;$j <= $_CONFIG['ore_per_giorno'];++$j) {
  $ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}
$db = database_connect();

$result = $db->query("SELECT  corsi.titolo as titolo,
  corsi.descrizione as descrizione,
  corsi.continuita as continuita,
  corsi.tipo as tipo,
  utenti.nome as nome,
  utenti.cognome as cognome,
  utenti.id as idDocente,
  corsi.id as id
  FROM corsi,utenti
  WHERE (utenti.id = corsi.iddocente) AND ($condizione)
  ORDER BY corsi.titolo
  LIMIT $min, ".($max - $min)) or die('AA: '.$db->error);
  ?>
  <li class="collection-header blue-text center"><h4 class="light condensed">ELENCO CORSI</h4></li>
  <li class="collection-item">
    <form  action="gestisciCorsi.php" method="POST">
      <div class="row condensed">
        <div class="col s2" style="font-size:120%; margin-top:0.8em">
          <p class="condensed red-text">
            <i class="material-icons left">search</i>Cerca
          </p>
        </div>
        <div class="col s2 valign">
          <div class="row" style="margin-bottom:5px;">
            <p>
              <input <?php
              if ((isset($_POST['concontinuita'])) || (!(isset($_POST['concontinuita'])) && !(isset($_POST['senzacontinuita'])))) {
                echo 'checked';
              }
              ?> type="checkbox" class="filled-in" name="concontinuita" id="concontinuita" />
              <label for="concontinuita">Con continuità</label>
            </p>
          </div>
          <div class="row" style="margin-bottom:5px;">
            <p>
              <input  <?php
              if ((isset($_POST['senzacontinuita'])) || (!(isset($_POST['concontinuita'])) && !(isset($_POST['senzacontinuita'])))) {
                echo 'checked';
              }
              ?> type="checkbox" class="filled-in" name="senzacontinuita" id="senzacontinuita" />
              <label for="senzacontinuita">Senza continuità</label>
            </p>
          </div>
        </div>

        <div class="col s2 valign">
          <div class="row" style="margin-bottom:5px;">
            <p>
              <input <?php
              if ((isset($_POST['recupero'])) || (!(isset($_POST['approfondimento'])) && !(isset($_POST['recupero'])))) {
                echo 'checked';
              }
              ?> type="checkbox" class="filled-in" name="recupero" id="recuperoCerca" />
              <label for="recuperoCerca">Recupero</label>
            </p>
          </div>
          <div class="row" style="margin-bottom:5px;">
            <p>
              <input <?php
              if ((isset($_POST['approfondimento'])) || (!(isset($_POST['approfondimento'])) && !(isset($_POST['recupero'])))) {
                echo 'checked';
              }
              ?> type="checkbox" class="filled-in" name="approfondimento" id="approfondimentoCerca" />
              <label for="approfondimentoCerca">Approfondimento</label>
            </p>
          </div>
        </div>

        <div class="input-field col s3 valign">
          <input id="parolaChiave" name="titolo" type="text" value="<?php echo $_POST['titolo']?>" class="validate valign">
          <label for="parolaChiave">Parola chiave</label>
        </div>
        <div class="col s1 right-align" style="margin-top:0.6em;">
          <p class="right-align">
            Mostra
          </p>
        </div>

        <div class="input-field col s1 valign">
          <input id="min" name="min" type="text" class="validate valign" value="<?php echo $min?>" required>

        </div>
        <div class="col s1">
          <div class="input-field">
            <button class="btn-floating btn-large center waves-effect waves-light red condensed white-text" type="submit" name="action">
              <i class="material-icons">search</i>
            </button>
          </div>
        </div>

      </div>
    </form>
  </li>
  <?php
  while ($dettagli = $result->fetch_assoc()) {
    ?>
    <li class="collection-item">
      <div class="row valign-wrapper" style="margin-bottom:0px;">
        <div class="col s1 valign bold">
          <i onclick="eliminaCorso(<?php echo $dettagli['id']; ?>)" class="material-icons waves-effect waves-red red-text waves-circle" style="border-radius:50%; display:inline; margin:0em;">close</i>
          <span class="condensed" style="margin-left:0.5em;">ID: <?php echo $dettagli['id']; ?></span>
        </div>
        <div class="col s2 valign center-align bold condensed capitalize" style="font-size:105%";>
          <?php echo $dettagli['titolo']; ?>
        </div>
        <div class="col s3 valign">
          <?php echo $dettagli['descrizione']; ?>
        </div>
        <div class="col s2 valign center-align condensed bold">
          <?php
          if ($dettagliCorso['iddocente'] == '0') {
            echo "<span class='italic'>Docenti vari</span>";
          } else {
            echo $dettagli['nome'][0].'. '.$dettagli['cognome'];
          } ?>
        </div>
        <div class="col s2 valign center-align capitalize condensed">
          <p style="margin: 0.4em;">
            <?php if (!$dettagli['tipo']) {
              echo 'Recupero';
            } else {
              echo 'Approfondimento';
            } ?>
          </p>
          <p style="margin:0.4em;">
            <?php if ($dettagli['continuita']) {
              echo 'Con continuità';
            } else {
              echo 'Senza continuità';
            } ?>
          </p>
        </div>
        <div class="col s2 center valign condensed">
          <a class="waves-effect small-icon-corsi condensed waves-red fill-width fake-button valign red-text" onclick="mostraModalDettagli(<?php echo $dettagli['id']; ?>, <?php echo $dettagli['idDocente']; ?>)" style="width:98%;">
            <i class="material-icons">more_horiz</i> <br/>DETTAGLI
          </a>
        </div>
      </div>
    </li>
<?php
  }

  $resultConta = $db->query("SELECT  corsi.id as conta
    FROM corsi, utenti
    WHERE (utenti.id = corsi.iddocente) AND $condizione") or die('BB: '.$db->error);
    if ($resultConta->num_rows > ($max - $min)) {
      ?>
      <li class="collection-item row center valign-wrapper">
        <div class"center center-text">
          <form action = "gestisciCorsi.php" method="post">
            <input type="hidden" name = "min" value="<?php echo $max?>"></input>
            <input type="hidden" name = "max" value="<?php echo $max + $max - $min?>"></input>
            <button class="btn center-text waves-effect waves-light red white-text" type="submit" name="action">Avanti
            </button>
          </div>
        </form>
      </li>
      <?php

    }
    ?>
