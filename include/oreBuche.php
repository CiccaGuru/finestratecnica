<?php
include dirname(__FILE__).'/funzioni.php';
include dirname(__FILE__).'/mpdf/mpdf.php';
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
$db = database_connect();
$ora = 1;
if (file_exists('./tmp/orebuche')) {
    recursiveRemoveDirectory("./tmp/orebuche");
}
mkdir( dirname(__FILE__).'/tmp/orebuche', 0777, true);
chmod( dirname(__FILE__)."/tmp/orebuche", 0777);
while($ora <= getProp("numero_giorni")*getProp("ore_per_giorno")){

  $result = $db->query("SELECT utenti.nome, utenti.cognome
                            from utenti
                            where level = '0' and
                              (SELECT COUNT(*) from iscrizioni, lezioni
                                    where lezioni.id = iscrizioni.idLezione and
                                          iscrizioni.idUtente = utenti.id and
                                          lezioni.ora = '$ora'
                              ) = 0
                            ORDER by cognome, nome asc") or die($db->error);
  $file = array();
  $code = "
  <style>td, th{border:1px solid; padding: 5px 30px;}</style> <h3 style='text-align:center; margin-bottom:0px;'>L. S. \"G. Galilei\" - \"Finestra tecnica\"</h3><h1 style='text-align:center;margin-bottom: 5px; margin-top:15px;'>".getStringaOra($ora)."</h1>";
  $code .= "<table style='border-collapse:collapse; margin-top:20px;'><tbody><tr>
    <td style='width:230px;'><b>Cognome</b></td>
    <td style='width:230px;'><b>Nome</b>  </td>
    <td style='width:250px; text-align:center;'><b>Firma</b>  </td>
  </tr>";

    while($utente = $result->fetch_assoc()){
      $code .= "<tr>
                <td>".$utente["cognome"]."</td>
                <td>".$utente["nome"]."</td>
                <td></td>
              </tr>";
    }
    $code .= "</tbody></table>";

    $mpdf = new mPDF('utf-8' , "A4");// , '' , '' , 50 , 1 , 1 , 1 , 1 , 1);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
    $mpdf->WriteHTML($code);
    $fileName = getStringaOraBreve($ora);
    $mpdf->Output( dirname(__FILE__)."/tmp/orebuche/$fileName.pdf", "F");
    $ora ++;
}
Zip( dirname(__FILE__)."/tmp/orebuche/",  dirname(__FILE__)."/tmp/orebuche.zip");
echo "SUCCESS";
}
?>
