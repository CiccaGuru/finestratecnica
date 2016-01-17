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

<div class="modal-content">
  <h4 class="light-blue-text thin center">Modifica ore</h4>
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
      <div class="col s1 bold valign valign-wrapper">
        <span class="valign"><?php echo $lezione["id"];?>:</span>
      </div>
      <div class="input-field col valign s3">
        <input id="titoloModificaOre<?php echo $i;?>" type="text" class="validate" value='<?php echo $titolo;?>' required>
        <label class="active" for="titoloModificaOre<?php echo $lezione["id"];?>">Titolo</label>
      </div>
      <div class="input-field col valign s1">
        <input disabled style="color:black" id="iscrittiModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $conta["count"];?>" required>
        <label class="active" for="iscrittiModificaOre<?php echo $i;?>">Iscritti</label>
      </div>
      <div class="input-field col valign s1">
        <input id="maxIscrittiModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $lezione["maxIscritti"];?>" required>
        <label class="active" for="maxIscrittiModificaOre<?php echo $lezione["id"];?>">Max.</label>
      </div>
      <div class="input-field col valign s2">
        <input id="aulaModificaOre<?php echo $i;?>" type="text" class="validate" value="<?php echo $lezione["aula"];?>" required>
        <label class="active" for="aulaModificaOre<?php echo $lezione["id"];?>">Aula</label>
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
