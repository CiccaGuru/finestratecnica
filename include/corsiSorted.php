<?php
include 'funzioni.php';
include("../mpdf60/mpdf.php");
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
$ordine = $_POST["ordine"];
switch ($ordine) {
  case '1':  // DOCENTE
      $ordinamento = " utenti.cognome, utenti.nome, lezioni.ora asc";
      $stringa = "Docente";
      break;
  case '2':  // AULA
      $ordinamento =   " aule.nomeAula, lezioni.ora, utenti.cognome, utenti.nome asc";
      $stringa = "Aula";
      break;
  case '3':  // ORA
      $ordinamento = " lezioni.ora, utenti.cognome, utenti.nome asc ";
      $stringa = "Ora";
      break;
  case '4':  // TITOLO
      $ordinamento = " corsi.titolo, lezioni.ora, utenti.cognome, utenti.nome asc";
      $stringa = "Titolo";
      break;
}

if (file_exists('./tmp/elencoCorsiPer'.$stringa)) {
  unlink('./tmp/elencoCorsiPer'.$stringa);
}
if(!file_exists('./tmp')){
  mkdir('./tmp', 0777, true);
  chmod("./tmp", 0777);
}

$result = $db->query("SELECT  corsi.titolo as titoloCorso,
                              corsi.descrizione as descrizioneCorso,
                              utenti.nome as nomeProf,
                              utenti.cognome as cognomeProf,
                              lezioni.titolo as titoloLezione,
                              lezioni.ora as ora , aule.nomeAula as aula, lezioni.id as id,
                              aule.maxStudenti as maxStudenti,
                              corsi.tipo as tipo,
                              corsi.continuita as continuita,
                              (SELECT COUNT(*) from iscrizioni where idLezione = lezioni.id and partecipa = '1') as conta
                      from    utenti, lezioni, corsi, aule, corsi_docenti
                      where   utenti.id = corsi_docenti.idDocente and
                              corsi.id = corsi_docenti.idCorso and
                              lezioni.idAula = aule.id and
                              corsi.id = lezioni.idCorso
                              order by $ordinamento ") or die($db->error);

$code .= "
<style>td, th{border:1px solid; padding: 12px 10px;}</style>
<h3 style='text-align:center; margin-bottom:0px;'>L. S. \"G. Galilei\" - \"Finestra tecnica\" </h3>
<h1 style='text-align:center;margin-bottom: 5px; margin-top:15px;'>Elenco dei Corsi</h1>
<h4 style='text-align:center;margin-bottom: 5px;'>Ordinati per aula</h4>";
$code .= "<table style='border-collapse:collapse; margin-top:20px;'><tbody><tr>
  <td style='text-align:center;'><b>Docente </b></td>
  <td style='text-align:center;'><b>Titolo</b>  </td>
  <td style='text-align:center;'><b>Descrizione</b>  </td>
  <td style='text-align:center;'><b>Iscr.</b>  </td>
  <td style='text-align:center;'><b>Ora</b>  </td>
  <td style='text-align:center;'><b>Aula</b>  </td>
</tr>";
$i = 0;
while($dett = $result->fetch_assoc()){

                $code .= "<tr>
                          <td style='text-align:center;'><b>".addslashes(replace($dett["nomeProf"][0].". ".$dett["cognomeProf"]))."</b></td>
                          <td style='text-align:center;'>".addslashes($dett["titoloCorso"])."</td>
                          <td>".addslashes(replace($dett["descrizioneCorso"]))."</td><td style='text-align:center;'>".addslashes($dett["conta"])."</td>
                          <td style='text-align:center;'>".getStringaOra(addslashes($dett["ora"]))."</td>
                          <td style='text-align:center;'>".addslashes($dett["aula"])."</td></tr>";
}
  $code .= "</tbody></table>";
$mpdf = new mPDF('utf-8' , "A4-L");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
$mpdf->SetDisplayMode('fullpage');
$mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
//echo $code;
$mpdf->WriteHTML($code);
$fileName = "ElencoCorsiPer".$stringa;
$mpdf->Output("./tmp/$fileName.pdf", "F");
echo "SUCCESS";
}
?>
