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
	<link href="css/colors.css" type="text/css" rel="stylesheet" media="screen"/>
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
			<ul id="sidebar" class="side-nav">
				<li><div class="userView">
					<div class="background primary">

					</div>
					<a class="text-on-primary condensed" style="font-size:200%;">Finestratecnica</a>
					<a><span class="text-on-primary condensed username">Amministratore</span></a>
				</div></li>
				<li><a href="admin.php" class="waves-effect waves-light condensed"><i class="material-icons">home</i>HOME</a></li>
				<li class="no-padding">
					<ul class="collapsible" data-collapsible="accordion">
						<li>
							<a class="collapsible-header condensed"><i class="material-icons">find_in_page</i>GESTISCI<i class="material-icons right-icon">arrow_drop_down</i></a>
							<div class="collapsible-body">
								<ul>
									<li><a class="waves-effect condensed" href="gestisciStudenti.php">STUDENTI</a></li>
									<li><a class="waves-effect condensed" href="gestisciDocenti.php">DOCENTI</a></li>
									<li><a class="waves-effect condensed" href="gestisciCorsi.php">CORSI</a></li>
									<li><a class="waves-effect condensed" href="gestisciClassi.php">CLASSI</a></li>
									<li><a class="waves-effect condensed" href="gestisciAule.php">AULE</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</li>
				<li class="active"><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i>IMPOSTAZIONI</a></li>
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
				<div data-trigger="database" class="setting-trigger waves-effect"><i class="material-icons">storage</i>Database & admin</div>
				<div data-trigger="aspettoBase" class="setting-trigger waves-effect"><i class="material-icons">color_lens</i>Aspetto</div>
				<div data-trigger="orario" class="setting-trigger waves-effect"><i class="material-icons">access_time</i>Orario</div>
			</div>
			<div id="main">
				<div id="cover">
					<p class="center-align grey-text text-darken-1"><i class="material-icons huge">settings</i></p>
					<p class="big-text center-align grey-text grey-darken-1" >Scegli una categoria dal menu a lato<p>
					</div>

					<div id="aspettoBase" data-trigged="aspettoTrigger">
						<div class="row">
							<div class="col s10">
								<h3 class="primary-text condensed" style="margin-top:0px;">Modifica aspetto</h3>
							</div>
							<div class="col s2">
								<a  data-trigger="aspettoAvanzato" class="setting-trigger waves-effect waves-light btn primary condensed">AVANZATO</a>
							</div>
						</div>

						<p>Finestratecnica rispetta le Linee Guida Material Design. Per questo motivo è basata su due colori,
							un colore principale (primary) ed uno secondario, usato in elementi isolati (accent). Vengono usate anche delle sfumature più
							chiare e scure, per favorire la leggibilità.
						</p>
						<section>
							<div class="row">
								<div class="col s6">
									<div class="description">
										<h4 class="primary-text condensed">Primary color</h4>
										<div>E' il colore principale del sito</div>
										<div class="actual-container">
											<div>Valore attuale:</div>
											<div class="actual" style="color: <?php echo getProp("primaryText");?>; background-color: <?php echo getProp("primaryColor");?>;"><?php echo getProp("primaryColor");?></div>
										</div>
									</div>
									<div id="primaryColorBase" data-prop="primaryColor" class="colorpicker base">
										<div class="maincolor">
										</div>
										<div class="variations">
										</div>
									</div>
								</div>
								<div class="col s6">
									<div class="description">
										<h4 class="accent-text condensed">Accent color</h4>
										<div>E' il colore secondario del sito</div>
										<div class="actual-container">
											<div>Valore attuale:</div>
											<div class="actual" style="color: <?php echo getProp("accentText");?>; background-color:<?php echo getProp("accentColor");?>;"><?php echo getProp("accentColor");?></div>
										</div>
									</div>
									<div id="accentColorBase" data-prop="accentColor" class="colorpicker base">
										<div class="maincolor">
										</div>
										<div class="variations">
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>

					<div id="aspettoAvanzato" data-trigged="aspettoTrigger">
						<div class="row">
							<div class="col s10">
								<h3 class="primary-text condensed" style="margin-top:0px;">Modifica aspetto</h3>
							</div>
							<div class="col s2">
								<a data-trigger="aspettoBase" class="setting-trigger btn primary condensed">BASE</a>
							</div>
						</div>

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
									<div id="primaryColorAdvanced" data-prop="primaryColor" class="colorpicker advanced">
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
									<div id="primaryColorLight" class="colorpicker advanced">
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
									<div id="primaryColorDarkAdvanced" data-prop="primaryColorDark" class="colorpicker advanced">
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
									<div id="primaryColorDarkestAdvanced" data-prop="primaryColorDarkestAdvanced" class="colorpicker advanced">
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
									<div id="primaryTextAdvanced" data-prop="primaryText" class="colorpicker advanced">
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
									<div id="primaryLightTextAdvanced" data-prop="primaryLightText" class="colorpicker advanced">
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
									<div id="primaryDarkTextAdvanced" data-prop="primaryDarkText" class="colorpicker advanced">
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
									<div id="accentColorAdvanced" data-prop="accentColor" class="colorpicker advanced">
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
									<div id="accentColorLightAdvanced" data-prop="accentColorLight" class="colorpicker advanced">
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
									<div id="accentTextAdvanced" data-prop="accentText" class="colorpicker advanced">
										<div class="maincolor">
										</div>
										<div class="variations">
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>

					<div id="database" data-trigged="databaseTrigger">
						<h3 class="primary-text condensed" style="margin-top:0px;">Impostazioni del database</h3>
						<div class="row">
							<div class="col s12 l6 offset-l3">
								<div class="card" id="card-database">
									<div class="card-content">
										<div class="card-title condensed primary-text">
											Connessione al database
										</div>
										<form id="modificaDatabase">
										<div class="input-field">
												<input id="db-host" type="text" autocomplete="off" class="validate">
												<label for="db-host">Posizione database</label>
										</div>
										<div class="input-field">
												<input id="db-username" type="text" autocomplete="off" class="validate">
												<label for="db-host">Username database</label>
										</div>
										<div class="input-field">
												<input id="db-password" type="password" autocomplete="off" class="validate">
												<label for="db-password">Password database</label>
										</div>
										<div class="input-field">
												<input id="db-name" type="text"  autocomplete="off"class="validate">
												<label for="db-host">Nome database</label>
										</div>
										<div class="right-align">
										<button type="submit" class="btn accent">APPLICA</button>
									</div>
									</form>
									</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col s12 l6 offset-l3">
									<div class="card" id="card-admin">
										<div class="card-content">
											<div class="card-title condensed primary-text">
												Utente amministratore
											</div>
											<div id="admin-error"></div>
											<form id="modificaAdmin">
											<div class="input-field">
													<input id="admin-username" type="text" autocomplete="off" class="validate">
													<label for="admin-username">Username</label>
											</div>
											<div class="input-field">
													<input id="admin-nowpassword" type="password" autocomplete="off" class="validate">
													<label for="admin-nowpassword">Password attuale</label>
											</div>
											<div class="input-field">
													<input id="admin-newpassword" type="password" autocomplete="off" class="validate">
													<label for="admin-newpassword">Nuova password</label>
											</div>
											<div class="input-field">
													<input id="admin-renewpassword" type="password" autocomplete="off" class="validate">
													<label for="admin-renewpassword">Ripeti nuova password</label>
											</div>
											<div class="right-align">
											<button type="submit" class="btn accent waves-effect waves-light">APPLICA</button>
										</div>
										</form>
										</div>
										</div>
									</div>
								</div>
						</div>
						<div id="orario"  data-trigged="orarioTrigger">
							<h3 class="primary-text condensed" style="margin-top:0px;">Impostazioni dell'orario</h3>
							<div class="row">
								<div class="col s12 l8 offset-l2">
									<div class="card" id="card-orario">
										<div class="card-content">
											<form id="modificaOrario">
												<div class="row valign-wrapper">
													<div class="col s3 bold condensed valign">
														Numero giorni
													</div>
													<div class="col s1 valign center-align">
														<i class="material-icons tooltipped" data-position="right" data-delay="50" data-tooltip="Il numero dei giorni che durerà la finestra tecnica">info_outline</i>
													</div>
													<div class="input-field col s8 valign">
				 											<input id="numero_giorni" type="text" value="<?php echo getProp("numero_giorni");?>" class="validate">
			 										</div>
												</div>
												<div class="row valign-wrapper">
													<div class="col s3 bold condensed valign">
														Apertura iscrizioni
													</div>
													<div class="col s1 valign center-align">
														<i class="material-icons tooltipped" data-position="right" data-delay="50" data-tooltip="Quando si apriranno le iscrizioni">info_outline</i>
													</div>
													<div class="col s4 valign">
														<label for="apertura_iscrizioni">Scegli la data...</label>
														<input id="apertura_iscrizioni" name="apertura_iscrizioni" type="date" data-value="<?php echo date("d/m/Y", getProp("apertura_iscrizioni"))?>" class="datepicker">
													</div>
													<div class="input-field col s2 valign">
															<input id="ora-apertura" type="text" value="<?php echo date("H", getProp("apertura_iscrizioni"))?>"  placeholder="hh" class="validate">
															<label for="ora-apertura">Ora</label>
													</div>:
													<div class="input-field col s2 valign">
															<input id="minuti-apertura" type="text"  value="<?php echo date("i", getProp("apertura_iscrizioni"))?>"   placeholder="mm" class="validate">
															<label for="minuti-apertura">Minuti</label>
													</div>
												</div>
												<div class="row valign-wrapper">
													<div class="col s3 bold condensed valign">
														Chiusura iscrizioni
													</div>
													<div class="col s1 valign center-align">
														<i class="material-icons tooltipped" data-position="right" data-delay="50" data-tooltip="Quando si apriranno le iscrizioni">info_outline</i>
													</div>
													<div class="col s4 valign">
														<label for="apertura_iscrizioni">Scegli la data...</label>
														<input id="chiusura_iscrizioni" name="chiusura_iscrizioni" type="date" data-value="<?php echo date("d/m/Y", getProp("chiusura_iscrizioni"))?>" class="datepicker">
													</div>
													<div class="input-field col s2 valign">
															<input id="ora-chiusura" value="<?php echo date("H", getProp("chiusura_iscrizioni"))?>"  type="text" placeholder="hh" class="validate">
															<label for="ora-chiusura">Ora</label>
													</div>:
													<div class="input-field col s2 valign">
															<input id="minuti-chiusura" value="<?php echo date("i", getProp("chiusura_iscrizioni"))?>" type="text" placeholder="mm"  class="validate">
															<label for="minuti-chiusura">Minuti</label>
													</div>
												</div>
												<div class="row valign-wrapper">
												<div class="col s3 bold condensed valign">
													Ore per giorno:
												</div>
												<div class="col s1 valign center-align">
													<i class="material-icons tooltipped" data-position="right" data-delay="50" data-tooltip="Il numero di lezioni massime giornaliere">info_outline</i>
												</div>
												<div class="input-field col s2 valign">
														<input id="ore_per_giorno" value="<?php echo getProp("ore_per_giorno");?>"  type="text" class="validate">
												</div>
											<div class="col s3 bold condensed right-align valign">
												Ore minime:
											</div>
											<div class="col s1 valign center-align">
												<i class="material-icons tooltipped" data-position="right" data-delay="50" data-tooltip="Il minimo numero di ore a cui uno studente deve iscriversi">info_outline</i>
											</div>
											<div class="input-field col s2 valign">
													<input id="soglia_minima" value="<?php echo getProp("soglia_minima");?>"  type="text" class="validate">
											</div>
										</div>
											<div class="right-align">
											<button type="submit" class="btn waves-effect waves-light accent">APPLICA</button>
										</div>
										</form>
										</div>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="wait" class="center-align valign-wrapper">
			<div id="close-icon" class="right white-text" style="display:none; margin:0.5em;"><i class="material-icons waves-effect waves-light waves-circle">close</i></div>
			<p id='messaggio' class='valign condensed white-text'>Elaborazione in corso.. può richiedere molto tempo!</p>
			<div id="contenitore-cerchio-admin" class="valign">
				<div class="preloader-wrapper big active">
					<div class="spinner-layer spinner-primary-only">
						<div class="circle-clipper right">
							<div class="circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="js/jquery-3.1.1.min.js"></script>
		<script src="js/materialize.js"></script>
		<script src="js/init.js"></script>
		<script src="js/impostazioni.js"></script>

	</body>
	</html>
