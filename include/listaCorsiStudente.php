<?php
include dirname(__FILE__).'/funzioni.php';
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
    $filtro = replace($_POST["filtro"]);
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
$result = $db->query("SELECT classe, idClasse from utenti where id='$utente'") or die('ERRORE: ' . $db->error);
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
  global $corsi_utente;
  global $ore_utente;
  global $utente;
  global $active;
  global $db;
  global $condizione;
  global $dettagliUtente;
  $classe = $dettagliUtente["classe"];
  $corsi_particolari_uno = array('275', '391', '392');
  $corsi_particolari_due = array('113', '114', '115');
  $corsi_obbligati = array();
  $result = $db->query("SELECT idCorso from corsi_obbligatori where idClasse = ".$dettagliUtente["idClasse"]) or die($db->error);
  while($corsoObbl = $result->fetch_assoc()){
    $corsi_obbligati[] = $corsoObbl["idCorso"];
  }
  $result = $db->query("SELECT  corsi.titolo AS titolo,
                                corsi.descrizione AS descrizione,
                                corsi_docenti.idDocente AS iddocente,
                                corsi.id as id,
                                utenti.nome as nome,
                                utenti.cognome as cognome,
                                COUNT(corsi_docenti.idDocente) as quantiDocenti
                        FROM    corsi, utenti, corsi_classi, corsi_docenti
                        WHERE   (utenti.id = corsi_docenti.idDocente) AND
                                corsi.id = corsi_classi.idCorso AND
                                corsi.id = corsi_docenti.idCorso AND
                                corsi_classi.classe = '$classe' AND
                                corsi.tipo = '$tipo' AND
                                corsi.continuita = '$continuita' AND
                                $condizione
                        GROUP BY corsi_docenti.idCorso
                        ORDER BY corsi.titolo") or die($db->error);

if($result->num_rows == 0){
  ?>
    <h5 class="center italic light error-nessun-corso"> Nessun corso trovato.</h5>
  <?php
}
  while($dettagliCorso = $result->fetch_assoc())
  {
    $idCorso = $dettagliCorso["id"];
    ?>
    <li id="collapsibleCorso<?php echo $idCorso; ?>" class="collapsibleCorso <?php
    if($active==$idCorso){
      echo "active";
    }?>">
    <div data-id="<?php echo $idCorso; ?>" class="collapsible-header  <?php
    if($active==$idCorso){
      echo "active";
    }
    if(in_array($idCorso,$corsi_utente)){
      echo ' primary-light lighten-3';
    }
    else{
      echo ' black-text';
    }?>" id="collapsible<?php echo $idCorso?>">
    <span class="ink"></span>
    <div class="row margin0">
      <div class="truncate col l3 m3 s4">
        <span class="bold" ><?php echo $dettagliCorso["titolo"];?></span>
      </div>
      <div class="truncate col l3 m3 s5">
        <span>
          <?php
            if($dettagliCorso["quantiDocenti"] > 1){
              echo "<span class='italic'>Docenti vari</span>";
            }
            else {
              echo $dettagliCorso["nome"][0].'. '.$dettagliCorso["cognome"];
            }
          ?>
        </span>
      </div>
      <div class="truncate hide-on-small-only col l4 m4">
        <span><?php echo $dettagliCorso["descrizione"];?></span>
      </div>
      <div class="col s3 m2 center">
        <?php
        $coincide = coincideCorso($idCorso, $db, $utente);
        $Tiscritti = troppiIscrittiCorso($idCorso, $continuita);

        if(!in_array($idCorso,$corsi_utente) && $Tiscritti){
          ?>
          <div class="chipMio hide-on-small-only light center center-align red white-text white-text">
            ESAURITO
          </div>
          <div class="chipMio hide-on-med-and-up light center red-text center-align text-darken-2 white-text">
            ESAURITO
          </div>
          <?php
        } else if((!in_array($idCorso,$corsi_utente) && $coincide)){
          ?>
          <div class="chipMio hide-on-small-only center center-align waves-effect hoverable waves-light amber darken-2 white-text coincidenzaTrigger" onclick="mostraCoincidenze(<?php echo $idCorso; ?>)">
            COINCIDE
          </div>
          <div class="chipMio hide-on-med-and-up center center-align waves-effect hoverable waves-amber amber-text text-darken-4 coincidenzaTrigger" onclick="mostraCoincidenze(<?php echo $idCorso; ?>)">
            COINCIDE
          </div>
          <?php
        }
        else {
          $resultIncompatibili = $db->query("SELECT idCorso2 from corsi_incompatibili where idCorso1 = $idCorso") or die($db->error);
          $incompatibile = 0;
          while($corsoIncompatibile = $resultIncompatibili->fetch_assoc()){
            if(in_array($corsoIncompatibile["idCorso2"], $corsi_utente)){
              $incompatibile = 1;
            }
          }
          if($incompatibile){
          ?>
          <div class="chipMio hide-on-small-only center center-align waves-effect hoverable waves-light amber darken-2 white-text coincidenzaTrigger" onclick="mostraIncompatibilita(<?php echo $idCorso; ?>)">
            INCOMPAT.
          </div>
          <div class="chipMio hide-on-med-and-up center center-align waves-effect hoverable waves-amber amber-text text-darken-4 coincidenzaTrigger" onclick="mostraIncompatibilita(<?php echo $idCorso; ?>)">
            INCOMPAT.
          </div>
          <?php
          }
          else{
            if(in_array($idCorso, $corsi_obbligati) && in_array($idCorso, $corsi_utente)){
             ?>
             <div class="chipMio center center-align primary darken-2 white-text hide-on-small-only" data-position="bottom">
               OBBLIG.
             </div>
             <div class="chipMio primary-light center center-align lighten-3 hide-on-med-and-up primary-text text-darken-4" data-position="bottom">
               OBBLIG.
             </div>
          <?php
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
          <?php  } }}?>
        </div>
      </div>
    </div>
    <div class="collapsible-body">
      <div class="center">

      <div class="preloader-wrapper center big active">
        <div class="spinner-layer center spinner-accent-only">
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
  <h4 class="light condensed letter-spacing-1 primary-text center margin0"> CORSI DI RECUPERO</h4>
  <h5 class="light condensed letter-spacing-1 primary-text center margin0">Senza continuità</h5>
</div>
<ul class="collapsible popout" data-collapsible="accordion">
  <?php
  generaLista(0, 0);
  ?>
</ul>
<div>
  <h5 class="light condensed letter-spacing-1 primary-text center margin0">Con continuità</h5>
</div>

<ul class="collapsible popout" data-collapsible="accordion">
  <?php
  generaLista(0, 1);
  ?>
</ul>
<div>
  <h4 class="light condensed letter-spacing-1 primary-text center margin0">CORSI DI APPROFONDIMENTO</h4>
  <h5 class="light condensed letter-spacing-1 primary-text center margin0">Senza continuità</h5>
</div>
<ul class="collapsible popout" data-collapsible="accordion">
  <?php
  generaLista(1, 0);
  ?>
</ul>
<div>
  <h5 class="light condensed letter-spacing-1 primary-text center margin0">Con continuità</h5>
</div>
<ul class="collapsible popout" data-collapsible="accordion">
  <?php
  generaLista(1, 1);
  ?>
</ul>
<?php
$db->close();
?>
