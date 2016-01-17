<?php
include 'funzioni.php';
include("../mpdf60/mpdf.php");
global $_CONFIG;
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
$ora = $_POST["ora"];
$db = database_connect();

$result = $db->query("SELECT id from lezioni where ora = '$ora'") or die($db->error);
$file = array();
while($idLez = $result->fetch_assoc()){
  $res = generaRegistroOra($idLez["id"], $ora);
  $file[]= $res;
}

chdir("tmp/registrini");
$zip = new ZipArchive;
$download = getStringaOraBreve($ora).'.zip';
$zip->open($download, ZipArchive::CREATE);
foreach (glob("./".getStringaOraBreve($ora)."/*.pdf") as $file) { /* Add appropriate path to read content of zip */
   $zip->addFile($file);
}
$zip->close();
}
echo $download;
?>
