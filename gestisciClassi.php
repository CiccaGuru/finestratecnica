<?php
include_once "include/funzioni.php";
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
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
	<link rel="stylesheet" href="css/material-scrolltop.css">
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
	<div class="container" id="ins-classe" style="margin-top:5em;">
		<div class="card">
			<div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
				<h4 class="center-align condensed blue-text light">Crea classe</h4>
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
						<a id="aggiungiClasse" class="waves-effect waves-light btn-large condensed red">  <i class="material-icons left">group_add</i>CREA CLASSE</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<ul class="collection with-header z-depth-1" id="dettagliSezioni">
			<li class="collection-header blue-text center"><h4 class="condensed light">ELENCO SEZIONI</h4></li>
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


		<script src="js/jquery-2.1.4.min.js"></script>
		<script src="js/materialize.js"></script>
		<script src="js/init.js"></script>
		<script src="js/admin.js"></script>
	</body>
	</html>