<?php
include 'funzioni.php';
global $_CONFIG;
$utente = check_login();
if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
    die("LOGINPROBLEM");
  if($user_level == 2){
    $utente = $_POST["id"];
  }

$db = database_connect();

?>

<h4 class="thin light-blue-text center" style="margin-top:0px;">Il tuo orario</h3>

<?php
  $result = $db->query("SELECT id from iscrizioni WHERE idUtente = '$utente' AND partecipa = '1' ") or die($db->error);
  if($result->num_rows<$_CONFIG["soglia_minima"]){
    ?>
      <div class="center-align red-text" style="font-size:120%;">Attenzione! Devi coprire almeno 17 ore!</div>
    <?php
    }

 ?>
 <table id="orario" class="centered bordered">
   <thead>
     <th></th>
     <?php
     for($i =1; $i<=$_CONFIG["numero_giorni"]; $i++){
       echo '<th>'.$_CONFIG["giorni"][$i].'</th>';
     }
   ?>
 </thead>
 <tbody>
   <?php
     $colori = array();
     $r = 0;
     for($i = 1; $i<=$_CONFIG["ore_per_giorno"]; $i++){
       echo '<tr><td>'.$i."</td>";
       for($j=1; $j<=$_CONFIG["numero_giorni"];$j++){
         $num = ($j-1)*$_CONFIG["ore_per_giorno"]+$i;
         $result = $db->query("SELECT  iscrizioni.idCorso as idCorso,
                                       iscrizioni.idLezione as idLezione,
                                       iscrizioni.continuita as continuita,
                                       corsi.titolo as titolo,
                                       lezioni.aula as aula
                               FROM    iscrizioni, corsi, lezioni
                               WHERE   iscrizioni.idUtente = '".$utente."'
                                       AND iscrizioni.ora = '".$num."'
                                       AND iscrizioni.partecipa = '1'
                                       AND corsi.id = iscrizioni.idCorso
                                       AND lezioni.id = iscrizioni.idLezione") or die('ERRORE: ' . $db->error);
         $resultContr = $db->query("SELECT id FROM iscrizioni WHERE ((idUtente = '".$utente."') AND (ora = '".$num."') AND (partecipa = '0'))") or die('ERRORE: ' . $db->error);
         if($result->num_rows ==1){
           while($iscrizione = $result->fetch_assoc()){
             $classiAgg = "";
             $nomeCorso = $iscrizione["titolo"];
             $aula =  $iscrizione["aula"];
             $result = $db->query("SELECT COUNT(*) as conta from iscrizioni where idUtente = $utente and partecipa = '1'");
             $conta = $result->fetch_assoc();
             if((time()<145246680) || ($conta["conta"]<17)){
             if($resultContr->num_rows > 0){
               $classiAgg = "class='underline'";
              }
              }
             if(!(in_array($iscrizione["idCorso"], $colori))){
               $colori[$r]=$iscrizione["idCorso"];
               $bgcolor = $_CONFIG["colori"][$r];
               $fgcolor = $_CONFIG["colore-testo"][$r];
               $r++;
             }
             else{
                 $index = array_search($iscrizione["idCorso"], $colori);
                 $bgcolor = $_CONFIG["colori"][$index];
                 $fgcolor = $_CONFIG["colore-testo"][$index];
             }
             if(($resultContr->num_rows > 0)&& ($iscrizione["continuita"]==0)){
               $classiAgg = "class='underline'";
             }

              if((time()>145246680) && ($conta["conta"]>=17)){
                if(($resultContr->num_rows > 0)&& ($iscrizione["continuita"]==0)){
                  echo '<td style=" font-size:85%; background-color: '.$bgcolor.'; color: '.$fgcolor.'; padding:5px 0px;" >'.$nomeCorso.'<span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';
                }
                else{
                  echo '<td style=" font-size:85%; background-color: '.$bgcolor.'; color: '.$fgcolor.'; padding:5px 0px;" >'.$nomeCorso.'<span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';
                }
              }else{


             if(($resultContr->num_rows > 0)&& ($iscrizione["continuita"]==0)){
               echo '<td style=" font-size:85%; cursor: pointer; background-color: '.$bgcolor.'; color: '.$fgcolor.'; padding:5px 0px;" class="underline" onclick="scegliQuale('.$num.')" >'.$nomeCorso.'<span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';
             }
             else{
               echo '<td style=" font-size:85%; cursor:pointer; background-color: '.$bgcolor.'; color: '.$fgcolor.'; padding:5px 0px;" onclick="$(\'#collapsible'.$iscrizione["idCorso"].'\').animatedScroll({easing: \'easeOutQuad\'});">'.$nomeCorso.'<span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';
             }
           }
           }
         }
         else{
           echo "<td></td>";
         }

       }
       echo "</tr>";
     }
     ?>
   </tbody>
 </table>
 <?php }?>
