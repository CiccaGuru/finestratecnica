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
					<li><a href="docente.php" class="waves-effect waves-light condensed"><i class="material-icons left">home</i>HOME</a></li>
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

	<div class="container">
		<h3 class="accent-text condensed light center">Crea un nuovo corso</h3>
		<div class="card" id="inserisciCorso">
      <div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <div class="row">
          <div class="input-field col s5 condensed">
            <input id="titolo" type="text" class="validate">
            <label for="titolo">Titolo</label>
          </div>
          <div class="input-field col s7 condensed">
            <input id="descriz" type="text" class="validate">
            <label for="descriz">Descrizione</label>
          </div>
        </div>
        <div class="row valign-wrapper">
					<div class="input-field col s4 condensed">
						<select id="selezionaClassi" multiple>
							<option value="" disabled selected>Scegli classi</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
						<label>Classi</label>
					</div>
          <div class="col s3 offset-s2 condensed">
            <p>
              <input name="tipo" value="0" type="radio" id="recupero" checked/>
              <label class="black-text" for="recupero">Recupero</label>
            </p>
            <p>
              <input name="tipo" value="1" type="radio" id="approfondimento" />
              <label class="black-text" for="approfondimento">Approfondimento</label>
            </p>
          </div>
          <div class="col s3  condensed">
            <p>
              <input name="continuita" value="1" type="radio" id="con_continuita" checked/>
              <label class="black-text" for="con_continuita">Con continuità</label>
            </p>
            <p>
              <input name="continuita" value="0" type="radio" id="senza_continuita" />
              <label class="black-text" for="senza_continuita">Senza continuità</label>
            </p>
          </div>
    	</div>
    </div>
	</div>
	<h5 class="center condensed accent-text" style="margin-bottom:1em; margin-top:2em;">Fare click su una cella dell'orario per aggiungere l'ora corrispondente al corso</h5>
</div>
<div class="container" style="width:95%">
	<div class="row">
		<div class="col s5">
			<div class="card" id="cardOrarioAggiungi">
				<div class="card-content">
					<table id="orarioAggiungi" class="centered bordered">
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
									$result = $db->query("SELECT  lezioni.idCorso as idCorso
																				FROM    corsi, lezioni, aule, corsi_docenti
																				WHERE   corsi_docenti.idDocente = '$utente' AND
																								corsi.id = corsi_docenti.idCorso AND
																								lezioni.idAula = aule.id AND
																								lezioni.idCorso = corsi.id AND
																								lezioni.ora = '$num'")
														or die('ERRORE: ' . $db->error);
									if($result->num_rows>0){
									while($lezione = $result->fetch_assoc()){
											echo '<td class="condensed grey"></td>';
										}
									}
									else{
										?> <td class="aggiungiOraCella free" data-ora="<?php echo $num;?>"></td> <?php
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
		<div class="col s7">
			<div class="card" id="elencoOreAggiungere">
				<div class="card-content">
					<span class="italic">Selezionare delle ore nella tabella orario a fianco per iniziare.</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col s6 offset-s3 m4 offset-m4 l2 offset-l5">
	<a id="btn-SalvaCorso" class="condensed fake-button letter-spacing-1 waves-effect waves-accent accent-text">
	<i class="material-icons">save</i><br/>
	SALVA
</a>
</div>
</div>
</div>
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
$(window).on('beforeunload', function(){
	     return 'Potrebbero esserci modifiche non salvate. Proseguire lo stesso?';
});

</script>

<?php
if(($dettagliUtente["primoAccesso"])&&!($dettagliUtente["passwordOriginale"])){
	?><script>$("#modal-help").modal("open");</script><?php
}
if($dettagliUtente["primoAccesso"]){
	$result = $db->query("UPDATE utenti SET primoAccesso = '0' where id = '$utente'");
}
?>

<div id="wait" class="center-align">
	<div id="contenuto-wait" class="center-align" style="position:relative; top:15%;">
		<i class="material-icons green-text light" style="font-size:1500%;">check_circle</i><br/>
		<div class="white-text condensed light" style="font-size:200%; margin-bottom:1em;">Il corso è stato aggiunto con successo.</div>
		<a href="docente.php" class='waves-effect waves-light btn-large primary condensed'>Continua</a>"
	</div>
</div>

</body>
</html>
