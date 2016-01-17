<?php
include 'funzioni.php';
include 'config.php';
global $_CONFIG;
error_reporting(E_ALL & ~E_NOTICE);
$active = $_POST["active"];
$utente = check_login();
$ore_utente = array();
$aule_utente = array();
$corsi_utente = array();
if($utente==-1){
    die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die("LOGINPROBLEM");
}
$condizione = "";

if(($_POST["concontinuita"] == '1') && ($_POST["senzacontinuita"] == '0')){
    $condizione .= " corsi.continuita = '1' AND ";
}
if(($_POST["concontinuita"] == '0') && ($_POST["senzacontinuita"] == '1')){
    $condizione .= " corsi.continuita = '0' AND ";
}
if(($_POST["concontinuita"] == '0') && ($_POST["senzacontinuita"] == '0')){
    $condizione .= " corsi.continuita = '2' AND ";
}
if(($_POST["recupero"] == '1') && ($_POST["approfondimento"] == '0')){
    $condizione .= " corsi.tipo = '0' AND ";
}
if(($_POST["recupero"] == '0') && ($_POST["approfondimento"] == '1')){
    $condizione .= " corsi.tipo = '1' AND ";
}
if(($_POST["recupero"] == '0') && ($_POST["approfondimento"] == '0')){
    $condizione .= " corsi.tipo = '2' AND ";
}

if(isset($_POST["filtro"])){
  if(!($_POST["filtro"]=="")){
    $filtro = $_POST["filtro"];
    $filtro = str_replace("à", "&agrave", $_POST["filtro"]);
    $filtro = str_replace("è", "&egrave", $filtro);
    $filtro = str_replace("ì", "&igrave", $filtro);
    $filtro = str_replace("ò", "&ograve", $filtro);
    $filtro = str_replace("é", "&eacuto", $filtro);
    $condizione .= "(utenti.nome LIKE '%secure($filtro%' OR utenti.cognome LIKE '%$filtro%' OR corsi.descrizione LIKE '%$filtro%' OR corsi.titolo LIKE '%$filtro%')";
  }
  else{
    $condizione .= "1";
  }
}
else{
  $condizione .= '1';
}

$db = database_connect();
$result = $db->query("SELECT classe from utenti where id='$utente'") or die('ERRORE: ' . $db->error);
$dettagliUtente = $result->fetch_assoc();
$result = $db->query('SELECT idLezione, idCorso from iscrizioni where idUtente='.$utente) or  die('ERRORE: 0 ' . $db->error);
while($ora_el = $result->fetch_assoc()){
  $ore_utente[]=$ora_el["idLezione"];
  if(!(in_array($ora_el["idCorso"], $corsi_utente))){
    $corsi_utente[]=$ora_el["idCorso"];
  }
}

function sono_iscritto($array){
  global $corsi_utente;
  foreach ($array as $corso) {
    if(in_array($corso, $corsi_utente)){
      return 1;
    }
  }
  return 0;
}



function generaLista($tipo, $continuita){
  global $_CONFIG;
  global $corsi_utente;
  global $ore_utente;
  global $utente;
  global $active;
  global $db;
  global $condizione;
  global $dettagliUtente;
  $corsi_particolari_uno = array('275', '391', '392');
  $corsi_particolari_due = array('113', '114', '115');
  $corsi_obbligati =array('190', '249',  '68', '230', '350', '69', '83', '67', '179');
  $result = $db->query("SELECT  corsi.titolo AS titolo,
                                corsi.descrizione AS descrizione,
                                corsi.iddocente AS iddocente,
                                corsi.id as id,
                                utenti.nome as nome,
                                utenti.cognome as cognome
                        FROM    corsi, utenti, corsi_classi
                        WHERE   (utenti.id = corsi.iddocente) AND
                                corsi.id = corsi_classi.id_corso AND
                                corsi_classi.classe = '".$dettagliUtente["classe"]."' AND
                                corsi.tipo = '$tipo' AND
                                corsi.continuita = '$continuita' AND
                                $condizione") or die($db->error);

if($result->num_rows == 0){
  ?>
    <h5 class="center italic light" style="font-size:120%;"> Nessun corso trovato.</h5>
  <?php
}
  while($dettagliCorso = $result->fetch_assoc())
  {
    $idCorso = $dettagliCorso["id"];
    ?>
    <li style="border-bottom:0px;" id="collapsibleCorso<?php echo $idCorso; ?>" class="<?php
    if($active==$idCorso){
      echo "active";
    }?>">
    <div style="border-bottom:0px;" data-id="<?php echo $idCorso; ?>" class="collapsible-header  <?php
    if($active==$idCorso){
      echo "active";
    }
    if(in_array($idCorso,$corsi_utente)){
      echo ' light-blue lighten-2';
    }
    else{
      echo ' black-text';
    }?>" id="collapsible<?php echo $idCorso?>">
    <span class="ink"></span>
    <div class="row" style="margin-bottom:0px;">
      <div class="truncate col l2 m3 s4">
        <span class="bold" ><?php echo $dettagliCorso["titolo"];?></span>
      </div>
      <div class="truncate col l3 m3 s5">
        <span>
          <?php
            if($dettagliCorso["iddocente"] == '0'){
              echo "<span class='italic'>Docenti vari</span>";
            }
            else {
              echo $dettagliCorso["nome"][0].'. '.$dettagliCorso["cognome"];
            }
          ?>
        </span>
      </div>
      <div class="truncate hide-on-small-only col l5 m4">
        <span><?php echo $dettagliCorso["descrizione"];?></span>
      </div>
      <div class="col s3 m2">
        <?php
        $coincide = coincideCorso($idCorso, $db, $utente);
        $Tiscritti = troppiIscrittiCorso($idCorso, $continuita);

        if(!in_array($idCorso,$corsi_utente) && $Tiscritti){
          ?>
          <div class="chip light  red darken-1 white-text">
            Esaurito
          </div>
          <?php
        } else if((!in_array($idCorso,$corsi_utente) && $coincide) || (in_array($idCorso, $corsi_particolari_uno) && sono_iscritto($corsi_particolari_uno) && !in_array($idCorso,$corsi_utente) || (in_array($idCorso, $corsi_particolari_due) && !(in_array($idCorso,$corsi_utente)) && sono_iscritto($corsi_particolari_due)))){
          ?>
          <div class="chip waves-effect hoverable waves-light amber darken-1 coincidenzaTrigger tooltipped" data-position="bottom" data-delay="50" data-tooltip="Fai click per info" style="cursor:pointer; z-index:1000;" onclick="mostraCoincidenze(<?php echo $idCorso; ?>)">
            Coincide
          </div>

          <?php
        } else {
            if(in_array($idCorso, $corsi_obbligati) && in_array($idCorso, $corsi_utente)){
             ?> <div class="chip green darken-1" data-position="bottom"  style="z-index:1000;">
               Obbligatorio
             </div><?php
           }else{
          ?>
          <div class="switch">
            <label for="iscriviCorso<?php echo $idCorso ?>">
              <input type="checkbox" data-id-corso="<?php echo $idCorso ?>" id="iscriviCorso<?php echo $idCorso ?>" class="iscriviCorso"
              <?php
              if(in_array($idCorso,$corsi_utente)){
                  echo ' checked="checked"';
              }
              ?>
              />
              <span class="lever"></span>
            </label>
          </div>
          <?php  } }?>
        </div>
      </div>
    </div>
    <div class="collapsible-body">
      <div class="center" style="width:100%; margin:1em;">

      <div class="preloader-wrapper center big active">
        <div class="spinner-layer center spinner-red-only">
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
        </div>
      </li>
      <?php
    }
  }
?>

<div>
  <h4 class="thin light-blue-text center" style="margin-bottom:0px;"> Corsi di recupero</h4>
  <h5 class="light light-blue-text center" style="margin-bottom:0px;">Senza continuità</h5>
</div>
<ul style="margin-top:1em; margin-bottom:3em" class="collapsible popout" style="display:none" data-collapsible="accordion">
  <?php
  generaLista(0, 0);
  ?>
</ul>
<div>
  <h5 class="light light-blue-text center" style="margin-bottom:0px;">Con continuità</h5>
</div>

<ul style="margin-top:1em; margin-bottom:3em" class="collapsible popout" style="display:none" data-collapsible="accordion">
  <?php
  generaLista(0, 1);
  ?>
</ul>
<div>
  <h4 class="thin light-blue-text center" style="margin-bottom:0px;">Corsi di approfondimento</h4>
  <h5 class="light light-blue-text center" style="margin-bottom:0px;">Senza continuità</h5>
</div>
<ul style="margin-top:1em; margin-bottom:3em" class="collapsible popout" style="display:none" data-collapsible="accordion">
  <?php
  generaLista(1, 0);
  ?>
</ul>
<div>
  <h5 class="light light-blue-text center" style="margin-bottom:0px;">Con continuità</h5>
</div>
<ul style="margin-top:1em; margin-bottom:3em" class="collapsible popout" style="display:none" data-collapsible="accordion">
  <?php
  generaLista(1, 1);
  ?>
</ul>
<?php
$db->close();
?>
