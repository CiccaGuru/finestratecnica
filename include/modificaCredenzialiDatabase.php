<?php
$db_host = $_POST["host"];
$db_user = $_POST["user"];
$db_password = $_POST["password"];
$db_name = $_POST["name"];
if($db_host == ""){
  $db_host = $_CONFIG["db_host"];
}
if($db_user == ""){
  $db_host = $_CONFIG["db_user"];
}
if($db_password == ""){
  $db_host = $_CONFIG["db_password"];
}
if($db_name == ""){
  $db_host = $_CONFIG["db_name"];
}
$db = new mysqli($db_host, $db_user, $db_password);
if($db->connect_errno){
  echo $db->connect_errno;
}
else{
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
