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

$idCorso = $_POST["id"];
$keyword = $_POST["keyword"];

if($keyword == ""){
	$result = $db->query("SELECT id, classe, sezione from sezioni") or die($db->error);
}
else{
	$result = $db->query("SELECT id, classe, sezione from sezioni where CONCAT_WS('', classe, sezione) LIKE '%$keyword%'") or die($db->error);
}

if($result->num_rows == 0){
	?>
		<li class="collection-item italic">Nessun risultato</li>
	<?php
}
else{
while($sezione=$result->fetch_assoc()){
	$resultA = $db->query("SELECT id from corsi_obbligatori where idCorso = $idCorso and idClasse=".$sezione["id"]);
	if($resultA->num_rows == 0){
?>
  <li class="collection-item hover row valign-wrapper waves-effect" onclick="aggiungiCorsoObbligatorio(<?php echo $idCorso.", ".$sezione["id"];?>)">
		<div class="col s2 valign red-text">
				<i class="material-icons">add</i>
		</div>
		<div class="col valign s10">
			<?php echo $sezione["classe"].$sezione["sezione"]; ?>
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
			<?php echo $sezione["classe"].$sezione["sezione"]; ?>
		</div>
  </li>
	<?php
}
}
}
?>
</ul>
