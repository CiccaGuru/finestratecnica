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


$result = $db->query("SELECT titolo, descrizione from corsi where id = '$idCorso'") or die($db->error);
$dettagliCorso = $result->fetch_assoc();
?>
<div class="row left-align">
  <div class="col s5 bold">
    Titolo corso:
  </div>
  <div class="col s7">
    <?php echo $dettagliCorso["titolo"];?>
  </div>
</div>
<div class="row left-align">
  <div class="col s5 bold">
    Descrizione corso:
  </div>
  <div class="col s7">
    <?php echo $dettagliCorso["descrizione"];?>
  </div>
</div>
<?php
$result = $db->query("SELECT  lezioni.titolo,
                              lezioni.ora,
                              lezioni.maxIscritti,
                              lezioni.id
                      FROM    lezioni
                      WHERE   lezioni.idCorso = '$idCorso'
                      ORDER BY ora ")
              or die($db->error);

while($lezione = $result->fetch_assoc()){
  ?>
  <div class="row left-align">
    <div class="valign-wrapper">
      <div class="col valign-wrapper s12 m3 bold">
        <?php echo getStringaOra($lezione["ora"]);?>
      </div>
      <div class="col valign-wrapper m4 s8 offset-s1">
        <?php echo $lezione["titolo"]?>
      </div>
        <div class="col valign-wrapper m2 s3 <?php if(troppiIscritti($lezione["id"], $db)) echo "red-text"; else echo "teal-text";?> ">
          <?php echo (num_iscritti($lezione["id"], $db)).'\\'.$lezione["maxIscritti"];?>
        </div>
        <div class="col valign s12 m3">
          <a class="btn red valign white-text waves-effect waves-light" onclick="getElenco(<?php echo $lezione["id"];?>)">Elenco studenti</a>
        </div>
      </div>
    </div>
      <?php
}
}

?>
