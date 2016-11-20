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
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Admin - Home</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
	<link rel="stylesheet" href="css/circle.php">
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
	<div id="total">
	<nav class="primary" id="head">
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
						<li><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
						<li class="active"><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i></a></li>
						<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</nav>
	<div class="wrapper">

		<div id="side" class="z-depth-1 white grey-text text-darken-3 condensed">
				<div id="aspettoTrigger" class="setting-trigger waves-effect"><i class="material-icons">color_lens</i>Aspetto</div>
				<div id="orarioTrigger" class="setting-trigger waves-effect"><i class="material-icons">access_time</i>Orario</div>
				<div id="databaseTrigger" class="setting-trigger waves-effect"><i class="material-icons">storage</i>Database</div>
		</div>
		<div id="main">
			<div id="cover">
				<p class="center-align grey-text text-darken-1"><i class="material-icons huge">settings</i></p>
				<p class="big-text center-align grey-text grey-darken-1" >Scegli una categoria dal menu a lato<p>
			</div>
			<div id="aspetto">
				<h3 class="primary-text condensed" style="margin-top:0px;">Modifica aspetto</h3>
				<p>Finestratecnica rispetta le Linee Guida Material Design. Per questo motivo è basata su due colori,
					un colore principale (primary) ed uno secondario, usato in elementi isolati (accent). Vengono usate anche delle sfumature più
					chiare e scure, per favorire la leggibilità.
				</p>
				<section>
					<h4 class="primary-text condensed">Primary</h4>
					  <div class="row">
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary color</h5>
									<div>E' il colore principale del sito</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryText");?>; background-color: <?php echo getProp("primaryColor");?>;"><?php echo getProp("primaryColor");?></div>
									</div>
								</div>
								<div id="primaryColor" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary color light</h5>
									<div>La sfumatura chiara del colore principale</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryLightText");?>; background-color:<?php echo getProp("primaryColorLight");?>;"><?php echo getProp("primaryColorLight");?></div>
									</div>
								</div>
								<div id="primaryColorLight" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary color dark</h5>
									<div>La sfumatura scura del colore principale</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryDarkText");?>; background-color:<?php echo getProp("primaryColorDark");?>;"><?php echo getProp("primaryColorDark");?></div>
									</div>
								</div>
								<div id="primaryColorDark" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
					  </div>
						<div class="row">
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary color darkest</h5>
									<div>La sfumatura scurissima del colore principale</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryDarkText");?>; background-color:<?php echo getProp("primaryColorDarkest");?>;"><?php echo getProp("primaryColorDarkest");?></div>
									</div>
								</div>
								<div id="primaryColorDarkest" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary text</h5>
									<div>Il colore del testo abbinato al colore principale</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryText");?>; background-color:<?php echo getProp("primaryColor");?>;"><?php echo getProp("primaryText");?></div>
									</div>
								</div>
								<div id="primaryText" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
							<div class="col s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary text light</h5>
									<div>Il colore del testo della sfumatura chiara</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryLightText");?>; background-color:<?php echo getProp("primaryColorLight");?>;"><?php echo getProp("primaryLightText");?></div>
									</div>
								</div>
								<div id="primaryLightText" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col s4 offset-s4">
								<div class="description">
									<h5 class="primary-text condensed">Primary text dark</h5>
									<div>Il colore del testo della sfumatura scura</div>
									<div class="actual-container">
										<div>Valore attuale:</div>
										<div class="actual" style="color: <?php echo getProp("primaryDarkText");?>; background-color:<?php echo getProp("primaryColorDark");?>;"><?php echo getProp("primaryDarkText");?></div>
									</div>
								</div>
								<div id="primaryDarkText" class="colorpicker">
									<div class="maincolor">
									</div>
									<div class="variations">
									</div>
								</div>
							</div>
						</div>
				</section>

				<section>
					<h4 class="accent-text condensed">Accent</h4>
					<div class="row">
						<div class="col s4">
							<div class="description">
								<h5 class="accent-text condensed">Accent color</h5>
								<div>E' il colore secondario del sito</div>
								<div class="actual-container">
									<div>Valore attuale:</div>
									<div class="actual" style="color: <?php echo getProp("accentText");?>; background-color: <?php echo getProp("accentColor");?>;"><?php echo getProp("accentColor");?></div>
								</div>
							</div>
							<div id="accentColor" class="colorpicker">
								<div class="maincolor">
								</div>
								<div class="variations">
								</div>
							</div>
						</div>
						<div class="col s4">
							<div class="description">
								<h5 class="accent-text condensed">Accent color light</h5>
								<div>La sfumatura chiara del colore secondario</div>
								<div class="actual-container">
									<div>Valore attuale:</div>
									<div class="actual" style="color: <?php echo getProp("accentText");?>; background-color:<?php echo getProp("accentColorLight");?>;"><?php echo getProp("accentColorLight");?></div>
								</div>
							</div>
							<div id="accentColorLight" class="colorpicker">
								<div class="maincolor">
								</div>
								<div class="variations">
								</div>
							</div>
						</div>
						<div class="col s4">
							<div class="description">
								<h5 class="accent-text condensed">Accent text</h5>
								<div>Il colore del testo del colore secondario</div>
								<div class="actual-container">
									<div>Valore attuale:</div>
									<div class="actual" style="color: <?php echo getProp("accentText");?>; background-color:<?php echo getProp("accentColor");?>;"><?php echo getProp("accentText");?></div>
								</div>
							</div>
							<div id="accentText" class="colorpicker">
								<div class="maincolor">
								</div>
								<div class="variations">
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>

	<div id="wait" class="center-align valign-wrapper">
			<div id="close-icon" class="right white-text" style="display:none; margin:0.5em;"><i class="material-icons waves-effect waves-light waves-circle">close</i></div>
			<p id='messaggio' class='valign condensed white-text'>Elaborazione in corso.. può richiedere molto tempo!</p>
			<div id="contenitore-cerchio-admin" class="valign">
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
	  <script src="js/impostazioni.js"></script>

		</body>
		</html>
