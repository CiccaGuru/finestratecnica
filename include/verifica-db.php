<?php
$db_host = $_POST["db-host"];
$db_user = $_POST["db-user"];
$db_password = $_POST["db-password"];
$db_name = $_POST["db-name"];

$db = new mysqli($db_host, $db_user, $db_password);
if($db->connect_errno){
  echo $db->connect_errno;
}
else{

$result = $db->query("DROP DATABASE IF EXISTS $db_name") or die("Errore 1!".$db->error);
$result = $db->query("CREATE DATABASE $db_name CHARACTER SET = 'utf8'") or die("Errore 2!".$db->error);
$f=fopen("config.php","w");
$database_inf='<?php
      $_CONFIG[\'db_host\'] = "'.$db_host.'";
      $_CONFIG[\'db_user\'] = "'.$db_user.'";
      $_CONFIG[\'db_password\'] =  "'.$db_password.'";
      $_CONFIG[\'db_name\'] = "'.$db_name.'";
     ?>';
if (fwrite($f,$database_inf)>0){
   fclose($f);
}
echo "SUCCESS";
}

?>
