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
  if($user_level == 0)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die('LOGINPROBLEM');


$result = $db->query("SELECT titolo, descrizione, tipo, continuita from corsi where id = '$idCorso'") or die($db->error);
$dettagliCorso = $result->fetch_assoc();
?>
<div id="dett-doc">
<div class="row left-align">
  <div class="col s3 bold condensed">
    TITOLO:
  </div>
  <div class="col s9">
    <?php echo $dettagliCorso["titolo"];?>
  </div>
</div>
<div class="row left-align">
  <div class="col s3 bold condensed">
    DESCRIZIONE:
  </div>
  <div class="col s9">
    <?php echo $dettagliCorso["descrizione"];?>
  </div>
</div>
<div class="row left-align">
  <div class="col s4 m3 condensed bold">
    CLASSI:
  </div>
  <div class="col s7 m2">
    <?php
      $resultClassi = $db->query("SELECT classe from corsi_classi where idCorso = '$idCorso'");
      $classi = array();
      while($classe = $resultClassi->fetch_assoc()){
        $classi[] = $classe["classe"]."^";
      }
      echo join(" - ", $classi);
    ?>
  </div>
  <div class="col s12 m7 condensed">
    Corso di <span class="bold">
    <?php if($dettagliCorso["tipo"]==0) echo "RECUPERO "; else echo "APPROFONDIMENTO ";
        if($dettagliCorso["continuita"]==0) echo "SENZA CONTINUITA'"; else echo "CON CONTINUITA'"; ?>
      </span>
  </div>
</div>
<div class="deep-orange-text condensed left-align" style="margin-top:1em; font-size:105%;">
  <?php
    $resultObbl = $db->query("SELECT corsi_obbligatori.idClasse, sezioni.classe, sezioni.sezione
                                        from corsi_obbligatori, sezioni
                                        where corsi_obbligatori.idClasse = sezioni.id AND
                                              corsi_obbligatori.idCorso = '$idCorso'") or die($db->error);
     if($resultObbl->num_rows == 0){
      ?>
        <span>E' un corso non obbligatorio per tutte le classi.</span>
      <?php
    } else {
        if($resultObbl->num_rows == 1){
      ?> <span>Corso obbligatorio per la classe <?php
    }
    else if($resultObbl->num_rows > 1){
      ?> <span>Corso obbligatorio per le classi <?php
    }
    $classi = array();
      while($classe=$resultObbl->fetch_assoc()){
        $classi[] = "<span class='bold'>".$classe["classe"]."^".$classe["sezione"];
      }
      echo join(", ", $classi);
    }
  ?>
</div>

<div class="deep-orange-text condensed left-align" style="margin-top:0.5em; font-size:105%;">
  <?php
    $resultIncomp = $db->query("SELECT  corsi_incompatibili.idCorso2 as idCorso,
                                      corsi.titolo as titolo,
                                      corsi.id,
                                      corsi_docenti.idDocente as idDocente,
                                      utenti.nome,
                                      utenti.cognome
                                FROM corsi_incompatibili, corsi, corsi_docenti, utenti
                                WHERE corsi_incompatibili.idCorso2 = corsi.id AND
                                      corsi_incompatibili.idCorso1 = '$idCorso' AND
                                      corsi_docenti.idCorso = corsi.id AND
                                      utenti.id = corsi_docenti.idDocente ") or die($db->error);
     if($resultIncomp->num_rows == 0){
      ?>
        <span>Corso compatibile con tutti i corsi.</span>
      <?php
    } else {
        if($resultIncomp->num_rows == 1){
      ?> <span>Corso incompatibile con il corso <?php
    }
    else if($resultIncomp->num_rows > 1){
      ?> <span>Corso incompatibile con i corsi <?php
    }
    $corsiIncomp = array();
      while($corsoIncomp=$resultIncomp->fetch_assoc()){
        $stringa = "<span class='bold'>".$corsoIncomp["titolo"]."</span>";
        if($corsoIncomp["idDocente"] == $utente){
          $stringa .= " (suo corso)";
        }
        else{
          $stringa .= " (di ".$corsoIncomp["nome"][0].". ".$corsoIncomp["cognome"].")";
        }
        $corsiIncomp[] = $stringa;
      }
      echo join(", ", $corsiIncomp);
    }
  ?>
</div>
<div class="bold condensed left-align" style="margin-top:1em;font-size:120%;">DETTAGLI LEZIONI</div>
<?php
$result = $db->query("SELECT  lezioni.titolo,
                              lezioni.ora,
                              aule.maxStudenti as maxIscritti,
                              lezioni.id
                      FROM    lezioni, aule
                      WHERE   lezioni.idCorso = '$idCorso' AND
                              lezioni.idAula = aule.id
                      ORDER BY ora ")
              or die($db->error);

while($lezione = $result->fetch_assoc()){
  ?>
  <div class="row left-align">
      <div class="col valign-wrapper s12 m3 bold">
        <?php echo getStringaOra($lezione["ora"]);?>
      </div>
      <div class="col valign-wrapper m4 s8 offset-s1">
        <?php if($lezione["titolo"]=="") echo "<span class='italic'>Nessuna descrizione</span>";
                else echo $lezione["titolo"]?>
      </div>
        <div class="col valign-wrapper m2 s3 <?php if(troppiIscritti($lezione["id"], $db)) echo "accent-text"; else echo "teal-text";?> ">
          <?php echo (num_iscritti($lezione["id"], $db)).'\\'.$lezione["maxIscritti"];?>
        </div>
        <div class="col valign s12 m3">
          <a class="btn-flat accent-text center-align valign waves-effect waves-accent" onclick="getElenco(<?php echo $lezione["id"];?>)">Elenco studenti</a>
        </div>
    </div>
      <?php
}
}

?>
</div>
