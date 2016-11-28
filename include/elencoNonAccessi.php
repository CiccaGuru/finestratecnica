<?php
include dirname(__FILE__).'/funzioni.php';
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
$idLezione = $_POST["lezione"];
$db = database_connect();

$result = $db->query("SELECT tab.username, tab.cognome, tab.nome from (SELECT * from utenti where level = '0') as tab WHERE (SELECT COUNT(*) from iscrizioni where iscrizioni.idUtente = tab.id and iscrizioni.partecipa = '1') = 0 ORDER by cognome, nome ASC");

$codice = "<h2>Elenco alunni non iscritti a nessuna ora</h2>";
$codice .= "<table><tr>
<td>
<b>Cognome</b>
</td>
<td>
<b>Nome</b>
</td>
<td>
<b>Username</b>
</td>
</tr>";
while($dettagliUtente = $result->fetch_assoc()){
  $codice .= "<tr><td>".$dettagliUtente["cognome"]."
  </td> <td>
  ".$dettagliUtente["nome"]."
  </td><td>
  ".$dettagliUtente["username"]."
  </td></tr>";
}

$codice .= "</table>";
$mpdf = new mPDF('utf-8' , "A4");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
$mpdf->WriteHTML($codice);
$fileName = "elencoNonIscritti";
$mpdf->Output("$fileName.pdf", "F");
rename($fileName.".pdf", "tmp/".$fileName.".pdf");
echo "tmp/".$fileName.".pdf";
}?>
