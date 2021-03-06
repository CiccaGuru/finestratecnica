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
  if ($user_level == 1) {
    header('Location: docente.php');
  }
}

$db = database_connect();
$idCorso = $_POST['idCorso'];
$ore = $_POST['ore'];

$result = $db->query("SELECT  corsi.titolo as titolo,
  corsi.descrizione as descrizione,
  corsi.continuita as continuita,
  corsi.tipo as tipo,
  utenti.nome as nome,
  utenti.cognome as cognome,
  corsi_docenti.idDocente as idDocente,
  corsi.id as id, COUNT(idCorso)
  FROM corsi,utenti,corsi_docenti
  WHERE (utenti.id = corsi_docenti.idDocente) AND
  (corsi.id = corsi_docenti.idCorso) AND
  corsi.id = '$idCorso'") or die($db->error);
  $dettagliCorso = $result->fetch_assoc();

  $resultClassi = $db->query("SELECT classe from corsi_classi where idCorso = '$idCorso'") or die($db->error);
  while ($classe = $resultClassi->fetch_assoc()) {
    $elencoClassi[] = $classe['classe'];
  }
  ?>
  <div class="modal-content">
    <h4 class="primary-text light condensed center" style="margin-bottom:1.5em">Dettagli corso</h4>
    <div id="dettagliCorsoAdmin">
      <div class="row valign-wrapper">
        <div class="col s1 valign bold condensed letter-spacing-1">TITOLO:</div>
        <div class="input-field valign col s4">
          <input value="<?php echo $dettagliCorso['titolo']?>" id="titoloCorsoModifica" type="text">
        </div>
        <div class="col s1 bold valign condensed letter-spacing-1">CLASSI: </div>
        <div class="col s2">
          <select id="classiCorsoModifica" multiple>
            <option value="1" <?php if (in_array(1, $elencoClassi)) echo 'selected';?>>1</option>
            <option value="2" <?php if (in_array(2, $elencoClassi)) echo 'selected';?>>2</option>
            <option value="3" <?php if (in_array(3, $elencoClassi)) echo 'selected';?>>3</option>
            <option value="4" <?php if (in_array(4, $elencoClassi)) echo 'selected';?>>4</option>
            <option value="5" <?php if (in_array(5, $elencoClassi)) echo 'selected';?>>5</option>
          </select>
          <label>Classi</label>
        </div>
        <div class="col s2 offset-s1 condensed">
          <p>
            <input name="tipoCorsoModifica" value="0" type="radio" id="recuperoModifica" <?php if (!$dettagliCorso['tipo']) echo 'checked';?>/>
            <label class="black-text" for="recuperoModifica">Recupero</label>
          </p>
          <p>
            <input name="tipoCorsoModifica" value="1" type="radio" id="approfondimentoModifica" <?php if ($dettagliCorso['tipo']) echo 'checked';?>/>
            <label class="black-text" for="approfondimentoModifica">Approfondimento</label>
          </p>
        </div>
        <div class="col s2  condensed">
          <p>
            <input name="continuitaCorsoModifica" value="1" type="radio" id="con_continuitaModifica" <?php if ($dettagliCorso['tipo']) echo 'checked';?>/>
            <label class="black-text" for="con_continuitaModifica">Con continuità</label>
          </p>
          <p>
            <input name="continuitaCorsoModifica" value="0" type="radio" id="senza_continuitaModifica" <?php if (!$dettagliCorso['tipo']) echo 'checked';?>/>
            <label class="black-text" for="senza_continuitaModifica">Senza continuità</label>
          </p>
        </div>
      </div>
      <div class="row valign-wrapper">
        <div class="col s2 bold  valign condensed letter-spacing-1">DESCRIZIONE:</div>
        <div class="col s6 valign">
          <textarea id="descrizioneCorsoModifica" class="materialize-textarea"><?php echo $dettagliCorso['descrizione']?></textarea>
        </div>
        <div class="col s2 bold condensed letter-spacing-1">
          DOCENTI:
        </div>
        <div class="col s4" style="margin-bottom:0px; margin-top:1em;">
          <div id="ChipsDocentiDettagli">

          </div>
          <a class="waves-effect animateButton row valign-wrapper small-icon-corsi condensed waves-accent fill-width fake-button valign accent-text" onclick="mostraModalScegliDocenti(<?php echo $dettagliCorso['id'];?>)">
            <div class="col s2 offset-s1 valign">
              <i class="material-icons" style="margin:0px;">add</i>
            </div>
            <div class="col s7 valign center-align" style="margin-top:4px;">
              AGGIUNGI DOCENTE
            </div>
          </a>
        </div>
        </div>
      <div class="row">
        <div class="col s2 bold condensed letter-spacing-1">
          OBBLIGATORIO PER:
        </div>
        <div class="col s3 valign">
          <div id="ChipsObbligatori">

          </div>
          <a class="waves-effect animateButton row valign-wrapper small-icon-corsi condensed waves-accent fill-width fake-button valign accent-text" onclick="mostraModalCorsiObbligatori(<?php echo $dettagliCorso['id'];?>)">
            <div class="col s2 offset-s1 valign">
              <i class="material-icons" style="margin:0px;">add</i>
            </div>
            <div class="col s7 valign center-align" style="margin-top:4px;">
              AGGIUNGI CLASSE
            </div>
          </a>
        </div>
        <div class="col s2 bold offset-s1 condensed letter-spacing-1">
          INCOMPATIBILE CON:
        </div>
        <div class="col s3 valign">
          <div id="ChipsIncompatibili">

          </div>
          <a class="waves-effect animateButton row valign-wrapper small-icon-corsi condensed waves-accent fill-width fake-button valign accent-text" onclick="mostraModalCorsiIncompatibili(<?php echo $dettagliCorso["id"]; ?>)">
            <div class="col s2 offset-s1 valign">
              <i class="material-icons" style="margin:0px;">add</i>
            </div>
            <div class="col s7 valign center-align " style="margin-top:4px;" >
              AGGIUNGI CORSO
            </div>
          </a>
        </div>
      </div>
    </div>


    <div class="divider" style="margin-bottom:3em;"></div>
    <div id="idCorsoAttuale" data-idcorso="<?php echo $dettagliCorso["id"]; ?>" style="display:none;"></div>
    <div id="listaOreModifica">

    </div>
  </div>
  <div class="modal-footer">
    <a href="#!" class="waves-effect waves-light white-text accent btn"  onclick="applicaModificaOre(<?php echo $idCorso;?>)">
      Applica
    </a>
    <a href="#!" class="modal-action modal-close waves-effect waves-accent accent-text btn-flat ">
      Chiudi
    </a>
  </div>
