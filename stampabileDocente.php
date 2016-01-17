<?php
include 'include/funzioni.php';
global $_CONFIG;
$utente = check_login();
if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
  header('Location: userhome.php');
  if($user_level == 2){
    $utente = $_POST["id"];
  }
}
$db = database_connect();
$result = $db->query("SELECT nome, cognome, username from utenti where id=$utente") or die('ERRORE: ' . $db->error);
$dettagliUtente = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Stampa orario</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
</head>

<body style="background:white;">
<div class="container">
<h1 class="thin light-blue-text center">Il Suo orario</h1>
<h3 class="thin light-blue-text center"><?php echo $dettagliUtente["nome"]." ".$dettagliUtente["cognome"]." - ".$dettagliUtente["username"]?></h3>
<div class="center-align" style="margin:1em;"><a href="javascript:window.print()">Stampa orario</a></div>
 <style>
  tr, th, td{border:solid 1px black;}
  table{border-collapse: collapse;}
 </style>

 <style media="print">
    #orario tr, #orario td, #orario th{
      padding: 0.3em;
      text-align:center;
    }
    #orario tr:first-child{
      width:3%;
      height:60px;
    }
    #orario th:first-child{
      width:3%;
    }

    #orario tr{
      width:10%;
      height:60px;
    }
    #orario th{
      width:10%;
      padding:0.2em;
    }
 </style>

  <table id="orario" class="centered bordered">
    <thead>
      <th></th>
      <?php
      for($i =1; $i<=$_CONFIG["numero_giorni"]; $i++){
        echo '<th>'.$_CONFIG["giorni"][$i].'</th>';
      }
    ?>
  </thead>
  <tbody>
    <?php
      $r = 0;
      for($i = 1; $i<=$_CONFIG["ore_per_giorno"]; $i++){
        echo '<tr><td>'.$i."</td>";
        for($j=1; $j<=$_CONFIG["numero_giorni"];$j++){
          $num = ($j-1)*$_CONFIG["ore_per_giorno"]+$i;
          $result = $db->query("SELECT  lezioni.idCorso as idCorso,
                                        corsi.continuita as continuita,
                                        corsi.titolo as titolo,
                                        lezioni.aula as aula
                                FROM    corsi, lezioni
                                WHERE   corsi.idDocente = '".$utente."'
                                        AND lezioni.idCorso = corsi.id AND
                                        lezioni.ora = '$num'")
                    or die('ERRORE: ' . $db->error);
          if($result->num_rows > 0){
            while($lezione = $result->fetch_assoc()){
              $aula =  $lezione["aula"];
                echo '<td><b>'.$lezione["titolo"].'</b><span style="display:block; font-size:70%">Aula '.$aula.'</span></td>';
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
      <th style="width:18%; padding:10px;">
        Titolo
      </th>
      <th style="width:37%; padding:10px;">
        Descrizione
      </th>
      <th style="width:15%; padding:10px;">
        Classi
      </th>
      <th style="width:15%; padding:10px;">
        Tipologia
      </th>
      <th style="width:15%; padding:10px;">
        Continuita
      </th>
    </thead>
    <tbody>
      <?php
        $result = $db->query("SELECT id, titolo, descrizione, continuita, tipo from corsi where iddocente = '$utente'");
        while($corso = $result->fetch_assoc()){
          ?>
          <tr>
            <td style="padding:15px;">
              <b><?php echo $corso["titolo"];?></b>
            </td>
            <td style="padding:15px;">
              <?php echo $corso["descrizione"];?>
            </td>
            <td style="padding:15px;">
            <?php
              $classi = "";
              $resultClassi = $db->query("SELECT classe from corsi_classi where id_corso = '".$corso["id"]."' GROUP BY classe");
              while($classe = $resultClassi->fetch_assoc()){
                $classi .= $classe["classe"]." ";
              }
              echo $classi;
              ?>
            </td>
            <td style="padding:15px;">
              <?php
                if($dettagliCorso["tipo"]=='0'){
                  echo "Recupero";
                }
                else{
                  echo "Approf.";
                }
            ?>
            </td>
            <td style="padding:15px;">
              <?php	if($dettagliCorso["continuita"]=='0'){
                  echo " Senza cont.";
                }
                else{
                  echo "Con cont.";
                }
               ?>
            </td>
          </tr>
          <?php
        }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
