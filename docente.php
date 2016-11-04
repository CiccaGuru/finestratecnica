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
	if($user_level == 2)
	header('Location: admin.php');
}

$db = database_connect();
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Settimana tecnica</title>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	<link href="css/docente.css" type="text/css" rel="stylesheet" media="screen"/>
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
		<nav id="intestaz" class="blue lighten-1">
			<div class="nav-wrapper">
				<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;">
					<?php
							$result = $db->query("SELECT 	username, nome, cognome, primoAccesso, passwordOriginale
																		FROM 		utenti
																		WHERE 	id = '$utente'");
							$dettagliUtente = $result->fetch_assoc();
							echo mb_strtoupper($dettagliUtente["nome"]." ".$dettagliUtente["cognome"]." (".$dettagliUtente["username"].")");
					?>
				</a>
				<a href="#" class="brand-logo center light condensed">Settimana tecnica</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="include/logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
				</ul>
			</div>
		</nav>
	</div>

	<div class="fixed-action-btn">
		<a class="btn-floating btn-large red">
			<i class="large material-icons">more_horiz</i>
		</a>
		<ul>
			<li><a class="btn-floating waves-effect waves-light yellow darken-1 tooltipped modal-trigger" href="#modal-scrivi" data-position="left" data-delay="50" data-tooltip="Scrivici"><i class="material-icons">mode_edit</i></a></li>
			<li><a class="btn-floating waves-effect waves-light green tooltipped" href="stampabileDocente.php" data-position="left" data-delay="50" data-tooltip="Stampa orario"><i class="material-icons">print</i></a></li>
			<li><a class="btn-floating waves-effect waves-light blue tooltipped modal-trigger" href="#modal-helpDo" data-position="left" data-delay="50" data-tooltip="Aiuto"><i class="material-icons">help</i></a></li>
		</ul>
	</div>

	<div id="cont">

		<div class="row">
			<div class="col l7 center" id="elencoCorsiContainer">
				<div class="row">
					<div class="col s6 l4 offset-l1">
						<a id="btn-CreaCorso" class="condensed fake-button letter-spacing-1 waves-effect waves-primary primary-text" href="creaCorsoDocente.php">
						<i class="material-icons">group_add</i><br/>
						CREA CORSO
					</a>
					</div>
					<div class="col s6 m6 l4 offset-l2">
						<a id="btn-eliminaCorso" class="condensed fake-button letter-spacing-1 waves-effect waves-accent accent-text">
						<i class="material-icons">delete_forever</i><br/>
						ELIMINA CORSO
					</a>
					</div>
				</div>
				<div id="elencoCorsiDocente" class="left-align">
					<div class="center">
						<ul class="collapsible popout" data-collapsible="accordion">
							<?php

							$result = $db->query("SELECT corsi.id,
																					 corsi.titolo,
																					 corsi.descrizione,
																					 corsi.continuita,
																					 corsi.tipo
																		from corsi, corsi_docenti
																		where corsi_docenti.idCorso = corsi.id and
																				  corsi_docenti.idDocente= '$utente'");
							while($dettagliCorso = $result->fetch_assoc()){
								?>

								<li class="collapsibleCorso" data-idcorso = "<?php echo $dettagliCorso["id"]; ?>" id="collapsibleCorso<?php echo $dettagliCorso["id"]; ?>" >
									<div class="collapsible-header" onclick="caricaInfo(<?php echo $dettagliCorso["id"]?>)">
										<div class="row">
												<div class="col s2 titoloCorso bold truncate condensed">
													<?php echo $dettagliCorso["titolo"];?>
												</div>
												<div class="col s6 truncate descrizioneCorso">
													<?php echo $dettagliCorso["descrizione"]?>
												</div>
												<div class="col s2 truncate condensed bold">
													<?php
														if($dettagliCorso["tipo"]=='0'){
															echo "Recupero";
														}
														else{
															echo "Approf.";
														}
												?></div>
												<div class="col s2 truncate condensed bold">
													<?php	if($dettagliCorso["continuita"]=='0'){
															echo " Senza cont.";
														}
														else{
															echo "Con cont.";
														}
													 ?>
												</div>
										</div>
									</div>

									<div class="collapsible-body">
										<div class="center">

										<div class="preloader-wrapper center big active">
											<div class="spinner-layer center spinner-red-only">
												<div class="circle-clipper right">
													<div class="circle"></div>
												</div>
											</div>
										</div>
									</div>
									</div>
								</li>

								<?php
							}
							?>

						</ul>

					</div>
				</div>
			</div>

			<div class="col l5">
				<div id="card-orario-gen" class="card">
					<div class="card-content">
						<table id="orario" class="centered bordered">
							<thead>
								<th></th>
								<?php
								for($i =1; $i<=$_CONFIG["numero_giorni"]; $i++){
									echo '<th class="condensed">'.strtoupper($_CONFIG["giorni"][$i]).'</th>';
								}
							?>
						</thead>
						<tbody>
							<?php
								$colori = array();
								$r = 0;
								for($i = 1; $i<=$_CONFIG["ore_per_giorno"]; $i++){
									echo '<tr><td class="condensed">'.$i."</td>";
									for($j=1; $j<=$_CONFIG["numero_giorni"];$j++){
										$num = ($j-1)*$_CONFIG["ore_per_giorno"]+$i;
										$result = $db->query("SELECT  lezioni.idCorso as idCorso,
																									corsi.continuita as continuita,
																									corsi.titolo as titolo,
																									aule.nomeAula as aula
																					FROM    corsi, lezioni, aule, corsi_docenti
																					WHERE   corsi_docenti.idDocente = '$utente' AND
																									corsi.id = corsi_docenti.idCorso AND
																									lezioni.idAula = aule.id AND
																									lezioni.idCorso = corsi.id AND
																									lezioni.ora = '$num'")
															or die('ERRORE: ' . $db->error);
										if($result->num_rows>0){
										while($lezione = $result->fetch_assoc()){
												$classiAgg = "";
												$nomeCorso = $lezione["titolo"];
												$aula =  $lezione["aula"];
												if(!(in_array($lezione["idCorso"], $colori))){
													$colori[$r]=$lezione["idCorso"];
													$bgcolor = $_CONFIG["colori"][$r];
													$fgcolor = $_CONFIG["colore-testo"][$r];
													$r++;
												}
												else{
														$index = array_search($lezione["idCorso"], $colori);
														$bgcolor = $_CONFIG["colori"][$index];
														$fgcolor = $_CONFIG["colore-testo"][$index];
												}
												echo '<td class="orario-cell-normal condensed" style="background-color: '.$bgcolor.'; color: '.$fgcolor.';" onclick="$(\'#collapsible'.$iscrizione["idCorso"].'\').animatedScroll({easing: \'easeOutQuad\'});">'.$nomeCorso.'<span>Aula '.$aula.'</span></td>';
											}
										}
										else{
											echo "<td></td>";
										}

									}
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="modal-continuita" class="modal">
		<div class="modal-content">

		</div>
		<div class="modal-footer">
			<a href="#!" class=" modal-action modal-close waves-effect waves-red red-text btn-flat">CHIUDI</a>
		</div>
	</div>

	<div id="modal-helpDo" class="modal modal-fixed-footer">
		<div class="modal-content">
			<h4 class="center light-blue-text light">Guida all'uso</h4>
			<p>
					Questo sito Le consente di visualizzare i corsi da Lei attivati durante la <span class="italic">Finestra tecnica.</span>
					L'interfaccia è composta da due colonne: a sinistra trova l'elenco dei corsi, a destra il Suo orario.
			</p>
			<h5 class="light-blue-text light">Elenco dei corsi</h4>
				<p>
					I corsi sono visualizzati in elenco, uno per riga, portando le indicazioni del <b>titolo</b>, della <b>descrizione</b>
					della <b>tipologia</b> e delle <b>classi</b> a cui è rivolto.
				</p>
				<p>
					Facendo click, si apre la visualizzazione dei dettagli. Sono riportati in tabella tutte le lezioni del corso,
					con la possibilità di visualizzare un elenco degli studenti iscritti ad ogni ora.
				</p>
				<h5 class="light-blue-text light">Altre informazioni</h4>
					<p>
							Spostando il mouse sul pulsante rosso in basso a destra, compariranno dei pulsanti aggiuntivi per stampare il
							Suo orario con le indicazioni dei corsi, inviarci una mail (funzione non ancora disponibile) e visualizzare nuovamente queste
							informazioni.
					</p>

					<p class="bold center">
						Per ulteriori informazioni, domande o chiarimenti, scriva un'email a robyciccarelli.rc@gmail.it o a quattrocchifilippo@gmail.com
					</p>
		</div>
		<div class="modal-footer">
			<a href="#!" class=" modal-action modal-close waves-effect waves-red red-text btn-flat">CHIUDI</a>
		</div>
	</div>
	<div id="modal-scrivi" class="modal modal-fixed-footer" >
			<form id="form-email">
		<div class="modal-content">
			<h3 class="light-blue-text thin center">Ci scriva!</h3>

				<div class="container">
				<p>
					Puoò scriverci usando il form qui in basso.<br/>
				</p>
				<p class="red-text">
					Non ancora funzionante!
				</p>
				<div class="input-field valing-wrapper all-width">
					 <i class="material-icons prefix valign">account_circle</i>
					<input id="recapito" name="recapito" type="text" required class="validate valign">
					<label for="recapito" class="valign">Nome e cognome</label>
				</div>
				<div class="input-field valing-wrapper all-width">
					 <i class="material-icons prefix valign">email</i>
					<input id="email" name="recapito" type="text" required class="validate valign">
					<label for="email" class="valign">Email</label>
				</div>
				<div class="input-field col s6 all-width">
          <i class="material-icons prefix">mode_edit</i>
          <textarea id="testoEmail" required class="materialize-textarea"></textarea>
          <label for="testoEmail">Messaggio</label>
        </div>
			</div>
		</div>
		<div class="modal-footer">
			<a type="submit" class="waves-effect disabled waves-light red white-text btn">INVIA</a>
			<a href="#!" class=" modal-action modal-close waves-effect waves-red red-text btn-flat">CHIUDI</a>
		</div>
	</form>
	</div>
	<?php
	if($dettagliUtente["passwordOriginale"]){
		?>
		<div class="modal-primoAccesso-trigger" href="modal-primoAccesso">

		</div>


		<div id="modal-primoAccesso" class="modal">
			<div class="modal-content">
				<h1 class="light-blue-text thin center">Benvenuto!</h1>
				<h4 class="light-blue-text thin center">Cambi la sua password!</h4>
				<div class="container">
					<div class="input-field col s4">
						<i class="material-icons prefix">vpn_key</i>
						<input id="cane" type="password" class="validate" required>
						<label for="cane">Nuova password</label>
					</div>
					<div class="input-field col s4">
						<i class="material-icons prefix">vpn_key</i>
						<input id="ripeti" type="password" class="validate" required>
						<label for="ripeti">Ripets password</label>
					</div>
				</div>
			</div>
			<div class="center">
				<a href="#!" id="cambiaPassword" class="center waves-effect waves-light red white-text btn-large">CONTINUA</a>
			</div>
		</div>
		<?php
	}
	?>

<!--  Scripts-->
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/docente.js"></script>
<script src="js/init.js"></script>
<script src="js/jquery.animatedscroll.min.js"></script>
<!-- material-scrolltop button -->
<button class="material-scrolltop" type="button">
	<i class="material-icons white-text">keyboard_arrow_up</i>
</button>

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
