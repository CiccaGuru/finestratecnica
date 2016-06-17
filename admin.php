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
	<link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
	<link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
	<link rel="stylesheet" href="css/circle.css">
</head>

<body>
	<ul id="utenti-dropDown" class="dropdown-content">
  <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciStudenti.php">STUDENTI</a></li>
	<li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciDocenti.php">DOCENTI</a></li>
</ul>
	<div class="navbar-fixed">
		<nav id="intestaz" class="light-blue">
			<div class="nav-wrapper">
				<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
				<a href="#" class="brand-logo center light">Settimana tecnica</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li class="active"><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
					<li><a href="gestisciCorsi.php" class="waves-effect waves-light condensed">CORSI</a></li>
					<li><a href="#!" class="dropdown-button waves-effect waves-light condensed"  data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">UTENTI<i class="material-icons right">arrow_drop_down</i></a></li>
					<li><a href="logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
				</ul>
			</div>
		</nav>
	</div>

<div class="container">
	<div class="row more-margin-bottom">
		<div class="col s4">
			<a class="condensed fake-button letter-spacing-1 waves-effect waves-amber amber-text text-darken-1" href="gestisciDocenti.php">
				<i class="material-icons">school</i> <br/>GESTISCI DOCENTI
			</a>
		</div>
		<div class="col s4">
				<a id="btn-gestisciDocenti" class="condensed fake-button letter-spacing-1 waves-effect waves-red red-text " href="gestisciCorsi.php">
				<i class="material-icons">class</i><br/>
				GESTISCI CORSI
			</a>
		</div><div class="col s4">
				<a id="btn-gestisciDocenti" class="condensed fake-button letter-spacing-1 waves-effect waves-green green-text text-darken-2" href="gestisciStudenti.php">
				<i class="material-icons">people</i><br/>
				GESTISCI STUDENTI
			</a>
		</div>
</div>
		<div class="card">
			<div class="card-content">
				<h3 class="light condensed letter-spacing-1 center light-blue-text">DETTAGLI ISCRIZIONI</h3>
				<div class="row more-margin-bottom">
					<div class="col s3">
						<?php
							$result = $db->query("SELECT COUNT(*) as conta from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') > 0") or die($db->error);
							$accesso = $result->fetch_assoc();
							$result = $db->query("SELECT COUNT(*) as conta from utenti where level='0'") or die($db->error);
							$tutti = $result->fetch_assoc();
							$percentuale = intval($accesso["conta"]/$tutti["conta"]*100);

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
					<div class="col s9">
							<h5 class="light-blue-text condensed light">ALUNNI ENTRATI ALMENO UNA VOLTA:</h5>
							<div class="row nomargin">
								<div class="col s7">
									<span class="contatore red-text"><?php echo $accesso["conta"]."/".$tutti["conta"];?></span>
								</div>
								<div class="col s5">
									<a class="btn-flat red-text waves-effect waves-red condensed" onclick="elencoNonAccessi()">GENERA ELENCO</a>
								</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s3">
						<?php
							$result = $db->query("SELECT COUNT(*) as conta from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') > 16") or die($db->error);
							$sufficienti = $result->fetch_assoc();
							$percentuale = intval($sufficienti["conta"]/$accesso["conta"]*100);
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
					<div class="col s9">
						<h5 class="light-blue-text condensed light">ALUNNI ISCRITTI A ORE SUFFICIENTI:</h5>
						<div class="row nomargin">
							<div class="col s7">
								<span class="contatore red-text"><?php echo $sufficienti["conta"]."/".$accesso["conta"];?></span>
							</div>
							<div class="col s5">
								<a class="btn-flat red-text waves-effect waves-red condensed" onclick="elencoNonAbbastanza()">GENERA ELENCO</a>
							</div>
					</div>
					</div>
				</div>
			</div>
			</div>
		<div class="card">
			<div class="card-content">
				<h3 class="center light light-blue-text condensed letter-spacing-1">OPERAZIONI DI STAMPA</h3>
				<div class="container width-90">
					<div class="row">
						<div class="col s4 offset-s2">
							<a class="fake-button red-text waves-effect waves-red fill-width valign-wrapper" onclick=" generaRegistrini();">
									<i class="material-icons">description</i><br/>
									REGISTRINI
							</a>
						</div>
						<div class="col s4">
							<a class="fake-button red-text waves-effect valign-wrapper fill-width waves-red" onclick=" generaOreBuche();">
									<i class="material-icons">date_range</i><br/>
									ORE BUCHE
							</a>
						</div>
					</div>

					<div class="row">
						<div class="col s3">
							<a class="fake-button red-text waves-effect fill-width valign-wrapper waves-red" onclick="generaCorsiByDocenti();">
								<div class="row">
									<div class="col s4">
											<i class="material-icons">school</i><br/>
									</div>
									<div class="col s8">
										<p class="valign">CORSI <br/> <span class="small">(DOCENTI)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s3">
							<a class="fake-button red-text waves-effect valign-wrapper fill-width waves-red"  onclick="generaCorsiByOra();">
								<div class="row">
									<div class="col s4">
											<i class="material-icons">alarm</i><br/>
									</div>
									<div class="col s8">
										<p class="valign">CORSI <br/> <span class="small">(ORA)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s3">
							<a class="fake-button red-text waves-effect valign-wrapper fill-width waves-red" onclick="generaCorsiByAula();">
								<div class="row">
									<div class="col s4">
											<i class="material-icons">home</i><br/>
									</div>
									<div class="col s8">
										<p class="valign">CORSI <br/> <span class="small">(AULA)</span></p>
									</div>
								</div>
							</a>
						</div>
						<div class="col s3">
							<a class="fake-button red-text waves-effect valign-wrapper fill-width waves-red" onclick="generaCorsiByTitolo();">

								<div class="row">
									<div class="col s4">
											<i class="material-icons">title</i><br/>
									</div>
									<div class="col s8">
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
			<div id="contenitore-cerchio" class="valign">
				<div class="preloader-wrapper big active">
					<div class="spinner-layer spinner-blue-only">
						<div class="circle-clipper right">
							<div class="circle"></div>
						</div>
					</div>
				</div>
			</div>
			<p id='errore-login' class='white-text'>Elaborazione in corso.. può richiedere molto tempo!</p>
			<div class="progress center">
				<div class="determinate center red"></div>
			</div>
		</div>

		<script src="js/jquery-2.1.4.min.js"></script>
	  <script src="js/materialize.js"></script>
	  <script src="js/init.js"></script>
	  <script src="js/admin.js"></script>

		</body>
		</html>
