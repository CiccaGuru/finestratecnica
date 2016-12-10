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
$db = database_connect();

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Admin - Aule</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
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
								<li><a class="waves-effect condensed" href="gestisciDocenti.php">DOCENTI</a></li>
								<li><a class="waves-effect condensed" href="gestisciCorsi.php">CORSI</a></li>
								<li><a class="waves-effect condensed" href="gestisciClassi.php">CLASSI</a></li>
								<li class="active"><a class="waves-effect condensed" href="gestisciAule.php">AULE</a></li>
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
  <div class="container">
  <div class="card">
    <form id="aggiungi-aula">
      <div class="card-content center-align"  style="padding-left:2em; padding-right:2em; padding-bottom:2em;">
        <div class="card-title primary-text center-align condensed" style="margin-bottom:1em;">Aggiungi una nuova aula</div>
        <div class="row">
          <div class="input-field col s6 m4 offset-m1">
            <input id="nomeAula" type="text" class="validate" required>
            <label class="condensed" for="nomeAula">Nome aula</label>
          </div>
          <div class="input-field col s6 m3">
            <input id="maxStudenti" type="text" class="validate" required>
            <label class="condensed" for="maxStudenti">Numero studenti</label>
          </div>
          <div class="col s8 offset-s2 m3">
          <button type="submit" class="waves-effect waves-light btn-large accent condensed">
            <i class="material-icons left">add_location</i>Aggiungi
          </button>
        </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="elenco-aule-container">
<ul class="collection with-header z-depth-1" id="dettagliAule">


</ul>

  <script src="js/jquery-3.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/gestisciAule.js"></script>
  <!-- material-scrolltop button -->

</div>
<button class="material-scrolltop hide-on-small-only" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

<!-- material-scrolltop plugin -->
<script src="js/material-scrolltop.js"></script>

<!-- Initialize material-scrolltop with (minimal) -->
<script>
  $('body').materialScrollTop();
</script>
</body>
<?php
$db->close();
?>
