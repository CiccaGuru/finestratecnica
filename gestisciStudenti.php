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

<body id="studenti">
  <nav class="primary">
    <ul id="utenti-dropDown" class="dropdown-content">
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciStudenti.php">STUDENTI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciDocenti.php">DOCENTI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciCorsi.php">CORSI</a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciClassi.php">CLASSI</a></li>
      	<li><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i></a></li>
      <li><a class="waves-effect waves-primary condensed primary-text" href="gestisciAule.php">AULE</a></li>
    </ul>
    <div class="navbar-fixed">
      <nav id="intestaz" class="primary">
        <div class="nav-wrapper">
          <a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
          <a href="#" class="brand-logo center condensed light">Settimana tecnica</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
            <li class="active"><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
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
            <div class="input-field col s3">
              <input id="passwordStudente" type="password" class="validate" requiaccent>
              <label class="condensed" for="passwordStudente">Password</label>
            </div>
            <div class="input-field col s3">
              <input id="ripeti_passwordStudente" type="password" class="validate" requiaccent>
              <label class="condensed" for="ripeti_passwordStudente">Ripeti password</label>
            </div>
            <div class="input-field col s3">
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
            <div class="input-field col s3">
              <select id="selezionaSezioneStudente">
                <option value="" disabled selected>Classe mancante</option>
              </select>
              <label>Sezione</label>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="col s4 center offset-s4">
            <button type="submit" class="waves-effect condensed waves-light btn-large accent">
              <i class="material-icons left">person_add</i>
              Aggiungi
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container" style="width:90%">
    <ul class="collection with-header z-depth-1" id="dettagliStudenti">

    </ul>
  </div>

  <div id="modal-orario" class="modal bottom-sheet" style="marign-top:5em;">
    <div class="modal-content">
      <h1 class="primary-text thin center" style="margin-bottom:0.3em;">Orario</h1>
    </div>
  </div>

  <div id="modal-scegli-classe" class="modal modal-piccolo" style="marign-top:5em;">
    <div class="modal-content">

    </div>
    <div class="modal-footer">
      <a href="#!" id="applicaCambiaClasse" class=" modal-action waves-effect waves-light accent btn">APPLICA</a>
      <a href="#!" class=" modal-action modal-close waves-effect waves-accent accent-text btn-flat">CHIUDI</a>
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
