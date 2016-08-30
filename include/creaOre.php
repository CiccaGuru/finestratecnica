<?php
include_once("config.php");

$ore=$_POST["ore"];
global $_CONFIG;
$giorni="<option value='' disabled selected>Scegli giorno</option>";
$ore_elenco="<option value='' disabled selected>Scegli ora</option>";
foreach($_CONFIG['giorni'] as $num=>$nome){
		$giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
	$ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
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
		<div class="input-field col s2 valign">
			<input id="aulaOra<?php echo $i;?>" type="text" class="validate" required/>
			<label for="aulaOra<?php echo $i;?>">Aula</label>
		</div>
		<div class="input-field col s1 valign">
			<input id="maxIscrittiOra<?php echo $i;?>" type="text" class="validate" required/>
			<label for="maxIscrittiOra<?php echo $i;?>">Alunni</label>
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
