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

$result = $db->query("SELECT * from sezioni") or die($db->error);
while($sezione=$result->fetch_assoc()){
?>
  <li class="collection-item row valign-wrapper">
		<div class="col s1 accent-text">
				<i class="material-icons waves-effect waves-accent waves-circle" style="border-radius:50%;" onclick="eliminaClasse(<?php echo $sezione["id"]?>)">close</i>
		</div>
		<div class="col s1 bold condensed letter-spacing-1">
			ANNO:
		</div>
		<div class="col s2 condensed">
			<?php echo $sezione["classe"];?>
		</div>
		<div class="col s1 bold condensed letter-spacing-1">
			SEZIONE:
		</div>
		<div class="col s2 condensed">
			<?php echo $sezione["sezione"];?>
		</div>
		<div class="col s1 offset-s1 condensed bold letter-spacing-1">
			ALUNNI:
		</div>
		<div class="col s2 condensed">
				<?php
					$contaR = $db->query("SELECT COUNT(id) as conta from utenti where idClasse = '".$sezione["id"]."'") or die("E:".$db->error);
					$conta = $contaR->fetch_assoc();
					echo $conta["conta"];
				?>
		</div>
  </li>
<?php
}
?>
