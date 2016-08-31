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
        <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciAule.php">AULE</a></li>
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
      <ul class="collection with-header z-depth-1" id="dettagliCorsi">

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

    <div id="modalCorsiIncompatibili" class="modal modal-piccolo modal-fixed-footer" style="marign-top:5em;">
      <div class="modal-content">
        <h4 class="light-blue-text light condensed center">Scegli corso</h4>
        <div class="row" style="margin-top:-1.8em; margin-bottom:0.2em;">
        <div class="col s2" style="font-size:120%; margin-top:0.8em">
          <p class="condensed red-text">
            <i class="material-icons left">search</i>
          </p>
        </div>
          <div class="input-field col s10 valign">
            <input id="cercaCorsiIncompatibili" name="corsoIncompatibile" type="text" value="" class="valign">
          </div>
      </div>
      <div id="elencoCorsiIncompatibili">

      </div>
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
    <script src="js/gestisciCorsi.js"></script>

    <button class="material-scrolltop" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

    <!-- material-scrolltop plugin -->
    <script src="js/material-scrolltop.js"></script>

    <!-- Initialize material-scrolltop with (minimal) -->
    <script>
    $('body').materialScrollTop();
    </script>
  </body>
