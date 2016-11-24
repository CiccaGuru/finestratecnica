<?php
include 'include/funzioni.php';
$utente = check_login();
if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 1)
  header('Location: docente.php');
  if($user_level == 2){
    $utente = $_POST["id"];
  }
}
$db = database_connect();
$result = $db->query("SELECT nome, cognome, username from utenti where id=$utente") or die('ERRORE: ' . $db->error);
$dettagliUtente = $result->fetch_assoc();
$numero_giorni = getProp("numero_giorni");
$ore_per_giorno = getProp("ore_per_giorno");
$giorni = unserialize(getProp("giorni"));
?>


<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Stampa orario</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.php" type="text/css" rel="stylesheet" media="screen"/>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" href="/img/favicons/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/img/favicons/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/img/favicons/manifest.json">
<link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#f44336">
<link rel="shortcut icon" href="/img/favicons/favicon.ico">
<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="msapplication-TileImage" content="/img/favicons/mstile-144x144.png">
<meta name="msapplication-config" content="/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#03a9f4">
</head>

<body style="background:white;">
<div class="container">
<h1 class="condensed primary-text center">Il tuo orario</h1>
<h3 class="condensed primary-text center"><?php echo $dettagliUtente["nome"]." ".$dettagliUtente["cognome"]." - ".$dettagliUtente["username"]?></h3>
<a class="right center-align btn-flat waves-effect waves-accent accent-text" href="javascript:window.print()" style="margin:1em;">Stampa orario</a>
 <style>
  tr, th, td{border:solid 1px black;}
  table{border-collapse: collapse;}
 </style>

 <style media="print">
    #orario tr, #orario td, #orario th{
      padding: 0.5em;
      text-align:center;
    }
    #orario tr:first-child{
      width:3%;
      height:100px;
    }
    #orario th:first-child{
      width:3%;
    }

    #orario tr{
      width:10%;
      height:100px;
    }
    #orario th{
      width:10%;
    }
 </style>

  <table id="orario" class="centered bordered">
    <thead>
      <th></th>
      <?php
      for($i =1; $i<=$numero_giorni; $i++){
        echo '<th>'.$giorni[$i].'</th>';
      }
    ?>
  </thead>
  <tbody>
    <?php
      $colori = array();
      $r = 0;
      for($i = 1; $i<=$ore_per_giorno; $i++){
        echo '<tr><td>'.$i."</td>";
        for($j=1; $j<=$numero_giorni;$j++){
          $num = ($j-1)*$ore_per_giorno+$i;

          $result = $db->query("SELECT  iscrizioni.idCorso as idCorso,
                                        iscrizioni.idLezione as idLezione,
                                        corsi.continuita as continuita,
                                        corsi.titolo as titolo,
                                        lezioni.idAula,
                                        aule.nomeAula as aula
                                FROM    iscrizioni, corsi, lezioni, aule
                                WHERE   iscrizioni.idUtente = '$utente'
                                        AND lezioni.ora = '$num'
                                        AND iscrizioni.partecipa = '1'
                                        AND corsi.id = iscrizioni.idCorso
                                        AND lezioni.id = iscrizioni.idLezione
                                        AND lezioni.idAula = aule.id") or die('ERRORE: ' . $db->error);
          if($result->num_rows > 0){
            while($iscrizione = $result->fetch_assoc()){
              $aula =  $iscrizione["aula"];//$lezione["aula"];
              if($resultContr->num_rows > 0){
                $classiAgg = "class='underline'";
              }

                echo '<td><b>'.$iscrizione["titolo"].'</b><span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';

            }
          }
          else{
            echo "<td></td>";
          }

        }
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

  <table style="margin-top:3em; margin-bottom:4em;">
    <thead>
      <th style="width:30%; padding:15px;">
        Titolo
      </th>
      <th style="width:20%; padding:15px;">
        Professore
      </th>
      <th style="width:50%; padding:15px;">
        Descrizione
      </th>
    </thead>
    <tbody>
      <?php
        $result = $db->query("SELECT  corsi.titolo as titolo,
                                      corsi.descrizione as descrizione,
                                      utenti.nome as nome,
                                      utenti.cognome as cognome,
                                      corsi.id as idCorso
                              FROM    corsi, utenti, iscrizioni, corsi_docenti
                              WHERE   iscrizioni.idCorso = corsi.id AND
                                      iscrizioni.partecipa = '1' AND
                                      iscrizioni.idUtente = '$utente' AND
                                      utenti.id = corsi_docenti.idDocente and
                                      corsi_docenti.idCorso = corsi.id
                              GROUP BY corsi.id") or die($db->error);
        while($corso = $result->fetch_assoc()){
          ?>
          <tr>
            <td style="padding:15px;">
              <b><?php echo $corso["titolo"];?></b>
            </td>
            <td style="padding:15px;">
              <?php
                $resultDoc = $db->query("SELECT id from corsi_docenti where idCorso = ".$corso["idCorso"]) or die($db->error);
                if($resultDoc->num_rows>1){
                  echo "<i>Docenti vari</i>";
                }else{
                  echo $corso["nome"][0].". ".$corso["cognome"];
                }?>
            </td>
            <td style="padding:15px;">
              <?php echo $corso["descrizione"];?>
            </td>
          </tr>
          <?php
        }
      ?>
    </tbody>
  </table>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>
</body>
</html>
