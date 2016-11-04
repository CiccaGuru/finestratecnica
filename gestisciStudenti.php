<?php
require_once('include/funzioni.php'); // Includes Login Script
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

if(isset($_POST["username"]) && ($_POST["username"]!="")){
  $filtro = $_POST["username"];
  $result = $db->query("SELECT * FROM utenti WHERE (username LIKE '$filtro' or nome like  '$filtro' or cognome like '$filtro') and level = '0'  ORDER BY cognome,nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: A' . $db->error);
  $resultAA = $db->query("SELECT id FROM utenti WHERE (username LIKE '".$_POST["username"]."' or nome like  '".$_POST["username"]."' or cognome like '".$_POST["username"]."') and level = '0'") or  die('ERRORE: B' . $db->error);
}
else{
  $result = $db->query("SELECT * FROM utenti WHERE level='0' ORDER BY cognome, nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: SDR' . $db->error);
  $resultAA = $db->query("SELECT * FROM utenti WHERE level='0'") or  die('ERRORE: ' . $db->error);
}

$numRisultato = $resultAA->num_rows;

$giorni="";
$ore_elenco="";
foreach($_CONFIG['giorni'] as $num=>$nome){
  $giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
  $ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}
$db = database_connect();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Studenti</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
  <link rel="stylesheet" href="css/material-scrolltop.css">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
  <link rel="icon" type="image/png" href="/img/favicons/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="image/png" href="/img/favicons/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="/img/favicons/manifest.json">
  <link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#f44336">
  <link rel="shortcut icon" href="/img/favicons/favicon.ico">
  <meta name="msapplication-TileColor" content="#2d89ef">
  <meta name="msapplication-TileImage" content="/img/favicons/mstile-144x144.png">
  <meta name="msapplication-config" content="/img/favicons/browserconfig.xml">
  <meta name="theme-color" content="#03a9f4">
</head>

<body>
  <nav class="primary">
    <ul id="utenti-dropDown" class="dropdown-content">
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciStudenti.php">STUDENTI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciDocenti.php">DOCENTI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciCorsi.php">CORSI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciClassi.php">CLASSI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciAule.php">AULE</a></li>
    </ul>
    <div class="navbar-fixed">
      <nav id="intestaz" class="primary">
        <div class="nav-wrapper">
          <a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
          <a href="#" class="brand-logo center condensed light">Settimana tecnica</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li class="active"><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
            <li><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
            <li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
          </ul>
        </div>
      </nav>
    </div>
  </nav>

  <div class="container"  style="margin-top:3em;">
    <div class="card">
      <div class="card-content"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title primary-text center condensed">Cerca studente</span>
        <div class="row">
          <div class="input-field col s3">
            <input id="nomeStudenteCerca" type="text" class="validate" requiaccent>
            <label for="nomeStudenteCerca" class="condensed">Nome</label>
          </div>
          <div class="input-field col s3">
            <input id="cognomeStudenteCerca" type="text" class="validate" requiaccent>
            <label for="cognomeStudenteCerca" class="condensed">Cognome</label>
          </div>
          <div class="input-field col s3">
            <select id="selezionaGiornoCerca">
              <?php echo $giorni;?>
            </select>
            <label>Giorno</label>
          </div>
          <div class="col s3">
            <div class="input-field">
              <select id="selezionaOraCerca">
                <?php echo $ore_elenco;?>
              </select>
              <label class="condensed">Ora</label>
            </div>
          </div>
        </div>
        <div class="center">
          <a onclick="cercaStudente()" class="waves-effect waves-light btn-large accent condensed">
            <i class="material-icons left">search</i>
            Cerca
          </a>
        </div>

      </div>
    </div>
    <div class="card">
      <form id="aggiungi-studente">
        <div class="card-content"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
          <span class="card-title primary-text center condensed">Aggiungi un nuovo studente</span>
          <div class="row">
            <div class="input-field col s4">
              <input id="nomeStudente" type="text" class="validate" requiaccent>
              <label class="condensed" for="nomeStudente">Nome</label>
            </div>
            <div class="input-field col s4">
              <input id="cognomeStudente" type="text" class="validate" requiaccent>
              <label class="condensed" for="cognomeStudente">Cognome</label>
            </div>
            <div class="input-field col s4">
              <input id="usernameStudente" type="text" class="validate" requiaccent>
              <label class="condensed" for="usernameStudente">Nome utente</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s4">
              <input id="passwordStudente" type="password" class="validate" requiaccent>
              <label class="condensed" for="passwordStudente">Password</label>
            </div>
            <div class="input-field col s3">
              <input id="ripeti_passwordStudente" type="password" class="validate" requiaccent>
              <label class="condensed" for="ripeti_passwordStudente">Ripeti password</label>
            </div>
            <div class="input-field col s2">
              <select id="selezionaClasseStudente">
                <option value="" disabled selected>Scegli</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
              <label>Classe</label>
            </div>
            <div class="col s3 center">
              <button type="submit" class="waves-effect condensed waves-light btn-large accent">
                <i class="material-icons left">person_add</i>
                Aggiungi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container" style="width:90%">
    <ul class="collection with-header z-depth-1" id="dettagliStudenti">
      <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO STUDENTI</h4></li>
      <li class="collection-item center">
        <form  action="gestisciStudenti.php" method="POST">
          <div class="row">
            <div class="col s2" style="font-size:120%; margin-top:0.5em">
              <p class="condensed accent-text">
                <i class="material-icons left">search</i>Cerca
              </p>
            </div>

            <div class="input-field col s4">
              <input id="usernameSearch" name="username" type="text" class="validate" value="<?php
              if(isset($_POST["username"]) && ($_POST["username"]!="")){
                echo $_POST["username"];
              }
              ?>">
              <label for="usernameSearch" class="condensed">Parola chiave</label>
            </div>
            <div class="col s2 offset-s1" style="margin-top:0.6em;">
              <p class="condensed">
                Risultati per pagina:
              </p>
            </div>
            <div class="input-field col s1">
              <input id="min" name="quanti" type="text" class="validate" value="<?php echo $quanti?>" requiaccent>
            </div>
            <div class="input-field col s1 right">
              <button class="btn-floating btn-large waves-effect waves-light accent condensed white-text" type="submit" name="action">
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
          <div class="input-field col s1">
            <select id="classeStudente<?php echo $row['id'];?>">
              <option value="1" <?php if($row["classe"]=='1') echo "selected";?>>1</option>
              <option value="2" <?php if($row["classe"]=='2') echo "selected";?>>2</option>
              <option value="3" <?php if($row["classe"]=='3') echo "selected";?>>3</option>
              <option value="4" <?php if($row["classe"]=='4') echo "selected";?>>4</option>
              <option value="5" <?php if($row["classe"]=='5') echo "selected";?>>5</option>
            </select>
            <label>Classe</label>
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
        <li class="collection-item row center">
          <div class"center">
            <ul class="pagination center">
              <li <?php if($page=='1') echo 'class="disabled"'?>>
                <form action="gestisciStudenti.php" id="paginaIndietro"  method="post">
                  <input type="hidden" name="page" value="<?php echo ($page -1)?>">
                  <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                  <?php if(isset($_POST["username"])){?>
                    <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                    <?php }?>
                    <a onclick="$('#paginaIndietro').submit();">
                      <i class="material-icons">chevron_left</i>
                    </a>
                  </form>

                </li>

                <?php
                while($i*$quanti<=$numRisultato){
                  ?>
                  <form action="gestisciStudenti.php" id="pagina<?php echo $i; ?>" method="post" style="display:inline;">
                    <li class="waves-effect waves-accent <?php if($i==$page) echo "active"?>">
                      <input type="hidden" name="page" value="<?php echo $i?>">
                      <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                      <?php if(isset($_POST["username"])){?>
                        <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                        <?php }?>
                        <a onclick="$('#pagina<?php echo $i?>').submit();">
                          <?php echo $i ?>
                          <a/>
                        </li>
                      </form>
                      <?php
                      $i++;
                    }
                    ?>

                    <li <?php if($page==($i-1)) echo 'class="disabled"'?>class="waves-effect">
                      <form action="gestisciStudenti.php" id="paginaAvanti" method="post">
                        <input type="hidden" name="page" value="<?php echo ($page +1)?>">
                        <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                        <?php if(isset($_POST["username"])){?>
                          <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                          <?php }?>
                          <a onclick="$('#paginaAvanti').submit();">
                            <i class="material-icons">chevron_right</i>
                          </a>
                        </form>
                      </li>
                    </ul>
                  </div>
                </li>
                <?php
              }
              ?>
            </ul>

          </div>

          <div id="modal-orario" class="modal bottom-sheet" style="marign-top:5em;">
            <div class="modal-content">
              <h1 class="primary-text thin center" style="margin-bottom:0.3em;">Orario</h1>
            </div>
          </div>
          <div id="modal-continuita" class="modal">
            <div class="modal-content">

            </div>
            <div class="modal-footer">
              <a href="#!" class=" modal-action modal-close waves-effect waves-accent accent-text btn-flat">CHIUDI</a>
            </div>
          </div>


          <script src="js/jquery-2.1.4.min.js"></script>
          <script src="js/materialize.js"></script>
          <script src="js/init.js"></script>
          <script src="js/gestisciUtenti.js"></script>
        </body>
        </html>
        <?php

        $db->close();
        ?>
