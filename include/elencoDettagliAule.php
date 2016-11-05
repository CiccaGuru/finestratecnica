<?php
require_once 'funzioni.php'; // Includes Login Script
$utente = check_login();
if ($utente == -1) {
  header('Location: index.php');
} else {
  $user_level = get_user_level($utente);
  if ($user_level == 0) {
    header('Location: userhome.php');
  }
  if ($user_level == 1) {
    header('Location: docente.php');
  }

  $db = database_connect();

  if(isset($_POST["quanti"])){
    $quanti = $_POST["quanti"];
  }else{
    $quanti = 20;
  }

  if(isset($_POST["page"])){
    $page = $_POST["page"];
  }
  else{
    $page = 1;
  }
  $filtro = $_POST["filtro"];

  if(isset($_POST["filtro"]) && ($_POST["filtro"]!="")){
    $result   = $db->query("SELECT * FROM aule WHERE nomeAula LIKE '%$filtro%' ORDER BY nomeAula LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: ' . $db->error);
    $resultAA = $db->query("SELECT * FROM aule WHERE nomeAula LIKE '%$filtro%' ORDER BY nomeAula") or  die('ERRORE: ' . $db->error);
  }
  else{
    $result   = $db->query("SELECT * FROM aule ORDER BY nomeAula LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: R' . $db->error);
    $resultAA = $db->query("SELECT * FROM aule") or  die('ERRORE: ' . $db->error);
  }

  $numRisultato = $resultAA->num_rows;
?>
  <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO AULE</h4></li>
  <li class="collection-item center">
    <form id="filtraAule">
      <div class="row">
        <div class="col s2" style="font-size:120%; margin-top:0.5em">
          <p class="condensed accent-text">
            <i class="material-icons left">search</i>Cerca
          </p>
        </div>

        <div class="input-field col s4">
          <input id="filtro"type="text" class="validate" value="<?php echo $filtro;?>">
          <label for="filtro" class="condensed">Parola chiave</label>
        </div>
        <div class="col s2 offset-s1" style="margin-top:0.6em;">
          <p class="condensed">
            Risultati per pagina:
          </p>
        </div>
        <div class="input-field col s1">
          <input id="quanti" type="text" class="validate" value="<?php echo $quanti; ?>" required>
        </div>
        <div class="input-field col s1 right">
          <button type="submit" class="btn-floating btn-large waves-effect waves-light accent condensed white-text">
            <i class="material-icons">search</i>
          </button>
        </div>
      </div>
    </form>
  </li>
<?php
if($result->num_rows==0){
  ?>  <li class="collection-item">
        <div class="accent-text condensed center-align" style="font-size:150%; margin:1em;">Nessuna aula</div>
      </li>
    <?php
}
 while($row = $result->fetch_assoc())
  { ?>
    <li class="collection-item row valign-wrapper">
      <div class="col s1 accent-text">
          <i class="material-icons waves-effect waves-accent waves-circle" style="border-radius:50%;" onclick="eliminaAula(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')">close</i>
      </div>
      <div class="col s2 bold">
        ID: <?php echo $row["id"];?>
      </div>
      <div class="input-field col s3 valign">
        <input id="nomeAula<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nomeAula']; ?>" required>
          <label for="nomeAula<?php echo $row['id']; ?>">Nome aula</label>
      </div>
      <div class="input-field col s3 valign">
        <input id="maxStudenti<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['maxStudenti']; ?>" required>
        <label for="maxStudenti<?php echo $row['id']; ?>">N° studenti</label>
      </div>
      <div class="col s2 offset-s1 center valign">
        <a onclick="modficaAula(<?php echo $row['id'];?>)" class="waves-effect small-icon condensed waves-accent fill-width btn-flat valign accent-text">
            MODIFICA
        </a>
      </div>
    </li>
    <?php  }
    if($numRisultato>$quanti){
      ?>
      <li class="collection-item center">
        <ul class="pagination">
          <li <?php if($page == '1') echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliAule('.($page-1).')"'; ?>><a href="#!"><i class="material-icons">chevron_left</i></a></li>
          <?php
          $i=1;
          while($i*$quanti<=$numRisultato){
            ?>
            <li onclick="aggiornaDettagliAule(<?php echo $i;?>)" class="<?php if($i==$page) echo 'active'; else echo 'waves-effect waves-accent'?>"><a href="#!"><?php echo $i; ?></a></li>
            <?php
            $i++;
          }
          ?>
          <li <?php if($page*$quanti>=$numRisultato) echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliAule('.($page+1).')"'; ?>><a href="#!"><i class="material-icons">chevron_right</i></a></li>
        </ul>
      </li>
      <?php
    }
  ?>
<li class="collection-item row valign-wrapper">
  <?php
}
$db->close();
?>
