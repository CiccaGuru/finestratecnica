<?php

include("../mpdf60/mpdf.php");
include 'funzioni.php';
include 'config.php';
global $_CONFIG;

$idLezione = $_POST["id"];

$utente = check_login();
$idCorso = $_POST["idCorso"];
$db = database_connect();
if($utente==-1){
  die("LOGINPROBLEM");
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
    die("LOGINPROBLEM");
  if($user_level == 2)
    die('LOGINPROBLEM');

$result = $db->query("SELECT nome, cognome from utenti where id = '$utente'") or die($db->error);
$dettagliProfessore = $result->fetch_assoc();
$result = $db->query("SELECT  corsi.titolo as titoloCorso,
                              corsi.id as idCorso,
                              corsi.descrizione as descrizione,
                              lezioni.ora as lezioniOra
                      FROM corsi, lezioni
                      WHERE lezioni.idCorso = corsi.id AND lezioni.id = '$idLezione'")
          or die($db->error);
$dettagliLezione = $result->fetch_assoc();
$result = $db->query("SELECT  utenti.nome,
                              utenti.cognome,
                              utenti.classe
                      FROM    utenti, iscrizioni
                      WHERE iscrizioni.partecipa = '1' AND
                            iscrizioni.idLezione = '$idLezione' AND
                            iscrizioni.idUtente = utenti.id
                      ORDER BY utenti.cognome, utenti.nome")
          or die($db->error);
$code = "<style>td, th{border:1px solid; padding: 5px 15px;}</style>";
$code .= "<h1 style='text-align:center;'>".$dettagliLezione["titoloCorso"]."</h1>";
$code .= "<h3 style='text-align:center;'>".$dettagliProfessore["nome"][0].". ".$dettagliProfessore["cognome"]." - ".getStringaOra($dettagliLezione["lezioniOra"])."</h3>";
$code .= "<br/><table style='border:0px;'>
  <tr>
    <td style='border:none;'>
        <span style='font-weight:bold;'>DESCRIZIONE:</span>
    </td>
    <td style='border:none;'>
    ".$dettagliLezione["descrizione"]."
    </td>
  </tr>
  <tr><td style='font-weight:bold; border:none;'>
      Classi
  </td>

  <td style='border:none;'>
  ";
    $classi = "";
    $resultClassi = $db->query("SELECT classe from corsi_classi where idCorso = '".$dettagliLezione["idCorso"]."' GROUP BY classe");
    while($classe = $resultClassi->fetch_assoc()){
      $classi .= $classe["classe"]." ";
    }
    $code.= $classi;

  $code .="</tr></td>
</table><br/><br/>";

$code .= "<table style='border:1px solid; border-collapse:collapse;'>";
$code .= "<tr style='font-weight:bold;'>
  <td style='width:40%;'>
    <b>Cognome</b>
  </td>
  <td style='width:40%;'>
      <b>Nome</b>
  </td>
  <td style='width:20%;'>
       <b>Classe</b>
  </td>
</thead>
<tbody>

";
while($studente = $result->fetch_assoc() ){
  $code .= "<tr>
      <td>
      ".$studente["cognome"]."
      </td>
      <td>
      ".$studente["nome"]."
      </td>
      <td>
      ".$studente["classe"]."
      </td>
  </tr>";
}

$code .= "</tbody></table>";
  //A4 paper
  $mpdf = new mPDF('utf-8' , "A4");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
  $mpdf->SetDisplayMode('fullpage');
  $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list

  $mpdf->WriteHTML($code);
  $fileName = $dettagliProfessore["nome"]."_".$dettagliProfessore["cognome"]."_".$idLezione;
  $mpdf->Output("$fileName.pdf", "F");
  rename($fileName.".pdf", "tmp/".$fileName.".pdf");
  echo "tmp/".$fileName.".pdf";
}
?>
