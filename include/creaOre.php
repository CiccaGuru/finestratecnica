<?php
include dirname(__FILE__).'/funzioni.php';
$ore=$_POST["ore"];
$giorni="<option value='' disabled selected>Scegli giorno</option>";
$ore_elenco="<option value='' disabled selected>Scegli ora</option>";
$aule = "<option value='' disabled selected>Scegli aula</option>";
foreach(unserialize(getProp("giorni")) as $num=>$nome){
		$giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=getProp('ore_per_giorno');$j++){
	$ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}
$db=database_connect();
$resultAule = $db->query("SELECT * from aule") or die("Error: ".$db->error);

while($aula = $resultAule->fetch_assoc()){
	$aule .= '<option value="'.$aula["id"].'">Aula '.$aula["nomeAula"].', '.$aula["maxStudenti"].' alunni</option>';
}

for($i=0;$i<$ore;$i++){
?>
<li class="ora_da_inserire condensed">
	<div class="row valign-wrapper">
		<div class="col s1 valign bold" >Ora <?php echo ($i+1)?></div>
		<div class="input-field col s2">
			 <select id="selezionaGiorno<?php echo $i;?>">
			  <?php echo $giorni; ?>
			</select>
			<label>Giorno</label>
		</div>
		<div class="col s2">
			<div class="input-field">
				 <select id="selezionaOra<?php echo $i; ?>">
					<?php echo $ore_elenco; ?>
				</select>
				<label>Ora</label>
			</div>
		</div>
			<div class="input-field col s3">
				 <select id="selezionaAula<?php echo $i;?>">
				  <?php echo $aule; ?>
				</select>
				<label>Aula</label>
			</div>
		<div class="input-field col s4 valign">
			<input id="nomeOra<?php echo $i;?>" type="text" class="validate">
			<label for="nomeOra<?php echo $i;?>">Titolo lezione (fac.)</label>
		</div>
	</div>
</li>

<?php

}
?>
