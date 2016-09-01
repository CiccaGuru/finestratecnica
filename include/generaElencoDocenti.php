<?php
include "funzioni.php";
$db = database_connect();
$result = $db->query("SELECT id, nome, cognome FROM utenti WHERE level=1 order by cognome") or die('ERRORE: ' . $db->error);
 while ($row = $result->fetch_assoc()) {
   if(isset($_POST["idDocente"]) && ($_POST["idDocente"]==$row["id"])){
      echo '<option value="'.$row["id"].'" selected>'.$row["nome"][0].'. '.$row["cognome"].'</option>';
   }
   else{
     echo '<option value="'.$row["id"].'">'.$row["nome"][0].'. '.$row["cognome"].'</option>';
   }

}
if(isset($_POST["idDocente"]) && ($_POST["idDocente"]=="0"))
{
   echo '<option value="-1" selected>Docenti vari</option>';
}
else{
    echo '<option value="-1">Docenti vari</option>';
}
$db->close()
?>
