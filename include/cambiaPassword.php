<?php
include 'funzioni.php';
global $_CONFIG;
$utente = check_login();

if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 2)
    die("LOGINPROBLEM");

$db = database_connect();

$password = $_POST["cane"];
$result = $db->query("UPDATE utenti SET password=SHA1('".secure($password)."'), passwordOriginale='0' WHERE id='$utente'") or die($db->error);
echo "SUCCESS";
}
?>
