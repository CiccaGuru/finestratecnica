<?php
require_once('include/funzioni.php'); // Includes Login Script
include 'include/config.php';
global $_CONFIG;

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

if(isset($_POST["min"])){
  $min = $_POST["min"];
}
else{
  $min = 0;
}

if(isset($_POST["max"])){
  $max = $_POST["max"];
}
else{
  $max = 30;
}
$condizione = "";
if(isset($_POST["action"])){
  if(isset($_POST["concontinuita"]) and !(isset($_POST["senzacontinuita"]))){
      $condizione .= " corsi.continuita = '1' AND ";
  }
  if(isset($_POST["senzacontinuita"]) and !(isset($_POST["concontinuita"]))){
      $condizione .= " corsi.continuita = '0' AND ";
  }
  if(isset($_POST["recupero"]) and !(isset($_POST["approfondimento"]))){
      $condizione .= " corsi.tipo = '0' AND ";
  }
  if(isset($_POST["approfondimento"]) and !(isset($_POST["recupero"]))){
      $condizione .= " corsi.tipo = '1' AND ";
  }
  if(!($_POST["professore"]=="")){
    $professore = str_replace("à", "&agrave", $_POST["professore"]);
    $professore = str_replace("è", "&egrave", $professore);
    $professore = str_replace("ì", "&igrave", $professore);
    $professore = str_replace("ò", "&ograve", $professore);
    $professore = str_replace("é", "&eacuto", $professore);
    $condizione .= "(utenti.nome LIKE '$professore' OR utenti.cognome LIKE '$professore') AND ";
  }
  if(!($_POST["titolo"]=="")){
    $titolo = str_replace("à", "&agrave", $_POST["titolo"]);
    $titolo = str_replace("è", "&egrave", $titolo);
    $titolo = str_replace("ì", "&igrave", $titolo);
    $titolo = str_replace("ò", "&ograve", $titolo);
    $titolo = str_replace("é", "&eacuto", $titolo);
    $condizione .= "(corsi.titolo LIKE '%$titolo%' OR corsi.descrizione LIKE '%$titolo%')";
  }
  else{
    $condizione .= "1";
  }
}

if($condizione==""){
  $condizione = "1";
}else{

  $condizione.=")";
}
$giorni="";
$ore_elenco="";
foreach($_CONFIG['giorni'] as $num=>$nome){
		$giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
	$ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}
$db = database_connect();

$result = $db->query("SELECT  corsi.titolo as titolo,
                              corsi.descrizione as descrizione,
                              corsi.continuita as continuita,
                              corsi.tipo as tipo,
                              utenti.nome as nome,
                              utenti.cognome as cognome,
                              utenti.id as idDocente,
                              corsi.id as id
                      FROM corsi,utenti
                      WHERE (utenti.id = corsi.iddocente) AND ($condizione)
                      ORDER BY corsi.titolo
                      LIMIT $min, ".($max-$min)) or die("AA: ".$db->error);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Corsi</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
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

  <nav class="light-blue">
    <ul id="utenti-dropDown" class="dropdown-content">
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciStudenti.php">STUDENTI</a></li>
  	<li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciDocenti.php">DOCENTI</a></li>
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciCorsi.php">CORSI</a></li>
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciClassi.php">CLASSI</a></li>
  </ul>
  	<div class="navbar-fixed">
  		<nav id="intestaz" class="light-blue">
  			<div class="nav-wrapper">
  				<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
  				<a href="#" class="brand-logo center light">Settimana tecnica</a>
  				<ul id="nav-mobile" class="right hide-on-med-and-down">
  					<li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
  					<li class="active"><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
  					<li><a href="logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
  				</ul>
  			</div>
  		</nav>
  	</div>
	</nav>

  <div class="container" id="ins-corso" style="margin-top:5em;">
    <div class="card">
      <div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title"><h4 class="blue-text condensed light center-align">Nuovo corso</h4></span>
        <div class="row" style="margin-top:1em">
          <div class="input-field col s3 condensed">
            <input id="titolo" type="text" class="validate">
            <label for="titolo">Titolo</label>
          </div>
          <div class="input-field col s5  condensed">
            <input id="descriz" type="text" class="validate">
            <label for="descriz">Descrizione</label>
          </div>
          <div class="input-field col s4  condensed">
            <select id="selezionaDocenti">
              <option value="" disabled selected class="grey-text">Seleziona insegnante</option>
            </select>
            <label>Scegli l'insegnante</label>
          </div>
        </div>
        <div class="row valign-wrapper">
          <div class="col s3  condensed">
            <p>
              <input name="tipo" value="0" type="radio" id="recupero" checked/>
              <label class="black-text" for="recupero">Recupero</label>
            </p>
            <p>
              <input name="tipo" value="1" type="radio" id="approfondimento" />
              <label class="black-text" for="approfondimento">Approfondimento</label>
            </p>
          </div>
          <div class="col s3  condensed">
            <p>
              <input name="continuita" value="1" type="radio" id="con_continuita" checked/>
              <label class="black-text" for="con_continuita">Con continuità</label>
            </p>
            <p>
              <input name="continuita" value="0" type="radio" id="senza_continuita" />
              <label class="black-text" for="senza_continuita">Senza continuità</label>
            </p>
          </div>
          <div class="input-field col s2 condensed">
            <select id="selezionaClassi" multiple>
              <option value="" disabled selected>Scegli classi</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            <label>Classi</label>
          </div>
          <div class="input-field col s2 valign condensed">
            <input id="ore" type="text" class="validate">
            <label for="ore">N° Ore</label>
          </div>
          <div class="col s2 center">
            <a id="inserisciOre" class="waves-effect condensed btn waves-light red">Procedi</a>
          </div>
        </div>
        <div id="ore_future">
        </div>
        <div class="center">
          <a onclick="aggiungiCorso()" id="aggiungiCorso" class="waves-effect waves-light btn-large condensed red disabled">  <i class="material-icons left">add_alert</i>CREA CORSO</a>
        </div>
      </div>
</div>
</div>
<div class="container" style="margin-top:3em; width:97%">
      <ul class="collection with-header z-depth-1" id="dettagliStudenti">
          <li class="collection-header blue-text center"><h4 class="light condensed">ELENCO CORSI</h4></li>
          <li class="collection-item">
            <form  action="gestisciCorsi.php" method="POST">
              <div class="row condensed">
                <div class="col s2" style="font-size:120%; margin-top:0.8em">
                  <p class="condensed red-text">
                    <i class="material-icons left">search</i>Cerca
                  </p>
                </div>
              <div class="col s2 valign">
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["concontinuita"])) || (!(isset($_POST["concontinuita"])) && !(isset($_POST["senzacontinuita"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" class="filled-in" name="concontinuita" id="concontinuita" />
                        <label for="concontinuita">Con continuità</label>
                    </p>
                  </div>
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input  <?php
                          if((isset($_POST["senzacontinuita"])) || (!(isset($_POST["concontinuita"])) && !(isset($_POST["senzacontinuita"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" class="filled-in" name="senzacontinuita" id="senzacontinuita" />
                        <label for="senzacontinuita">Senza continuità</label>
                    </p>
                  </div>
                </div>

                <div class="col s2 valign">
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["recupero"])) || (!(isset($_POST["approfondimento"])) && !(isset($_POST["recupero"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" class="filled-in" name="recupero" id="recuperoCerca" />
                        <label for="recuperoCerca">Recupero</label>
                    </p>
                  </div>
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["approfondimento"])) || (!(isset($_POST["approfondimento"])) && !(isset($_POST["recupero"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" class="filled-in" name="approfondimento" id="approfondimentoCerca" />
                        <label for="approfondimentoCerca">Approfondimento</label>
                    </p>
                  </div>
                </div>

                <div class="input-field col s3 valign">
                  <input id="parolaChiave" name="titolo" type="text" value="<?php echo $_POST["titolo"]?>" class="validate valign">
                  <label for="parolaChiave">Parola chiave</label>
                </div>
                <div class="col s1 right-align" style="margin-top:0.6em;">
                  <p class="right-align">
                    Mostra
                  </p>
                </div>

                <div class="input-field col s1 valign">
                  <input id="min" name="min" type="text" class="validate valign" value="<?php echo $min?>" required>

                </div>
                <div class="col s1">
                  <div class="input-field">
                    <button class="btn-floating btn-large center waves-effect waves-light red condensed white-text" type="submit" name="action">
                      <i class="material-icons">search</i>
                    </button>
                  </div>
                </div>

            </div>
              </form>
          </li>
          <?php
            while($dettagli = $result->fetch_assoc()){
          ?>
          <li class="collection-item">
            <div class="row valign-wrapper" style="margin-bottom:0px;">
              <div class="col s1 valign bold">
                <i class="material-icons waves-effect waves-red red-text waves-circle" style="border-radius:50%; display:inline; margin:0em;">close</i>
                <span class="condensed" style="margin-left:0.5em;">ID: <?php echo $dettagli["id"];?></span>
              </div>
              <div class="col s2 valign center-align bold condensed capitalize" style="font-size:105%";>
                <?php echo $dettagli["titolo"];?>
              </div>
              <div class="col s3 valign">
                <?php echo $dettagli["descrizione"];?>
              </div>
              <div class="col s2 valign center-align condensed bold">
                <?php
                  if($dettagliCorso["iddocente"] == '0'){
                    echo "<span class='italic'>Docenti vari</span>";
                  }
                  else {
                    echo $dettagli["nome"][0].'. '.$dettagli["cognome"];
                  }
              ?>
              </div>
              <div class="col s2 valign center-align capitalize condensed">
                <p style="margin: 0.4em;">
                  <?php if(!$dettagli["tipo"]) echo "Recupero"; else echo "Approfondimento";?>
                </p>
                <p style="margin:0.4em;">
                  <?php if($dettagli["continuita"]) echo "Con continuità"; else echo "Senza continuità";?>
                </p>
              </div>
              <div class="col s2 center valign condensed">
                <a class="waves-effect small-icon-corsi condensed waves-red fill-width fake-button valign red-text" onclick="mostraModalDettagli(<?php echo $dettagli['id'];?>, <?php echo $dettagli["idDocente"];?>)" style="width:98%;">
                  <i class="material-icons">more_horiz</i> <br/>DETTAGLI
                </a>
              </div>
            </div>
          </li><?php
        }

        $resultConta = $db->query("SELECT  corsi.id as conta
                              FROM corsi, utenti
                              WHERE (utenti.id = corsi.iddocente) AND $condizione") or die("BB: ".$db->error);
          if($resultConta->num_rows>($max-$min)){
            ?>
            <li class="collection-item row center valign-wrapper">
              <div class"center center-text">
              <form action = "gestisciCorsi.php" method="post">
                <input type="hidden" name = "min" value="<?php echo ($max)?>"></input>
                <input type="hidden" name = "max" value="<?php echo ($max+$max-$min)?>"></input>
                <button class="btn center-text waves-effect waves-light red white-text" type="submit" name="action">Avanti
                </button>
              </div>
              </form>
            </li>
            <?php
          }
        ?>
</ul>
    </div>

    <div id="modal-orario" class="modal modal-fixed-footer" style="marign-top:5em;">
      <div class="modal-content">
          <h1 class="light-blue-text thin center" style="margin-bottom:0.3em;">Orario</h1>
      </div>
      <div class="modal-footer">
        <a href="#!" style="margin-bottom:1em;" class="modal-action modal-close waves-effect waves-red red-text btn-flat">CHIUDI</a>
      </div>
    </div>

  <div id="modal-ore" class="modal modal-fixed-footer bottom-sheet" style="height:87%;">

  </div>

  <div id="wait" class="center-align valign-wrapper">
    <div id="contenitore-cerchio" class="valign">
      <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-blue-only">
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
    <script src="js/admin.js"></script>

    <button class="material-scrolltop" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

    <!-- material-scrolltop plugin -->
    <script src="js/material-scrolltop.js"></script>

    <!-- Initialize material-scrolltop with (minimal) -->
    <script>
      $('body').materialScrollTop();
    </script>
</body>
<?php
$db->close(); ?>
