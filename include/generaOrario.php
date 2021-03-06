<?php
include dirname(__FILE__).'/funzioni.php';
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
  $numero_giorni = getProp("numero_giorni");
  $ore_per_giorno = getProp("ore_per_giorno");
  $coloriDB = unserialize(getProp("colori"));
  $colore_testo = unserialize(getProp("colore_testo"));
  $giorni = unserialize(getProp("giorni"));
  $soglia_minima = getProp("soglia_minima");
  $chiusura_iscrizioni = getProp("chiusura_iscrizioni");
  $admin = isset($_POST["admin"]);
  ?>

  <h4 class="light condensed letter-spacing-1 primary-text center" id="titoloOrario">IL TUO ORARIO</h3>

    <?php

    $result = $db->query("SELECT id from iscrizioni WHERE idUtente = '$utente' AND partecipa = '1' ") or die($db->error);
    if($result->num_rows<$soglia_minima){
        echo "<div class='center-align accent-text condensed' id='messaggioPocheOre'>ATTENZIONE! DEVI COPRIRE ALMENO $soglia_minima ORE!</div>";
    }

    ?>
    <table id="orario<?if($admin) echo '-admin';?>" class="centered">
      <thead>
        <th></th>
        <?php
        for($i =1; $i<=$numero_giorni; $i++){
          $giorno = str_replace("ì", "i'", $giorni[$i]);
          echo '<th class="condensed letter-spacing-1">'.strtoupper($giorno).'</th>';
        }
        ?>
      </thead>
      <tbody>
        <?php
        $colori = array();
        $r = 0;
        for($i = 1; $i<=$ore_per_giorno; $i++){
          echo '<tr><td class="condensed">'.$i."</td>";
          for($j=1; $j<=$numero_giorni;$j++){
            $num = ($j-1)*$ore_per_giorno+$i;
            $result = $db->query("SELECT  iscrizioni.idCorso as idCorso,
                                          iscrizioni.idLezione as idLezione,
                                          corsi.continuita as continuita,
                                          corsi.titolo as titolo,
                                          lezioni.idAula,
                                          aule.nomeAula as aula
                                  FROM    iscrizioni, corsi, lezioni, aule
                                  WHERE   iscrizioni.idUtente = '$utente'
                                          AND lezioni.ora = '$num'
                                          AND iscrizioni.partecipa = '1'
                                          AND corsi.id = iscrizioni.idCorso
                                          AND lezioni.id = iscrizioni.idLezione
                                          AND lezioni.idAula = aule.id")
                            or die('ERRORE: ' . $db->error);
              $resultContr = $db->query(" SELECT  iscrizioni.id
                                          FROM    iscrizioni, lezioni
                                          WHERE   iscrizioni.idUtente = '$utente'
                                                  AND lezioni.ora = '$num'
                                                  AND lezioni.id = iscrizioni.idLezione
                                                  AND iscrizioni.partecipa = '0'")
                            or die('ERRORE: ' . $db->error);
              if($result->num_rows ==1){
                while($iscrizione = $result->fetch_assoc()){
                  $nomeCorso = $iscrizione["titolo"];
                  $aula =  $iscrizione["aula"];
                  $result = $db->query("SELECT COUNT(*) as conta from iscrizioni where idUtente = $utente and partecipa = '1'");
                  $conta = $result->fetch_assoc();
                  if(!(in_array($iscrizione["idCorso"], $colori))){
                    $colori[$r]=$iscrizione["idCorso"];
                    $bgcolor = $coloriDB[$r];
                    $fgcolor = $colore_testo[$r];
                    $r++;
                  }
                  else{
                    $index = array_search($iscrizione["idCorso"], $colori);
                    $bgcolor = $coloriDB[$index];
                    $fgcolor = $colore_testo[$index];
                  }
                  if((time()>$chiusura_iscrizioni) && ($conta["conta"]>=$soglia_minima)){
                      echo '<td class="cellaOrario condensed" style="background-color: '.$bgcolor.'; color: '.$fgcolor.';" >'.strtoupper($nomeCorso).'<span>Aula '.$aula.'</span></td>';
                  }else{
                    if(($resultContr->num_rows > 0)&& ($iscrizione["continuita"]==0)){
                      echo '<td class="cellaOrario waves-mod waves-effect waves-light condensed pointerCursor underline" style="background-color: '.$bgcolor.'; color: '.$fgcolor.'; " onclick="scegliQuale('.$num.')" >'.strtoupper($nomeCorso).'<span>Aula '.strtoupper($aula).'</span></td>';
                    }
                    else{
                      echo '<td class="cellaOrario waves-mod waves-effect waves-light condensed pointerCursor" style="background-color: '.$bgcolor.'; color: '.$fgcolor.';" onclick="$(\'#collapsible'.$iscrizione["idCorso"].'\').animatedScroll({easing: \'easeOutQuad\'});">'.strtoupper($nomeCorso).'<span>Aula '.strtoupper($aula).'</span></td>';
                    }
                  }
                }
              }
              else if($result->num_rows == 0){
                echo "<td class='cellaBordo waves-mod waves-effect' onclick='scegliQuale(".$num.")' ></td>";
              }
              else{
                echo "<td class='cellaBordo waves-mod waves-effect accent-text condensed' style='font-weight:500;' >ERRORE</td>";
              }

            }
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
      <?php }?>
