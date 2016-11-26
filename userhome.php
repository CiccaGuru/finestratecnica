<?php
require_once("include/funzioni.php");
require_once("include/config.php");

if(isset($_GET["ufficio"])){
	$posso = $_GET["ufficio"];
}

$utente = check_login();

if($utente==-1){
	header('Location: index.php');
}
else{
	$user_level = get_user_level($utente);
	if($user_level == 1)
		header('Location: docente.php');
	if($user_level == 2)
		header('Location: admin.php');
}

$db = database_connect();
$chiusura_iscrizioni = getProp("chiusura_iscrizioni");
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Settimana tecnica</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
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

<body id="body-userhome">
	<div class="navbar-fixed">
		<nav id="intestaz" class="primary">
			<div class="nav-wrapper">
				<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;">
					<?php
							$result = $db->query("SELECT 	username, nome, cognome, primoAccesso, passwordOriginale
																		FROM 		utenti
																		WHERE 	id = '$utente'");
							$dettagliUtente = $result->fetch_assoc();
							echo strtoupper($dettagliUtente["nome"]." ".$dettagliUtente["cognome"]." (".$dettagliUtente["username"].")");
					?>
				</a>
				<a href="#" class="brand-logo center condensed light">Settimana tecnica</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>				</ul>
			</div>
		</nav>
	</div>
	<div class="fixed-action-btn" id="pointsFAB">
		<a class="btn-floating btn-large accent">
			<i class="large material-icons">more_horiz</i>
		</a>
		<ul>
			<li><a class="btn-floating waves-effect waves-light yellow darken-1 tooltipped modal-trigger" href="#modal-scrivi" data-position="left" data-delay="50" data-tooltip="Scrivici"><i class="material-icons">mode_edit</i></a></li>
			<li><a class="btn-floating waves-effect waves-light green tooltipped" href="stampabile.php" data-position="left" data-delay="50" data-tooltip="Stampa orario"><i class="material-icons">print</i></a></li>
			<li><a class="btn-floating waves-effect waves-light blue tooltipped modal-trigger" href="#modal-help" data-position="left" data-delay="50" data-tooltip="Aiuto"><i class="material-icons">help</i></a></li>
		</ul>
	</div>

<div id="cont">
	<div class="row">
			<div class="col hide-on-large-only s12">
				<div id="card-orario-gen-piccolo" class="card">
					<div class="card-content">
						<div class="center valign-wrapper">
							<div class="preloader-wrapper valign center big active">
								<div class="spinner-layer spinner-accent-only">
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col l7 s12 center" id="elencoCorsiContainer">
					<?php
						$result = $db->query("SELECT COUNT(*) as conta from iscrizioni where idUtente = $utente and partecipa = '1'");
						$conta = $result->fetch_assoc();
						if((time()>$chiusura_iscrizioni) && ($conta["conta"]>=getProp("soglia_minima")) && !($posso == "44")){
					?>

					<h3 class="accent-text center light">Iscrizioni chiuse</h3>
					<div class="center">
							Le iscrizioni sono chiuse. Puoi solo visualizzare il tuo orario.
					</div>
					<a href = "stampabile.php" class="btn-flat accent-text waves-effect waves-accent center condensed">Orario stampabile</a>
					<div class="center">
						<img src="img/gif.gif" style="width:100%" alt="Nancy Cat">
					</div>
					<?php } else {?>

					<form id="formCerca">
					<div id="card-cerca" class="card hide-on-med-and-down" >
						<div class="card-content">
							<div class="row">
								<div class="valign-wrapper">
								<div class="input-field valign col condensed s12 m6" >
									<input id="filtro" type="text" class="validate">
									<label for="filtro">Parola chiave</label>
								</div>
								<div class="col s6 m3 valign left-align">
	                  <div class="row condensed letter-spacing-1">
												<p>
	                        <input checked type="checkbox" class="filled-in" name="concontinuita" id="concontinuitaCerca" />
	                        <label for="concontinuitaCerca">CON CONT.</label>
	                    </p>
	                  </div>
	                  <div class="row condensed">
	                    <p>
	                        <input checked type="checkbox" class="filled-in" name="senzacontinuita" id="senzacontinuitaCerca" />
	                        <label for="senzacontinuitaCerca">SENZA CONT.</label>
	                    </p>
	                  </div>
	                </div>
	                <div class="col s6 m2 valign left-align">
	                  <div class="row condensed">
	                    <p>
	                        <input checked type="checkbox" class="filled-in" name="recupero" id="recuperoCerca" />
	                        <label for="recuperoCerca">RECUPERO</label>
	                    </p>
	                  </div>
	                  <div class="row condensed">
	                    <p>
	                        <input checked type="checkbox" class="filled-in" name="approfondimento" id="approfondimentoCerca" />
	                        <label for="approfondimentoCerca">APPROF.</label>
	                    </p>
	                  </div>
	                </div>
									<div class="col s12 center-align m1">
										<button type="submit" id="cerca" class="btn-floating btn-large waves-effect waves-light red valign">
											<i class="material-icons">search</i>
										</button>
									</div>
								</div>
								</div>
						</div>
					</div>
</form>

<form id="formCercaP">
<div id="card-cercaP" class="card hide-on-large-only " >
	<div class="card-content" >
		<div class="row">
			<div class="input-field col s12" >
				<input id="filtroP" type="text" class="validate">
				<label for="filtro">Parola chiave</label>
			</div>
			<div class="col s6 left-align">
					<div class="row">
							<p>
								<input checked type="checkbox" name="concontinuita" id="concontinuitaCercaP" />
								<label for="concontinuitaCercaP">Con cont.</label>
						</p>
					</div>
					<div class="row">
						<p>
								<input checked type="checkbox" name="senzacontinuita" id="senzacontinuitaCercaP" />
								<label for="senzacontinuitaCercaP">Senza cont.</label>
						</p>
					</div>
				</div>
				<div class="col s6 left-align">
					<div class="row">
						<p>
								<input checked type="checkbox" name="recupero" id="recuperoCercaP" />
								<label for="recuperoCercaP">Recupero</label>
						</p>
					</div>
					<div class="row">
						<p>
								<input checked type="checkbox" name="approfondimento" id="approfondimentoCercaP" />
								<label for="approfondimentoCercaP">Approf.</label>
						</p>
					</div>
				</div>
				<div class="col s12 center-align">
					<button type="submit" id="cercaP" class="btn-floating btn-large waves-effect waves-light red valign">
						<i class="material-icons">search</i>
					</button>
				</div>
			</div>
			</div>
	</div>
	</form>

<?php  if(time()>$chiusura_iscrizioni){
	?>
	<h3 class="accent-text center light">Iscrizioni chiuse</h3>
	<div class="center">
		Le iscrizioni sono chiuse ma, visto che il tuo orario non è completo, potrai completarlo.
		<br/>Appena raggiungerai <?php echo $getProp("soglia_minima")?> ore sarà bloccato.
	</div>

<?php	} ?>

					<div id="elencoCorsiStudente" class="left-align">
						<div class="center">
							<div class="preloader-wrapper center big active">
								<div class="spinner-layer spinner-accent-only">
									<div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>
					</div><?php } ?>
				</div>
		<div class="col l5 hide-on-med-and-down s12">
			<div id="card-orario-gen" class="card">
				<div class="card-content">

				</div>
			</div>
		</div>
	</div>
		</div>
	<div id="modal-continuita" class="modal">
		<div class="modal-content">

		</div>
		<div class="modal-footer">
			<a href="#!" class=" modal-action modal-close waves-effect waves-red accent-text btn-flat">CHIUDI</a>
		</div>
	</div>

	<div id="modal-help" class="modal modal-fixed-footer">
		<div class="modal-content">
				<h4 class="center primary-text light">Guida all'uso</h4>
				<p>
					Questo sito permette l'iscrizione ai corsi della "Finestra tecnica" che si terrà dal 13 al 16 gennaio.

				</p>
				<h5 class="primary-text light">Interfaccia utente</h5>
				<p>
					L'interfaccia è composta da due colonne principali: sulla sinistra l'elenco dei corsi disponibili per
					la tua classe, a destra il tuo orario.<br/>
					E' inoltre presente un pulsante a sinistra per scorrere velocemente all'inizio della pagina (visibile solo se non sei
					all'inizio).<br/>
					Sulla destra invece, spostando il mouse sul pulsante, hai la possibilità di mandarci una mail direttamente dal sito
					(icona della matita), visualizzare il tuo orario stampabile (icona della stampante), visualizzare questo pannello delle
					informazioni (icona con la i).

				</p>
				<h5 class="primary-text light">Iscrizione</h5>
				<p>
					Per iscriversi al corso desiderato basta fare click sull'interruttore a destra del nome del corso. Verrà
					aggiornato l'orario e anche la lista dei corsi. L'interruttore rosso indica un corso a cui ci si è iscritti,
					quello grigio un corso a cui ci si può iscrivere. <br/> Per annullare l'iscrizione, fare nuovamente click
					sull'interruttore.
				</p>
				<h5 class="primary-text light">Visualizzare dettagli dei corsi</h5>
				<p>
					Facendo click sulla riga di un corso si apre una <span class="italic">card</span> che contiene informazioni
					aggiuntive. Ad esempio, se il nome del corso o la descrizione sono molto lunghi, verranno tagliati nella
					riga, ma sarà possibile visualizzarli per intero nei dettagli. Inoltre, è contenuto anche un elenco delle singole
					lezioni, con la possibilità di iscriversi a una singola ora dei corsi senza continuità. Viene mostrato anche l'orario
					del corso: le ore a cui si è iscritti sono blu, le altre grigie.
				</p>
				<h5 class="primary-text light">Orario</h5>
				<p>
					Nell'orario vengono visualizzate tutte le ore a cui sei iscritto. Alcune ore sono sottolineate: se fai click, puoi
					scegliere a quale corso disponibile in quell'ora partecipare (senza continuità, vedi più avanti).
				</p>
				<h5 class="primary-text light">Corsi con continuità</h5>
				<p>
					L'iscrizione a un corso con continuità comporta la partecipazione a tutte le ore. Per questo motivo, quando anche una
					sola ora di un corso con continuità si sovrappone a un'ora di un corso con continuità a cui sei iscritti, il corso
					viene marcato come coincidente e ti viene vietata l'iscrizione. Cliccando sul badge "Coincide" puoi avere più informazioni.
					Sono marcati come coincidenti anche alcuni corsi che offrono più "repliche", quando si può partecipare solo ad una.
				</p>
				<h5 class="primary-text light">Corsi senza continuità</h5>
				<p>
					L'iscrizione a un corso con continuità non comporta la partecipazione a tutte le ore. Per questo motivo, i corsi
					senza continuità vengono sovrascritti dai corsi senza continuità
				</p>
		</div>
		<div class="modal-footer">
			<a href="#!" class=" modal-action modal-close waves-effect waves-red accent-text btn-flat">CHIUDI</a>
		</div>
	</div>


	<div id="modal-scrivi" class="modal modal-fixed-footer">
			<form id="form-email">
		<div class="modal-content">
			<h3 class="primary-text thin center">Scrivici</h3>

				<div class="container">
				<p>
					Puoi scriverci usando il form qui in basso.<br/>
				</p>
				<p class="red-text">
					Non ancora funzionante!
				</p>
				<div class="input-field valing-wrapper">
					 <i class="material-icons prefix valign">account_circle</i>
					<input id="recapito" name="recapito" type="text" required class="validate valign">
					<label for="recapito" class="valign">Nome e cognome</label>
				</div>
				<div class="input-field valing-wrapper">
					 <i class="material-icons prefix valign">email</i>
					<input id="email" name="recapito" type="text" required class="validate valign">
					<label for="email" class="valign">Email</label>
				</div>
				<div class="input-field col s6">
          <i class="material-icons prefix">mode_edit</i>
          <textarea id="testoEmail" required class="materialize-textarea"></textarea>
          <label for="testoEmail">Messaggio</label>
        </div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="waves-effect disabled waves-light red white-text btn">INVIA</a>
			<a href="#!" class=" modal-action modal-close waves-effect waves-red accent-text btn-flat">CHIUDI</a>
		</div>
	</form>
	</div>

	<div id="modal-scegliquale" class="modal modal-fixed-footer">
		<div class="modal-content">

		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-red accent-text btn-flat">CHIUDI</a>
		</div>
	</div>

	<div id="miao" class="modal">
		<div class="modal-content">
				<img src = "img/easteregg.jpg" alt="Flavio">
		</div>
	</div>
	<div id="miaomiao" class="modal" >
		<div class="modal-content">
				<img src = "img/easteregg2.jpg" alt="Boss">
		</div>
	</div>
	<?php
	if($dettagliUtente["passwordOriginale"]){
		?>
		<a class="modal-primoAccesso-trigger" href="modal-primoAccesso">

		</a>


		<div id="modal-primoAccesso" class="modal">
			<div class="modal-content">
				<h1 class="primary-text thin center">Benvenuto!</h1>
				<h4 class="primary-text thin center">Cambia la tua password!</h4>
				<div class="container">
					<div class="input-field col s4">
						<i class="material-icons prefix">vpn_key</i>
						<input id="cane" type="password" class="validate" required>
						<label for="cane">Nuova password</label>
					</div>
					<div class="input-field col s4">
						<i class="material-icons prefix">vpn_key</i>
						<input id="ripeti" type="password" class="validate" required>
						<label for="ripeti">Ripeti password</label>
					</div>
				</div>
			</div>
			<div class="center">
				<a id="cambiaPassword" class="center waves-effect waves-light red white-text btn-large">CONTINUA</a>
			</div>
		</div>
		<?php
	}
	?>
	<!--  Scripts-->
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/materialize.js"></script>
	<script src="js/jquery-ui.min.js"></script>
	<script src="js/jquery.cookie.js"></script>
	<script src="js/userhome.js"></script>
	<script src="js/init.js"></script>
	<script src="js/jquery.animatedscroll.min.js"></script>
	<!-- material-scrolltop button -->
	<button class="material-scrolltop" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

	<!-- material-scrolltop plugin -->
	<script src="js/material-scrolltop.js"></script>

	<!-- Initialize material-scrolltop with (minimal) -->
	<script>
		$('body').materialScrollTop();
	</script>
		<?php
			if(($dettagliUtente["primoAccesso"])&&!($dettagliUtente["passwordOriginale"])){
				?><script>$("#modal-help").modal("open");</script><?php
			}
			if($dettagliUtente["primoAccesso"]){
				$result = $db->query("UPDATE utenti SET primoAccesso = '0' where id = '$utente'");
			}
		?>
</body>
</html>
