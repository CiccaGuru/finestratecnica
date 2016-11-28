<?php if(!file_exists(dirname(__FILE__)."/config.php")){
  $config = fopen(dirname(__FILE__)."/config.php", "w")  or die("Non riesco a creare il file di configurazione!");
  fclose($config);
}?><h4 class="condensed primary-text" style="margin-top:0px;">Step 2: Controllo requisiti</h4>
<p>Per poter usare finestratecnica, il server deve soddisfare alcuni requisiti minimi. Se ci sono problemi,
  consulta la pagina wiki del canale Gihtub</p>
<div id="compat">
<div class="row">
  <div class="col s3 bold"></div>
  <div class="col s3 bold">In uso</div>
  <div class="col s3 bold">Richiesta</div>
  <div class="col s3"></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">Versione PHP:</div>
 <div class="col s3"><?php echo phpversion(); ?></div>
 <div class="col s3">5.4+</div>
 <div class="col s3"><?php echo (phpversion() >= '5.4') ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>'; ?></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">Session Auto Start:</div>
 <div class="col s3"><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></div>
 <div class="col s3">Off</div>
 <div class="col s3"><?php echo (!ini_get('session_auto_start')) ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>'; ?></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">MySQL:</div>
 <div class="col s3"><?php echo extension_loaded('mysqli') ? 'On' : 'Off'; ?></div>
 <div class="col s3">On</div>
 <div class="col s3"><?php echo extension_loaded('mysqli') ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>'; ?></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">Hash:</div>
 <div class="col s3"><?php echo extension_loaded('hash') ? 'On' : 'Off'; ?></div>
 <div class="col s3">On</div>
 <div class="col s3"><?php echo extension_loaded('hash') ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>';?></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">Sendmail:</div>
 <div class="col s3"><?php echo function_exists('mail') ? 'On' : 'Off'; ?></div>
 <div class="col s3">On</div>
 <div class="col s3"><?php echo function_exists('mail') ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>'; ?></div>
</div>
<div class="row">
 <div class="col s3 bold right-align">config.php</div>
 <div class="col s3"><?php echo is_writable('config.php') ? 'Scrivibile' : 'Scrivivile'; ?></div>
 <div class="col s3">Writable</div>
 <div class="col s3"><?php echo is_writable('config.php') ? '<i class="material-icons green-text">check_circle</i>':'<i class="material-icons red-text">error</i>'; ?></div>
</div>
 <div id="numCheck" style="display:none;"><?php echo intval((phpversion() >= '5.0') && is_writable('config.php') && function_exists('mail') && extension_loaded('hash') && extension_loaded('mysqli') && (!ini_get('session_auto_start')));?></div>
</div>
