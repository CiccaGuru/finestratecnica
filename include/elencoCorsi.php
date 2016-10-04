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

$keyword = $_POST["keyword"];
$quanti = $_POST['mostra'];
$pagina = $_POST["pagina"];

$condizione = '';
if ($_POST["senzacontinuita"]=='0') {
  $condizione .= " corsi.continuita = '1' AND ";
}
if ($_POST['concontinuita'] == '0') {
  $condizione .= " corsi.continuita = '0' AND ";
}
if ($_POST['approfondimento']== '0') {
  $condizione .= " corsi.tipo = '0' AND ";
}
if ($_POST['recupero'] == '0') {
  $condizione .= " corsi.tipo = '1' AND ";
}
if($keyword == ""){
  $condizione .= "1";
}
else{
  $condizione .= "(utenti.nome LIKE '%$keyword%' OR utenti.cognome LIKE '%$keyword%' OR corsi.titolo LIKE '%$keyword%' OR corsi.descrizione LIKE '%$keyword%')";
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

$query = "SELECT  corsi.titolo as titolo,
  corsi.descrizione as descrizione,
  corsi.continuita as continuita,
  corsi.tipo as tipo,
  utenti.nome as nome,
  utenti.cognome as cognome,
  corsi_docenti.idDocente as idDocente,
  corsi.id as id,
  COUNT(corsi_docenti.idCorso) as quantiDocenti
  FROM corsi,utenti,corsi_docenti
  WHERE (utenti.id = corsi_docenti.idDocente) AND (corsi.id = corsi_docenti.idCorso) AND $condizione
  GROUP BY corsi.id
  ORDER BY corsi.titolo";

  //die($query);
$result = $db->query($query." LIMIT ".(($pagina-1)*$quanti).", ".$quanti) or die('AA: '.$db->error);
$resultAA = $db->query($query);
$numRisultato = $resultAA->num_rows;

 if($result->num_rows == 0){
   ?>
   <li class="collection-item">
         <div class="accent-text condensed center-align" style="font-size:150%; margin:1em;">Nessun risultato trovato</div>
       </li>
   <?php
 }
  while ($dettagli = $result->fetch_assoc()) {
    $quantiDocenti = $dettagli["quantiDocenti"];
    ?>
    <li class="collection-item">
      <div class="row valign-wrapper" style="margin-bottom:0px;">
        <div class="col s1 valign bold">
          <i onclick="eliminaCorso(<?php echo $dettagli['id']; ?>)" class="material-icons waves-effect waves-accent accent-text waves-circle" style="border-radius:50%; display:inline; margin:0em;">close</i>
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
          if ($quantiDocenti > 1) {
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
          <a class="waves-effect small-icon-corsi condensed waves-accent fill-width fake-button valign accent-text" onclick="mostraModalDettagli(<?php echo $dettagli['id']; ?>, <?php echo $dettagli['idDocente']; ?>)" style="width:98%;">
            <i class="material-icons">more_horiz</i> <br/>DETTAGLI
          </a>
        </div>
      </div>
    </li>
    <?php
  }

    if($numRisultato>$quanti){
      ?>
      <li class="collection-item row center">
        <div class"center">
          <ul class="pagination center">
              <li <?php if($pagina==1) echo 'class="disabled"'?>class="waves-effect">
                  <a onclick="vaiPagina(<?php echo $pagina - 1;?>)">
                    <i class="material-icons">chevron_left</i>
                  </a>
              </li>
              <?php
                 $i = 1;
                 $numPagine = ceil($numRisultato/$quanti);
                  while($i<=$numPagine){
                      ?>
                        <li class="waves-effect waves-accent <?php if($i==$pagina) echo "active"?>">
                              <a onclick="vaiPagina(<?php echo $i;?>)">
                                <?php echo $i; ?>
                              <a/>
                        </li>
                    <?php
                      $i++;
                  }
                  ?>
                  <li <?php if($pagina==$numPagine) echo 'class="disabled"'?>class="waves-effect">
                      <a onclick="vaiPagina(<?php echo $pagina+1;?>)">
                        <i class="material-icons">chevron_right</i>
                      </a>
                  </li>
          </ul>
        </div>
      </li>
      <?php
    }
    ?>
