<?php
include 'funzioni.php';
$db = database_connect();
$level = $_POST["level"];

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
  $result = $db->query("SELECT * FROM utenti WHERE (username LIKE '%$filtro%' or nome like  '%$filtro%' or cognome like '%$filtro%') and level = $level  ORDER BY cognome,nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: ' . $db->error);
  $resultAA = $db->query("SELECT id FROM utenti WHERE (username LIKE '%$filtro%' or nome like  '%$filtro%' or cognome like '%$filtro%') and level = $level") or  die('ERRORE: ' . $db->error);
}
else{
  $result = $db->query("SELECT * FROM utenti WHERE level=$level ORDER BY cognome, nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: R' . $db->error);
  $resultAA = $db->query("SELECT * FROM utenti WHERE level=$level") or  die('ERRORE: ' . $db->error);
}

$numRisultato = $resultAA->num_rows;

if($level == 1){
  ?>
  <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO DOCENTI</h4></li>
  <li class="collection-item center">
    <form id="filtraDocenti">
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
      <div class="accent-text condensed center-align" style="font-size:150%; margin:1em;">Nessun risultato trovato</div>
    </li>
    <?php
  }

  while($row = $result->fetch_assoc())
  { ?>
    <li class="collection-item row valign-wrapper">
      <div class="col s1 accent-text">
        <i class="material-icons waves-effect waves-accent waves-circle" style="border-radius:50%;" onclick="eliminaUtente(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["filtro"]?>', 1)">close</i>
      </div>
      <div class="col s1 bold">
        ID: <?php echo $row["id"];?>
      </div>
      <div class="input-field col s2 valign">
        <input id="nome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
        <label for="nome<?php echo $row['id']; ?>">Nome</label>
      </div>
      <div class="input-field col s2 valign">
        <input id="cognome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
        <label for="cognome<?php echo $row['id']; ?>">Cognome</label>
      </div>
      <div class="input-field col s2 valign">
        <input id="username<?php echo $row['id']; ?>" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
        <label for="username<?php echo $row['id']; ?>">Username</label>
      </div>
      <div class="col s2 cente valign">
        <p><a onclick="modificaDocente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["filtro"]?>')" class="waves-effect center-align waves-accent btn-flat accent-text valign" style="width:98%">Modifica</a></p>
        <p><a class="waves-effect waves-accent center-align btn-flat accent-text valign" onclick="eliminaDocente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["filtro"]?>')" style="width:98%">Elimina</a></p>
      </div>
      <div class="col s2 center valign">
        <a onclick="passwordReset(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["filtro"]?>')" class="waves-effect small-icon condensed waves-accent fill-width fake-button valign accent-text">
          <i class="material-icons ">refresh</i> <br/>RESET
        </a>
      </div>
    </li>
    <?php  }
    if($numRisultato>$quanti){
      ?>
      <li class="collection-item center">
        <ul class="pagination">
          <li <?php if($page == '1') echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliUtenti(1,'.($page-1).')"'; ?>><a href="#!"><i class="material-icons">chevron_left</i></a></li>
          <?php
          $i=1;
          while($i*$quanti<=$numRisultato){
            ?>
            <li onclick="aggiornaDettagliUtenti(1, <?php echo $i;?>)" class="<?php if($i==$page) echo 'active'; else echo 'waves-effect waves-accent'?>"><a href="#!"><?php echo $i; ?></a></li>
            <?php
            $i++;
          }
          ?>
          <li <?php if($page*$quanti>=$numRisultato) echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliUtenti(1,'.($page+1).')"'; ?>><a href="#!"><i class="material-icons">chevron_right</i></a></li>
        </ul>
      </li>
      <?php
    }
    ?>
    <?php
  }














  else{
    ?>
    <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO STUDENTI</h4></li>
    <li class="collection-item center">
      <form id="filtraStudenti">
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
        <div class="accent-text condensed center-align" style="font-size:150%; margin:1em;">Nessun risultato trovato</div>
      </li>
      <?php
    }


    while($row = $result->fetch_assoc())
    {
      ?>

      <li class="collection-item row valign-wrapper">
        <div class="col s1 accent-text">
          <i class="material-icons waves-effect waves-accent waves-circle" style="border-radius:50%;" onclick="eliminaUtente(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>', 0)">close</i>
        </div>
        <div class="col s1 bold">
          ID: <?php echo $row["id"];?>
        </div>
        <div class="input-field col s2 valign">
          <input id="nome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" requiaccent>
          <label for="nome<?php echo $row['id']; ?>Studente">Nome</label>
        </div>
        <div class="input-field col s2 valign">
          <input id="cognome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" requiaccent>
          <label for="nome<?php echo $row['id']; ?>Studente">Cognome</label>
        </div>
        <div class="input-field col s2 valign">
          <input id="username<?php echo $row['id']; ?>Studente" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" requiaccent>
          <label for="nome<?php echo $row['id']; ?>Studente">Username</label>
        </div>
        <div class="input-field col s1 valign">
          <a class="valgin waves-accent waves-effect btn-flat condensed" onclick="mostraModalScegliClasse(<?php echo $row["id"];?>)">CLASSE</a>
        </div>
        <div class="col s2 cente valign">
          <p style="margin-bottom:5px;">
            <a onclick="modificaStudente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect center-align waves-accent btn-flat accent-text valign" style="width:98%">
              Modifica
            </a>
          </p>
          <p style="margin-top:5px;">
            <a class="waves-effect waves-accent center-align btn-flat accent-text valign" onclick="mostraOrarioStudente(<?php echo $row['id'];?>)" style="width:98%;">Orario</a>
          </p>
        </div>
        <div class="col s1 center valign" style="padding:0px;">
          <a class="waves-effect small-icon condensed waves-accent fill-width fake-button valign accent-text" onclick="passwordReset(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" >
            <i class="material-icons ">refresh</i> <br/>RESET
          </a>
        </div>
      </li>
      <?php
    }

    if($numRisultato>$quanti){
      ?>
      <li class="collection-item center">
        <ul class="pagination">
          <li <?php if($page == '1') echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliUtenti(0,'.($page-1).')"'; ?>><a href="#!"><i class="material-icons">chevron_left</i></a></li>
          <?php
          $i=1;
          while($i*$quanti<=$numRisultato){
            ?>
            <li onclick="aggiornaDettagliUtenti(0, <?php echo $i;?>)" class="<?php if($i==$page) echo 'active'; else echo 'waves-effect waves-accent'?>"><a href="#!"><?php echo $i; ?></a></li>
            <?php
            $i++;
          }
          ?>
          <li <?php if($page*$quanti>=$numRisultato) echo 'class="disabled"'; else echo 'class="waves-effect"  onclick="aggiornaDettagliUtenti(0,'.($page+1).')"'; ?>><a href="#!"><i class="material-icons">chevron_right</i></a></li>
        </ul>
      </li>
      <?php
    }
          }
          ?>
