<?php
include_once "include/funzioni.php";
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
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Admin - Classi</title>
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
						<li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
						<li class="active"><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
						<li><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i></a></li>
						<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</nav>
	<div class="container" id="ins-classe" style="margin-top:5em;">
		<div class="card">
			<form id ="aggiungiClasse">
			<div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
				<h4 class="center-align condensed primary-text light">Crea classe</h4>
				<div class="row valign-wrapper">
					<div class="col s1 bold valign condensed">
						ANNO:
					</div>
					<div class="input-field valign  col s2">
						<select id="selezionaClasseStudente">
							<option value="" disabled selected>Scegli</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</div>
					<div class="col s2 valign offset-s1 bold condensed right-align">
						SEZIONE:
					</div>
					<div class="col valign  s2 input-field">
						<input type="text" id="sezione">
						<label for="sezione">Sezione</label>
					</div>
					<div class="col valign  s3 offset-s1">
						<button type="submit" class="waves-effect waves-light btn-large condensed accent">
							<i class="material-icons left">group_add</i>CREA CLASSE
						</button>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>

	<div class="container">
		<ul class="collection with-header z-depth-1" id="dettagliSezioni">
			<li class="collection-header center"><h4 class="condensed primary-text">ELENCO SEZIONI</h4></li>
			<li class="collection-item center">
				<div class="preloader-wrapper big active" style="margin:2em;">
					<div class="spinner-layer spinner-blue-only">
						<div class="circle-clipper left">
							<div class="circle"></div>
						</div><div class="gap-patch">
							<div class="circle"></div>
						</div><div class="circle-clipper right">
							<div class="circle"></div>
						</div>
					</div>
				</div>
			</li>
		</ul>


		<script src="js/jquery-3.1.1.min.js"></script>
		<script src="js/materialize.js"></script>
		<script src="js/init.js"></script>
		<script src="js/gestisciClassi.js"></script>
	</body>
	</html>
