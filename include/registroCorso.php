<?php
include 'funzioni.php';
include("./mpdf/mpdf.php");
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
if (!file_exists('./tmp/registrini')) {
    mkdir('./tmp/registrini', 0777, true);
    chmod("./tmp/registrini", 0777);
}
else{
    recursiveRemoveDirectory("./tmp/registrini");
}
for($i=1;$i<=(getProp("numero_giorni")*getProp("ore_per_giorno"));$i++){
  $result = $db->query("SELECT id from lezioni where ora = '$i'") or die($db->error);
  while($idLez = $result->fetch_assoc()){
    generaRegistroOra($idLez["id"], $i);
  }
}
Zip("./tmp/registrini/", "./tmp/registrini.zip");
echo "SUCCESS";
}
?>
