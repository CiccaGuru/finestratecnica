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
?>
  <?php
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
  ?>
  <div class="modal-content">
    <h4 class="blue-text light condensed center" style="margin-bottom:1.5em">Dettagli corso</h4>
    <div id="dettagliCorsoAdmin">
      <div class="row valign-wrapper">
        <div class="col s1 valign bold condensed letter-spacing-1">TITOLO:</div>
        <div class="input-field valign col s4">
          <input value="<?php echo $dettagliCorso["titolo"]?>" id="titoloCorso" type="text">
        </div>
        <div class="col s1 bold valign condensed letter-spacing-1">PROF:</div>
        <div class="col s2 valign">
          <select id="selezionaDocentiCorso">
            <option value="" disabled selected class="grey-text">Seleziona insegnante</option>
          </select>
        </div>
        <div class="col s2 condensed">
          <p>
            <input name="tipo" value="0" type="radio" id="recupero" <?php if(!$dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="recupero">Recupero</label>
          </p>
          <p>
            <input name="tipo" value="1" type="radio" id="approfondimento" <?php if($dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="approfondimento">Approfondimento</label>
          </p>
         </div>
        <div class="col s2  condensed">
          <p>
            <input name="continuita" value="1" type="radio" id="con_continuita" <?php if($dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="con_continuita">Con continuità</label>
          </p>
          <p>
            <input name="continuita" value="0" type="radio" id="senza_continuita" <?php if(!$dettagliCorso["tipo"]) echo "checked";?>/>
            <label class="black-text" for="senza_continuita">Senza continuità</label>
          </p>
        </div>
      </div>
      <div class="row valign-wrapper">
        <div class="col s2 bold  valign condensed letter-spacing-1">DESCRIZIONE:</div>
        <div class="col s7 valign">
          <textarea id="descrizioneCorso" class="materialize-textarea"><?php echo $dettagliCorso["descrizione"]?></textarea>
        </div>
        <div class="col s1 offset-s1 bold valign condensed letter-spacing-1">CLASSI: </div>
        <div class="col s2">

          <?php
            $resultClassi = $db->query("SELECT classe from corsi_classi where id_corso = '$idCorso'") or die($db->error);
            while($classe = $resultClassi->fetch_assoc()){
              $elencoClassi[]=$classe["classe"];
            }

          ?>
          <p class="littlemargin">
            <input type="checkbox" class="filled-in" id="prime" <?php if(in_array(1, $elencoClassi)) echo "checked";?> />
            <label for="prime">Prime</label>
          </p>
          <p class="littlemargin">
            <input type="checkbox" class="filled-in" id="seconde" <?php if(in_array(2, $elencoClassi)) echo "checked";?>/>
            <label for="seconde">Seconde</label>
          </p>
          <p class="littlemargin">
            <input type="checkbox" class="filled-in" id="terze" <?php if(in_array(3, $elencoClassi)) echo "checked";?> />
            <label for="terze">Terze</label>
          </p>
        </div>
        <div class="col s2">
          <p class="littlemargin">
            <input type="checkbox" class="filled-in" id="quarte" <?php if(in_array(4, $elencoClassi)) echo "checked";?>/>
            <label for="quarte">Quarte</label>
          </p>
          <p class="littlemargin">
            <input type="checkbox" class="filled-in" id="quinte" <?php if(in_array(5, $elencoClassi)) echo "checked";?> />
            <label for="quinte">Quinte</label>
          </p>
        </div>
      </div>
    </div>
 <div class="divider" style="margin-bottom:3em;"></div>
  <?php
  $result = $db->query("SELECT id, ora, titolo, aula, maxIscritti from lezioni  WHERE idCorso = $idCorso order by ora asc") or die($db->error);
  $i = 0;
  while($lezione = $result->fetch_assoc()){

    $resultConta = $db->query("SELECT COUNT(*) as count from iscrizioni  WHERE idLezione = '".$lezione["id"]."' and partecipa = '1'") or die($db->error);
    $conta = $resultConta->fetch_assoc();
    if($lezione["titolo"]=='<span class=”italic”>Nessuna descrizione</span>'){
      $titolo = 'Nessuna descrizione';
    }
    else {
      $titolo = $lezione["titolo"];
    }

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

    ?>

    <div class="row valign-wrapper" id="row<?php echo $i;?>" data-idCorso="<?php echo $idCorso;?>" data-idLezione="<?php echo $lezione["id"];?>">
      <div class="col s1">
        <i class="material-icons waves-effect right red-text waves-red waves-circle" style="border-radius:50%;">close</i>
      </div>
      <div class="col s1">
       <span class="valign bold condensed" style="display:inline;">   ID: <?php echo $lezione["id"];?></span>
      </div>

      <div class="input-field col valign s3">
        <input id="titoloModificaOre<?php echo $i;?>" type="text" class="validate" value='<?php echo $titolo;?>' required>
        <label class="active condensed" for="titoloModificaOre<?php echo $lezione["id"];?>">Titolo</label>
      </div>
      <div class="input-field col valign s1">
        <input disabled style="color:black" id="iscrittiModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $conta["count"];?>" required>
        <label class="active condensed" for="iscrittiModificaOre<?php echo $i;?>">Iscritti</label>
      </div>
      <div class="input-field col valign s1">
        <input id="maxIscrittiModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $lezione["maxIscritti"];?>" required>
        <label class="active condensed" for="maxIscrittiModificaOre<?php echo $lezione["id"];?>">Alunni Max.</label>
      </div>
      <div class="input-field col valign s1">
        <input id="aulaModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $lezione["aula"];?>" required>
        <label class="active condensed" for="aulaModificaOre<?php echo $lezione["id"];?>">Aula</label>
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
