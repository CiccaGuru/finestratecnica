<?php
include 'funzioni.php';
include 'config.php';
global $_CONFIG;



$utente = check_login();
$idCorso = $_POST["idCorso"];
$db = database_connect();
if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die('LOGINPROBLEM');

$result = $db->query("SELECT classe FROM utenti where id = '$utente'");
$dettagliUtente = $result->fetch_assoc();
$result = $db->query("SELECT  corsi.titolo AS titolo,
                              corsi.descrizione AS descrizione,
                              corsi.iddocente AS iddocente,
                              corsi.id as id,
                              corsi.continuita as continuita,
                              utenti.nome as nome,
                              utenti.cognome as cognome
                      FROM    corsi, utenti
                      WHERE   (utenti.id = corsi.iddocente) AND
                              corsi.id = '$idCorso'") or die($db->error);
$dettagliCorso=$result->fetch_assoc();
$continuita = $dettagliCorso["continuita"];
$ore_utente = array();
$result = $db->query('SELECT idLezione from iscrizioni where idUtente='.$utente) or  die('ERRORE: 0 ' . $db->error);
while($ora_el = $result->fetch_assoc()){
  $ore_utente[]=$ora_el["idLezione"];
}
$classiElenco = "";
$result = $db->query("SELECT classe from corsi_classi where id_corso = '".$idCorso."' ");
while($classe = $result->fetch_assoc()){
  if($classe["classe"]==$dettagliUtente["classe"]){
    $classiElenco .= "<b class='underline'>".$classe["classe"]."</b>";
  }
  else{
    $classiElenco .= $classe["classe"]." ";
  }
}
?>


<div class="cont-dett">
  <div class="row">
    <div class="col m3 s12 bold">Titolo:</div>
    <div class="col m9 s11 offset-s1"><?php echo $dettagliCorso["titolo"]?></div>
  </div>
  <div class="row">
    <div class="col m3 s12 bold">Prof:</div>
    <div class="col m9 s11 offset-s1">
      <?php
        if($dettagliCorso["iddocente"] == '0'){
          echo "<span class='italic'>Docenti vari</span>";
        }
        else {
          echo $dettagliCorso["nome"][0].'. '.$dettagliCorso["cognome"];
        }
    ?>
  </div>
  </div>
  <div class="row">
    <div class="col m3 s12 bold">Descrizione:</div>
    <div class="col m9 s11 offset-s1"><?php echo $dettagliCorso["descrizione"]?></div>
  </div>
  <div class="row">
    <div class="col m3 s12 bold">Classi:</div>
    <div class="col m9 s11 offset-s1"><?php echo $classiElenco;?></div>
  </div>
  <div id="lezioni_dettagli">
    <div class="bold">Dettaglio lezioni:</div>
    <?php
    $ore=array();
    $ore_result = $db->query("SELECT id, aula, ora, maxiscritti, titolo from lezioni where idcorso='".$idCorso."' ORDER BY ora") or die('ERRORE: 4 ' . $db->error);
    $cont=1;
    while($ora=$ore_result->fetch_assoc()){
      $Tiscritti = troppiIscritti($ora["id"], $db);
      $aule_utente[$ora["ora"]] = $ora["aula"];
      $ore[$ora["ora"]]=$ora["id"];    ?>
      <div class="row">
        <div class="col m3 s12 bold">
          Lezione <?php echo $cont;?>
        </div>
        <div class="col m4 s11 offset-s1">
          <?php if($ora["titolo"] == "Nessuna descrizion")
                  echo "<span class='italic'>Nessuna descrizione</span>";
                  else echo $ora["titolo"];?>
        </div>
        <div class="col m1 s4 offset-s1 <?php if($Tiscritti) echo "red-text"; else echo "teal-text";?> ">
          <?php echo num_iscritti($ora["id"], $db).'\\'.$ora["maxiscritti"];?>
        </div>
        <div class="col m2 s4">
          Aula <?php echo $ora["aula"];?>
        </div><?php if($continuita=='0'){?>
          <div class="col s2">
            <?php
            if(!in_array($ora["id"], $ore_utente) && $Tiscritti){
              ?>
              <i class="red-text material-icons">clear</i>
              <?php
            }else{
              ?>
              <div class="switch">
                <input type="checkbox" class="iscriviOra" data-id-ora="<?php echo $ora["id"];?>" data-id-corso="<?php echo $idCorso?>" id="check<?php echo $ora["id"]?>"
                <?php
                if(in_array($ora["id"], $ore_utente)){

                  echo 'checked="checked"';
                }
                ?>
                />
                <label for="check<?php echo $ora["id"]?>"> </label>
              </div><?php }?>
            </div><?php } ?>
          </div>
          <?php
          $cont++;
        }
        ?>
      </div>
      <br/>
      <div>
        <table class="centered">
          <thead>
            <th></th>
            <?php
            for($i =1; $i<=$_CONFIG["numero_giorni"]; $i++){
              echo '<th>'.$_CONFIG["giorni"][$i].'</th>';
            }
            echo '</thead> <tbody>';
            for($i = 1; $i<=$_CONFIG["ore_per_giorno"]; $i++){
              echo "<tr><td>".$i."</td>";
              for($j=1; $j<=$_CONFIG["numero_giorni"];$j++){
                $num = ($j-1)*$_CONFIG["ore_per_giorno"]+$i;
                if($ore[$num]){
                  if(iscritto($ore[$num], $utente)){
                    $colore = 'light-blue lighten-2';
                  }
                  else if(troppiIscritti($ore[$num], $db)){
                    $colore = "red";
                  }else{
                    $colore = "grey";
                  }
                  echo  '<td class="'.$colore.'">'.$dettagliCorso["titolo"].'<span class="aulaDettagli">Aula '.$aule_utente[$num].'</span></td>';
                }else {
                  echo '<td></td>';
                }
              }
              echo '</tr>';
            }  ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
$db->close();
}
?>
