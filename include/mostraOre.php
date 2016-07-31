<?php
require_once('funzioni.php'); // Includes Login Script
$utente = check_login();
if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
  header('Location: userhome.php');
  if($user_level == 1)
  header('Location: docente.php');
}

$db = database_connect();
$idCorso = $_POST["idCorso"];
$ore=$_POST["ore"];
global $_CONFIG;

  $result = $db->query("SELECT  corsi.titolo AS titolo,
                                corsi.descrizione AS descrizione,
                                corsi.iddocente AS iddocente,
                                corsi.id as id,
                                corsi.continuita as continuita,
                                corsi.tipo as tipo,
                                utenti.nome as nome,
                                utenti.cognome as cognome
                        FROM    corsi, utenti
                        WHERE   (utenti.id = corsi.iddocente) AND
                                corsi.id = '$idCorso'") or die($db->error);
  $dettagliCorso=$result->fetch_assoc();

  $resultConta = $db->query("SELECT COUNT(*) as count from iscrizioni  WHERE idLezione = '".$lezione["id"]."' and partecipa = '1'") or die($db->error);
  $conta = $resultConta->fetch_assoc();

  $giorni="";
  $ore_elenco="";
  $numGiorno = (int) ($lezione["ora"] / $_CONFIG["ore_per_giorno"])+1;
  $numOra = $lezione["ora"] % $_CONFIG["ore_per_giorno"];

  if($numOra == 0){
    $numOra = 6;
    $numGiorno --;
  }
  foreach($_CONFIG['giorni'] as $num=>$nome){
    if($num == $numGiorno){
      $giorni .= '<option value="'.$num.'" selected>'.$nome.'</option>';
    }
    else{
      $giorni .= '<option value="'.$num.'" >'.$nome.'</option>';
    }
  }

  for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
    if($j == $numOra){
      $ore_elenco .= '<option value="'.$j.'" selected>'.$j.'^a ora</option>';
    }
    else{
      $ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
    }
  }

  $aule = "";
  $resultAule = $db->query("SELECT * from aule") or die("Error: ".$db->error);
  while($aula = $resultAule->fetch_assoc()){
    $aule .= '<option value="'.$aula["id"].'">Aula '.$aula["nomeAula"].', '.$aula["maxStudenti"].' alunni</option>';
  }

  $resultClassi = $db->query("SELECT classe from corsi_classi where idCorso = '$idCorso'") or die($db->error);
  while($classe = $resultClassi->fetch_assoc()){
    $elencoClassi[]=$classe["classe"];
  }
  ?>
  <div class="modal-content">
    <h4 class="blue-text light condensed center" style="margin-bottom:1.5em">Dettagli corso</h4>
    <div id="dettagliCorsoAdmin">
      <div class="row valign-wrapper">
        <div class="col s1 valign bold condensed letter-spacing-1">TITOLO:</div>
        <div class="input-field valign col s4">
          <input value="<?php echo $dettagliCorso["titolo"]?>" id="titoloCorsoModifica" type="text">
        </div>
        <div class="col s1 bold valign condensed letter-spacing-1">PROF:</div>
        <div class="col s2 valign">
          <select id="docenteCorsoModifica">
            <option value="" disabled selected class="grey-text">Seleziona insegnante</option>
          </select>
        </div>
        <div class="col s2 condensed">
          <p>
            <input name="tipoCorsoModifica" value="0" type="radio" id="recuperoModifica" <?php if(!$dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="recuperoModifica">Recupero</label>
          </p>
          <p>
            <input name="tipoCorsoModifica" value="1" type="radio" id="approfondimentoModifica" <?php if($dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="approfondimentoModifica">Approfondimento</label>
          </p>
         </div>
        <div class="col s2  condensed">
          <p>
            <input name="continuitaCorsoModifica" value="1" type="radio" id="con_continuitaModifica" <?php if($dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="con_continuitaModifica">Con continuità</label>
          </p>
          <p>
            <input name="continuitaCorsoModifica" value="0" type="radio" id="senza_continuitaModifica" <?php if(!$dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="senza_continuitaModifica">Senza continuità</label>
          </p>
        </div>
      </div>
      <div class="row valign-wrapper">
        <div class="col s2 bold  valign condensed letter-spacing-1">DESCRIZIONE:</div>
        <div class="col s7 valign">
          <textarea id="descrizioneCorsoModifica" class="materialize-textarea"><?php echo $dettagliCorso["descrizione"]?></textarea>
        </div>
        <div class="col s1 offset-s1 bold valign condensed letter-spacing-1">CLASSI: </div>
        <div class="col s2">
            <select id="classiCorsoModifica" multiple>
              <option value="1" <?php if(in_array(1,$elencoClassi)) echo "selected"?>>1</option>
              <option value="2" <?php if(in_array(2,$elencoClassi)) echo "selected"?>>2</option>
              <option value="3" <?php if(in_array(3,$elencoClassi)) echo "selected"?>>3</option>
              <option value="4" <?php if(in_array(4,$elencoClassi)) echo "selected"?>>4</option>
              <option value="5" <?php if(in_array(5,$elencoClassi)) echo "selected"?>>5</option>
            </select>
            <label>Classi</label>
          </div>
      </div>
      <div class="row">
        <div class="col s2 bold condensed letter-spacing-1">
          OBBLIGATORIO PER:
        </div>
        <div class="col s3 valign">
          <div class="chip">
            1 Adl
            <i class="material-icons">close</i>
          </div>
          <a class="waves-effect animateButton row valign-wrapper small-icon-corsi condensed waves-red fill-width fake-button valign red-text" onclick="mostraModalDettagli(<?php echo $dettagli['id'];?>, <?php echo $dettagli["idDocente"];?>)" style="width:98%;">
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
          <div class="chip">
            Italiano 15
            <i class="material-icons">close</i>
          </div>
          <a class="waves-effect animateButton row valign-wrapper small-icon-corsi condensed waves-red fill-width fake-button valign red-text" onclick="mostraModalDettagli(<?php echo $dettagli['id'];?>, <?php echo $dettagli["idDocente"];?>)" style="width:98%;">
            <div class="col s2 offset-s1 valign">
              <i class="material-icons" style="margin:0px;">add</i>
            </div>
            <div class="col s7 valign center-align" style="margin-top:4px;">
              AGGIUNGI CORSO
            </div>
          </a>
        </div>
        </div>
      </div>


 <div class="divider" style="margin-bottom:3em;"></div>
  <?php
  $result = $db->query("SELECT lezioni.id, lezioni.ora,  lezioni.titolo, aule.nomeAula as aula, aule.maxStudenti as maxIscritti
                          from lezioni, aule
                          WHERE idCorso = $idCorso and
                                lezioni.idAula = aule.id
                          order by ora asc") or die($db->error);
  $i = 0;
  while($lezione = $result->fetch_assoc()){
    ?>

    <div class="row valign-wrapper" id="row<?php echo $i;?>" data-idCorso="<?php echo $idCorso;?>" data-idLezione="<?php echo $lezione["id"];?>">
      <div class="col s1">
        <i class="material-icons waves-effect red-text waves-red waves-circle" style="border-radius:50%; margin-right:10px; display:inline;">close</i>
        <span class="valign bold condensed" style="display:inline;">   ID: <?php echo $lezione["id"];?></span>
      </div>
      <div class="input-field col valign s3">
        <input id="titoloModificaOre<?php echo $i;?>" type="text" class="validate" value='<?php echo $lezione["titolo"];?>' required>
        <label class="active condensed" for="titoloModificaOre<?php echo $lezione["id"];?>">Titolo</label>
      </div>
      <div class="input-field col s3">
				 <select id="selezionaAulaModificaOre<?php echo $i;?>">
				  <?php echo $aule; ?>
				</select>
				<label>Aula</label>
			</div>
      <div class="input-field col valign s1">
        <input disabled style="color:black" id="iscrittiModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $conta["count"]."/".$lezione["maxIscritti"];?>" required>
        <label class="active condensed" style="color:black" for="iscrittiModificaOre<?php echo $i;?>">Iscritti</label>
      </div>
      <div class="input-field col s2">
        <select id="selezionaGiornoModificaOre<?php echo $i?>">
          <?php echo $giorni;?>
        </select>
        <label>Giorno</label>
      </div>
      <div class="col s2 input-field">
        <select id="selezionaOraModificaOre<?php echo $i?>">
          <?php echo $ore_elenco;?>
        </select>
        <label>Ora</label>
      </div>
    </div>

    <?php
    $i++;
  }

  ?>
</div>
<div class="modal-footer">
  <a href="#!" class="waves-effect waves-light white-text red btn"  onclick="applicaModificaOre(<?php echo $idCorso;?>)">
    Applica
  </a>
  <a href="#!" class="modal-action modal-close waves-effect waves-red red-text btn-flat ">
    Chiudi
  </a>
</div>
