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
<ul class="collection" id="collectionCorsiIncompatibili">
<?php

$idCorso = $_POST["id"];
$keyword = $_POST["keyword"];

if($keyword == ""){
	$result = $db->query("SELECT id, titolo from corsi where not id = $idCorso") or die($db->error);
}
else{
	$result = $db->query("SELECT id, titolo from corsi where (titolo LIKE '%$keyword%') AND not id = $idCorso") or die($db->error);
}

if($result->num_rows == 0){
	?>
		<li class="collection-item italic">Nessun risultato</li>
	<?php
}
else{
while($corso=$result->fetch_assoc()){
	$resultA = $db->query("SELECT id from corsi_incompatibili where idCorso1 = $idCorso AND idCorso2 = ".$corso["id"]);
	if($resultA->num_rows == 0){
?>
  <li class="collection-item hover row valign-wrapper waves-effect" onclick="aggiungiCorsoIncompatibile(<?php echo $idCorso.", ".$corso["id"];?>)">
		<div class="col s2 valign accent-text">
				<i class="material-icons">add</i>
		</div>
		<div class="col valign s10">
			<?php echo $corso["titolo"]; ?>
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
			<?php echo $corso["titolo"]; ?>
		</div>
  </li>
	<?php
}
}
}
?>
</ul>
