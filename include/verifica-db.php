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
      $_CONFIG[\'numero_giorni\'] = 4;
      $_CONFIG[\'ore_per_giorno\'] = 6;
      $_CONFIG[\'soglia_minima\'] = 17;
      $_CONFIG[\'chiusura_iscrizioni\'] = 111111111111111111111;
      $_CONFIG[\'giorni\'] = array(1=>"Mercoledì",2=>"Giovedì",3=>"Venerdì",4=>"Sabato");
      $_CONFIG[\'num_colori\']=20;
      $_CONFIG[\'colori\'] = 	   array(	 "#03a9f4", "#F44336", "#FFEB3B", "#0d47a1", "#4CAF50",
      																	 "#FF5722", "#9C27B0", "#311b92", "#212121", "#795548",
      																	 "#607D8B", "#E91E63", "#00695c", "#01579b", "#1b5e20",
      																	 "#aeea00", "#FFFFFF");
      $_CONFIG["colore-testo"] =  array( "black", 	"white", "black", "white", "black",
      																	 "white", "white", "white", "white", "white",
      																	 "black", "black", "white", "white", "white",
      																	 "white","white","black", "white","white");
     ?>';
if (fwrite($f,$database_inf)>0){
   fclose($f);
}
echo "SUCCESS";
}

?>
