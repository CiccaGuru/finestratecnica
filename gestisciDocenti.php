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
  <title>Admin - Docenti</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
  <link rel="stylesheet" href="css/material-scrolltop.css">
  <link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
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
<body id="docenti">
  <nav class="primary">
		<ul id="utenti-dropDown" class="dropdown-content">
			<li><a class="waves-effect waves-primary condensed primary-text" href="gestisciStudenti.php">STUDENTI</a></li>
			<li><a class="waves-effect waves-primary condensed primary-text" href="gestisciDocenti.php">DOCENTI</a></li>
			<li><a class="waves-effect waves-primary condensed primary-text" href="gestisciCorsi.php">CORSI</a></li>
			<li><a class="waves-effect waves-primary condensed primary-text" href="gestisciClassi.php">CLASSI</a></li>
			<li><a class="waves-effect waves-primary condensed primary-text" href="gestisciAule.php">AULE</a></li>
		</ul>
    <ul id="sidebar" class="side-nav">
			<li><div class="userView">
				<div class="background primary">

				</div>
				<a class="text-on-primary condensed" style="font-size:200%;">Finestratecnica</a>
				<a><span class="text-on-primary condensed username">Amministratore</span></a>
			</div></li>
			<li><a href="admin.php" class="waves-effect waves-light condensed"><i class="material-icons">home</i>HOME</a></li>
			<li class="no-padding active">
				<ul class="collapsible" data-collapsible="accordion">
					<li>
						<a class="collapsible-header condensed"><i class="material-icons">find_in_page</i>GESTISCI<i class="material-icons right-icon">arrow_drop_down</i></a>
						<div class="collapsible-body">
							<ul>
								<li><a class="waves-effect condensed" href="gestisciStudenti.php">STUDENTI</a></li>
								<li class="active"><a class="waves-effect condensed" href="gestisciDocenti.php">DOCENTI</a></li>
								<li><a class="waves-effect condensed" href="gestisciCorsi.php">CORSI</a></li>
								<li><a class="waves-effect condensed" href="gestisciClassi.php">CLASSI</a></li>
								<li><a class="waves-effect condensed" href="gestisciAule.php">AULE</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</li>
			<li><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i>IMPOSTAZIONI</a></li>
			<li class="divider"></li>
			<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
		</ul>
		<div class="navbar-fixed">
			<nav id="intestaz" class="primary">
				<div class="nav-wrapper">
          <a href="#" data-activates="sidebar" class="button-collapse"><i class="material-icons">menu</i></a>
					<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
					<a href="#" class="brand-logo center condensed light">Settimana tecnica</a>
					<ul id="nav-mobile" class="right hide-on-med-and-down">
						<li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
						<li class="active"><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
            	<li><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i></a></li>
            <li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</nav>
  <div class="container"  style="margin-top:3em;">
  <div class="card">
    <form id="aggiungi-docente">
      <div class="card-content center-align"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title primary-text center-align condensed">Aggiungi un nuovo docente</span>
        <div class="row">
          <div class="input-field col s4">
            <input id="nome" type="text" class="validate" required>
            <label class="condensed" for="last_name">Nome</label>
          </div>
          <div class="input-field col s4">
            <input id="cognome" type="text" class="validate" required>
            <label class="condensed" for="last_name">Cognome</label>
          </div>
          <div class="input-field col s4">
            <input id="username" type="text" class="validate" required>
            <label class="condensed" for="last_name">Nome utente</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s4">
            <input id="password" type="password" class="validate" required>
            <label class="condensed" for="last_name">Password</label>
          </div>
          <div class="input-field col s4">
            <input id="ripeti_password" type="password" class="validate" required>
            <label class="condensed" for="last_name">Ripeti password</label>
          </div>
          <div class="col s4 center">
            <button type="submit" class="waves-effect waves-light btn-large red condensed">
              <i class="material-icons left">person_add</i>Aggiungi</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="container" style="width:90%">
  <ul class="collection with-header z-depth-1" id="dettagliDocenti">

  </ul>



</div>

<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>
<script src="js/gestisciUtenti.js"></script>
<!-- material-scrolltop button -->
<button class="material-scrolltop" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

<!-- material-scrolltop plugin -->
<script src="js/material-scrolltop.js"></script>

<!-- Initialize material-scrolltop with (minimal) -->
<script>
  $('body').materialScrollTop();
</script>
</body>
