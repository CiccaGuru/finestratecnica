<?php
require_once('funzioni.php'); // Includes Login Script
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
<ul class="collection" id="collectionCorsiObbligatori">
<?php


$keyword = $_POST["keyword"];
$listaDocenti = $_POST["listaDocenti"];
if($keyword == ""){
	$result = $db->query("SELECT id, nome, cognome FROM utenti WHERE level=1 order by cognome") or die($db->error);
}
else{
	$result = $db->query("SELECT id, nome, cognome FROM utenti WHERE level=1 and (nome LIKE '%$keyword' or cognome LIKE '%$keyword' or CONCAT_WS(' ', nome, cognome) LIKE '%$keyword%' or CONCAT_WS(' ', cognome, nome) LIKE '%$keyword%') order by cognome") or die($db->error);
}


if($result->num_rows == 0){
	?>
		<li class="collection-item italic">Nessun risultato</li>
	<?php
}
else{
while ($docente = $result->fetch_assoc()) {
	$spunta = 0;
	if(in_array($docente["id"], $listaDocenti)){
			$spunta = 1;
	}
	
	if(!$spunta){
?>
  <li class="collection-item hover row valign-wrapper waves-effect" onclick="aggiungiScegliDocenti(<?php echo $docente["id"];?>, '<?php echo $docente["nome"][0].". ".$docente["cognome"];?>')">
		<div class="col s2 valign red-text">
				<i class="material-icons">add</i>
		</div>
		<div class="col valign s10">
			<?php echo $docente["nome"][0].". ".$docente["cognome"]; ?>
		</div>
  </li>
<?php
}
else{
	?>
	<li class="collection-item hover row valign-wrapper waves-effect">
		<div class="col s2 valign green-text">
				<i class="material-icons">check</i>
		</div>
		<div class="col valign s10">
				<?php echo $docente["nome"][0].". ".$docente["cognome"]; ?>
		</div>
  </li>
	<?php
}
}
}
$db->close()
?>
