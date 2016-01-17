<?php
include_once("config.php");

$ore=$_POST["ore"];
global $_CONFIG;
$giorni="";
$ore_elenco="";
foreach($_CONFIG['giorni'] as $num=>$nome){
		$giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
	$ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}

for($i=0;$i<$ore;$i++){
echo '
<li class="ora_da_inserire">
	<div class="row valign-wrapper">
		<div class="col s1 valign bold" >Ora '.($i+1).'</div>
		<div class="input-field col s3 valign">
			<input id="nomeOra'.$i.'" type="text" class="validate">
			<label for="nomeOra'.$i.'">Titolo lezione (fac.)</label>
		</div>
		<div class="input-field col s1 valign">
			<input id="aulaOra'.$i.'" type="text" class="validate" required/>
			<label for="aulaOra'.$i.'">Aula</label>
		</div>
		<div class="input-field col s1 valign">
			<input id="maxIscrittiOra'.$i.'" type="text" class="validate" required/>
			<label for="maxIscrittiOra'.$i.'">Alunni</label>
		</div>
		<div class="input-field col s3">
			 <select id="selezionaGiorno'.$i.'">
				'.$giorni.'
			</select>
			<label>Giorno</label>
		</div>
		<div class="col s3">
			<div class="input-field">
				 <select id="selezionaOra'.$i.'">
					'.$ore_elenco.'
				</select>
				<label>Ora</label>
			</div>
		</div>
	</div>
</li>';

}
?>
