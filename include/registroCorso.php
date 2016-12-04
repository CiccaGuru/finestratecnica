<?php
include dirname(__FILE__).'/funzioni.php';
include dirname(__FILE__).'/mpdf/mpdf.php';
$utente = check_login();
if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
    die("LOGINPROBLEM");
  if($user_level == 1)
    die('LOGINPROBLEM');
$db = database_connect();
if (!file_exists( dirname(__FILE__).'/tmp/registrini')) {
    mkdir( dirname(__FILE__).'/tmp/registrini', 0777, true);
    chmod( dirname(__FILE__)."/tmp/registrini", 0777);
}
else{
    recursiveRemoveDirectory( dirname(__FILE__)."/tmp/registrini");
}
for($i=1;$i<=(getProp("numero_giorni")*getProp("ore_per_giorno"));$i++){
  $result = $db->query("SELECT id from lezioni where ora = '$i'") or die($db->error);
  while($idLez = $result->fetch_assoc()){
    generaRegistroOra($idLez["id"], $i);
  }
}
Zip( dirname(__FILE__)."/tmp/registrini/",  dirname(__FILE__)."/tmp/registrini.zip");
echo "SUCCESS";
}
?>
