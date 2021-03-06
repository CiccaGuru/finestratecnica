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
	<link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
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
			<li class="active"><a href="admin.php" class="waves-effect waves-light condensed"><i class="material-icons">home</i>HOME</a></li>
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
						<li class="active"><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
						<li><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
						<li><a href="impostazioni.php" class="waves-effect waves-light condensed"><i class="material-icons">settings</i></a></li>
						<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</nav>

	<div class="container">
		<div class="row more-margin-bottom">
			<div class="col m4 s6">
				<a class="condensed fake-button letter-spacing-1 waves-effect waves-amber amber-text text-darken-1" href="gestisciDocenti.php">
					<i class="material-icons">school</i> <br/>GESTISCI DOCENTI
				</a>
			</div>
			<div class="col m4 s6">
				<a id="btn-gestisciDocenti" class="condensed fake-button letter-spacing-1 waves-effect waves-red red-text " href="gestisciCorsi.php">
					<i class="material-icons">class</i><br/>
					GESTISCI CORSI
				</a>
			</div><div class="col m4 s6 offset-s3">
				<a id="btn-gestisciDocenti" class="condensed fake-button letter-spacing-1 waves-effect waves-green green-text text-darken-2" href="gestisciStudenti.php">
					<i class="material-icons">people</i><br/>
					GESTISCI STUDENTI
				</a>
			</div>
		</div>
		<div class="card">
			<div class="card-content">
				<h3 class="light condensed letter-spacing-1 center primary-text">DETTAGLI ISCRIZIONI</h3>
				<div class="percentuale-container">
					<div class="percentuale">
						<?php
						$result = $db->query("SELECT COUNT(*) as conta from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') > 0") or die($db->error);
						$accesso = $result->fetch_assoc();
						$result = $db->query("SELECT COUNT(*) as conta from utenti where level='0'") or die($db->error);
						$tutti = $result->fetch_assoc();
						if($tutti["conta"]!=0){
							$percentuale = intval($accesso["conta"]/$tutti["conta"]*100);
						}
						else{
							$percentuale = 0;
						}

						if(($percentuale == 0) && ($accesso["conta"]>0))
						{
							$percentuale = 1;
							$percentuale_mostra = '< 1';
						}
						else{
							$percentuale_mostra = $percentuale;
						}
						?>
						<div class="c100 p<?php echo $percentuale;?>">
							<span><?php echo $percentuale_mostra;?>%</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
					</div>
					<div class="percentuale-azioni">
						<h5 class="primary-text condensed light">ALUNNI ENTRATI ALMENO UNA VOLTA:</h5>
						<div class="azioni-button">
								<span class="contatore accent-text"><?php echo $accesso["conta"]."/".$tutti["conta"];?></span>
								<a class="btn-flat accent-text waves-effect waves-red condensed" onclick="elencoNonAccessi()">GENERA ELENCO</a>
						</div>
					</div>
				</div>
				<div class="percentuale-container">
					<div class="percentuale">
						<?php
						$result = $db->query("SELECT COUNT(*) as conta from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') > 16") or die($db->error);
						$sufficienti = $result->fetch_assoc();
						if($accesso["conta"]!=0){
							$percentuale = intval($sufficienti["conta"]/$accesso["conta"]*100);
						}
						else{
							$percentuale = 0;
						}
						if(($percentuale == 0) && ($sufficienti["conta"]>0))
						{
							$percentuale = 1;
							$percentuale_mostra = '< 1';
						}
						else{
							$percentuale_mostra = $percentuale;
						}
						?>
						<div class="c100 p<?php echo $percentuale;?>">
							<span><?php echo $percentuale_mostra;?>%</span>
							<div class="slice">
								<div class="bar"></div>
								<div class="fill"></div>
							</div>
						</div>
					</div>
					<div class="percentuale-azioni">
						<h5 class="primary-text condensed light">ALUNNI ISCRITTI A ORE SUFFICIENTI:</h5>
						<div class="azioni-button">
								<span class="contatore accent-text"><?php echo $sufficienti["conta"]."/".$accesso["conta"];?></span>
								<a class="btn-flat accent-text waves-effect waves-red condensed" onclick="elencoNonAbbastanza()">GENERA ELENCO</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-content">
				<h3 class="center light primary-text condensed letter-spacing-1">OPERAZIONI DI STAMPA</h3>
				<div class="container width-90">
					<div class="row">
						<div class="col s6 m4 offset-m2">
							<a class="fake-button accent-text waves-effect waves-red fill-width valign-wrapper" onclick=" generaRegistrini();">
								<i class="material-icons">description</i><br/>
								REGISTRINI
							</a>
						</div>
						<div class="col s6 m4">
							<a class="fake-button accent-text waves-effect valign-wrapper fill-width waves-red" onclick=" generaOreBuche();">
								<i class="material-icons">date_range</i><br/>
								ORE BUCHE
							</a>
						</div>
					</div>
					<div class="row">
						<div class="col s6 m3">
							<a class="fake-button accent-text waves-effect fill-width valign-wrapper waves-red" onclick="generaCorsi(1);">
								<div class="row">
									<div class="col s12 m4">
										<i class="material-icons">school</i><br/>
									</div>
									<div class="col s12 m8">
										<p class="valign">CORSI <br/> <span class="small">(DOCENTI)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s6 m3">
							<a class="fake-button accent-text waves-effect valign-wrapper fill-width waves-red"  onclick="generaCorsi(3);">
								<div class="row">
									<div class="col s12 m4">
										<i class="material-icons">alarm</i><br/>
									</div>
									<div class="col s12 m8">
										<p class="valign">CORSI <br/> <span class="small">(ORA)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s6 m3">
							<a class="fake-button accent-text waves-effect valign-wrapper fill-width waves-red" onclick="generaCorsi(2);">
								<div class="row">
									<div class="col s12 m4">
										<i class="material-icons">home</i><br/>
									</div>
									<div class="col s12 m8">
										<p class="valign">CORSI <br/> <span class="small">(AULA)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s6 m3">
							<a class="fake-button accent-text waves-effect valign-wrapper fill-width waves-red" onclick="generaCorsi(4);">
								<div class="row">
									<div class="col s12 m4">
										<i class="material-icons">title</i><br/>
									</div>
									<div class="col s12 m8">
										<p class="valign">CORSI <br/> <span class="small">(TITOLO)</span></p>
									</div>
								</div>
							</a>
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
	<script src="js/admin.js"></script>
</body>
</html>
