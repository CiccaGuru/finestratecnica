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

$result = $db->query("SELECT  corsi.titolo as titoloCorso,
                              corsi.descrizione as descrizioneCorso,
                              utenti.nome as nomeProf,
                              utenti.cognome as cognomeProf,
                              lezioni.titolo as titoloLezione,
                              lezioni.ora as ora , lezioni.aula as aula,
                              corsi.tipo as tipo,
                              corsi.continuita as continuita,
                              (SELECT COUNT(*) from iscrizioni where idLezione = lezioni.id and partecipa = '1') as conta
                      from    utenti, lezioni, corsi
                      where   utenti.id = corsi.idDocente and
                              corsi.id = lezioni.idCorso
                              order by utenti.cognome, utenti.nome, lezioni.ora asc") or die($db->error);

$code .= "
<style>td, th{border:1px solid; padding: 12px 10px;}</style>
<h3 style='text-align:center; margin-bottom:0px;'>L. S. \"G. Galilei\" - \"Finestra tecnica\"</h3>
<h1 style='text-align:center;margin-bottom: 5px; margin-top:15px;'>Elenco dei Corsi</h1>
<h4 style='text-align:center;margin-bottom: 5px;'>Ordinati per docente</h4>";
$code .= "<table style='border-collapse:collapse; margin-top:20px;'><tbody><tr>
  <td style='text-align:center;'><b>Docente </b></td>
  <td style='text-align:center;'><b>Titolo</b>  </td>
  <td style='text-align:center;'><b>Descrizione</b>  </td>
  <td style='text-align:center;'><b>Iscr.</b>  </td>
  <td style='text-align:center;'><b>Ora</b>  </td>
  <td style='text-align:center;'><b>Aula</b>  </td>
</tr>";
$i=0;
while($dett = $result->fetch_assoc()){

                $code .= "<tr>
                          <td style='text-align:center;'><b>".addslashes(replace($dett["nomeProf"][0].". ".$dett["cognomeProf"]))."</b></td>
                          <td style='text-align:center;'>".$dett["titoloCorso"]."</td>
                          <td>".addslashes(replace($dett["descrizioneCorso"]))."</td><td style='text-align:center;'>".$dett["conta"]."</td>
                          <td style='text-align:center;'>".getStringaOra($dett["ora"])."</td>
                          <td style='text-align:center;'>".$dett["aula"]."</td></tr>";


}
  $code .= "</tbody></table>";
$mpdf = new mPDF('utf-8' , "A4-L");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
//echo $code;
$mpdf->WriteHTML($code);

chdir("./tmp/");
$fileName = "corsi-per-docente";
$mpdf->Output("$fileName.pdf", "F");
//rename("$fileName.pdf", getStringaOraBreve($ora)."/".$fileName.".pdf");
echo $fileName.".pdf";
}
?>
