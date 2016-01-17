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
	<link rel="stylesheet" href="css/circle.css">
</head>

<body style="height:100%;">
	<nav class="light-blue">
		<div class="nav-wrapper">
			<a class="left light big" style="margin-left:2%">Amministratore</a>
			<a href="#" class="brand-logo center light">Settimana tecnica</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li class="active"><a href="admin.php" class="waves-effect waves-light">Home</a></li>
				<li><a href="gestisciDocenti.php" class="waves-effect waves-light">Docenti</a></li>
				<li><a href="gestisciCorsi.php" class="waves-effect waves-light">Corsi</a></li>
				<li><a href="gestisciStudenti.php" class="waves-effect waves-light">Studenti</a></li>
				<li><a href="logout.php" class="waves-effect waves-light">Logout</a></li>
			</ul>
		</div>
	</nav>

<div class="container" style="margin-top:3em;">
	<div class="row">
		<div class="col s4">
			<a class="btn-large waves-effect waves-light  yellow valign-wrapper black-text darken-1"  style="width:95%; height:5.5em" href="gestisciDocenti.php">
				<p class="valign">Gestisci docenti</p>
			</a>
		</div>
		<div class="col s4">
			<a class="btn-large waves-effect waves-light red valign-wrapper "  style="width:95%; height:5.5em" href="gestisciCorsi.php">
				<p class="valign">Gestisci corsi</p>
			</a>
		</div><div class="col s4">
			<a class="btn-large waves-effect waves-light green valign-wrapper"  style="width:95%; height:5.5em" href="gestisciStudenti.php">
				<p class="valign">Gestisci studenti</p>
			</a>
		</div>
</div>
		<div class="card">
			<div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
				<h3 class="thin center light-blue-text" style="margin-top:5px;">Dettagli Iscrizioni</h3>
				<div class="row">
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
							<h5 class="light-blue-text light">Alunni che hanno effettuato il primo accesso:</h5>
							<div class="row" style="margin:0px">
								<div class="col s7">
									<span style="font-size:150%;" class="red-text"><?php echo $accesso["conta"]."/".$tutti["conta"];?></span>
								</div>
								<div class="col s5">
									<a class="btn red white-text waves-effect waves-light" onclick="elencoNonAccessi()">ELENCO</a>
								</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s3">
						<?php
							$result = $db->query("SELECT COUNT(*) as conta from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') > 16") or die($db->error);
							$accesso = $result->fetch_assoc();
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
						<h5 class="light-blue-text light">Alunni non iscritti a ore sufficienti:</h5>
						<div class="row" style="margin:0px">
							<div class="col s7">
								<span style="font-size:150%;" class="red-text"><?php echo $accesso["conta"]."/".$tutti["conta"];?></span>
							</div>
							<div class="col s5">
								<a class="btn red white-text waves-effect waves-light" onclick="elencoNonAbbastanza()">ELENCO</a>
							</div>
					</div>
					</div>
				</div>
			</div>
			</div>
		<div class="card">
			<div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
				<h3 class="center light light-blue-text">Operazioni di stampa</h3>
				<div class="row">
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick=" generaRegistrini();">
								<p class="valign" >Genera registrini</p>
						</a>
					</div>
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick=" generaOreBuche();">
								<p class="valign" >Ore buche</p>
						</a>
					</div>
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick="generaCorsiByDocenti();">
								<p class="valign">Corsi (docenti)</p>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick="generaCorsiByOra();">
								<p class="valign">Corsi (ora)</p>
						</a>
					</div>
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick="generaCorsiByAula();">
								<p class="valign">Corsi (aula)</p>
						</a>
					</div>
					<div class="col s4">
						<a class="btn-large red white-text waves-effect valign-wrapper waves-light" style="width:95%;" onclick="generaCorsiByTitolo();">
								<p class="valign">Corsi (titolo)</p>
						</a>
					</div>
				</div>
			</div>
		</div>
		</div>
		<div id="wait" class="center-align valign-wrapper" style="position:fixed; top:0px; left:0px; height:100%; width:100%;">
		<div id="contenitore-cerchio" class="valign">
			<div class="preloader-wrapper big active">
				<div class="spinner-layer spinner-blue-only">
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
		 </div>
			<p id='errore-login' class='white-text'>Elaborazione in corso.. può richiedere molto tempo!</p>
			<div class="progress center" style="width:70%; margin:auto;">
      	<div class="determinate center red" style="width: 0%;"></div>
  		</div>
		</div>

		<script src="js/jquery-2.1.4.min.js"></script>
	  <script src="js/materialize.js"></script>
	  <script src="js/init.js"></script>
	  <script src="js/admin.js"></script>

		</body>
		</html>
