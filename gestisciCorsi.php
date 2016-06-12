<?php
require_once('include/funzioni.php'); // Includes Login Script
include 'include/config.php';
global $_CONFIG;

$utente = check_login();
if($utente==-1){
  header('Location: index.php');
}
else{
  $user_level = get_user_level($utente);
  if($user_level == 0)
  header('Location: userhome.php');
  if($user_level == 1)
  header('Location: docente.php');
}

if(isset($_POST["min"])){
  $min = $_POST["min"];
}
else{
  $min = 0;
}

if(isset($_POST["max"])){
  $max = $_POST["max"];
}
else{
  $max = 30;
}
$condizione = "";
if(isset($_POST["action"])){
  if(isset($_POST["concontinuita"]) and !(isset($_POST["senzacontinuita"]))){
      $condizione .= " corsi.continuita = '1' AND ";
  }
  if(isset($_POST["senzacontinuita"]) and !(isset($_POST["concontinuita"]))){
      $condizione .= " corsi.continuita = '0' AND ";
  }
  if(isset($_POST["recupero"]) and !(isset($_POST["approfondimento"]))){
      $condizione .= " corsi.tipo = '0' AND ";
  }
  if(isset($_POST["approfondimento"]) and !(isset($_POST["recupero"]))){
      $condizione .= " corsi.tipo = '1' AND ";
  }
  if(!($_POST["professore"]=="")){
    $professore = str_replace("à", "&agrave", $_POST["professore"]);
    $professore = str_replace("è", "&egrave", $professore);
    $professore = str_replace("ì", "&igrave", $professore);
    $professore = str_replace("ò", "&ograve", $professore);
    $professore = str_replace("é", "&eacuto", $professore);
    $condizione .= "(utenti.nome LIKE '$professore' OR utenti.cognome LIKE '$professore') AND ";
  }
  if(!($_POST["titolo"]=="")){
    $titolo = str_replace("à", "&agrave", $_POST["titolo"]);
    $titolo = str_replace("è", "&egrave", $titolo);
    $titolo = str_replace("ì", "&igrave", $titolo);
    $titolo = str_replace("ò", "&ograve", $titolo);
    $titolo = str_replace("é", "&eacuto", $titolo);
    $condizione .= "(corsi.titolo LIKE '%$titolo%' OR corsi.descrizione LIKE '%$titolo%')";
  }
  else{
    $condizione .= "1";
  }
}

if($condizione==""){
  $condizione = "1";
}else{

  $condizione.=")";
}
$giorni="";
$ore_elenco="";
foreach($_CONFIG['giorni'] as $num=>$nome){
		$giorni .= '<option value="'.$num.'">'.$nome.'</option>';
}

for($j=1;$j<=$_CONFIG['ore_per_giorno'];$j++){
	$ore_elenco .= '<option value="'.$j.'">'.$j.'^a ora</option>';
}
$db = database_connect();

$result = $db->query("SELECT  corsi.titolo as titolo,
                              corsi.descrizione as descrizione,
                              corsi.continuita as continuita,
                              corsi.tipo as tipo,
                              utenti.nome as nome,
                              utenti.cognome as cognome,
                              corsi.id as id
                      FROM corsi,utenti
                      WHERE (utenti.id = corsi.iddocente) AND ($condizione)
                      ORDER BY corsi.titolo
                      LIMIT $min, ".($max-$min)) or die("AA: ".$db->error);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Corsi</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
</head>

<body>

  <nav class="light-blue">
    <div class="nav-wrapper">
      <a class="left light big" style="margin-left:2%">Amministratore</a>
      <a href="#" class="brand-logo center light">Settimana tecnica</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><a href="admin.php" class="waves-effect waves-light">Home</a></li>
        <li><a href="gestisciDocenti.php" class="waves-effect waves-light">Docenti</a></li>
        <li class="active"><a href="gestisciCorsi.php" class="waves-effect waves-light">Corsi</a></li>
        <li><a href="gestisciStudenti.php" class="waves-effect waves-light">Studenti</a></li>
        <li><a href="logout.php" class="waves-effect waves-light">Logout</a></li>
      </ul>
    </div>
  </nav>

  <div class="container" id="ins-corso" style="margin-top:5em;">
    <div class="card">
      <div class="card-content" style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title blue-text" style="text-align:center">Crea un nuovo corso</span>
        <div class="row">
          <div class="input-field col s3">
            <input id="titolo" type="text" class="validate">
            <label for="titolo">Titolo</label>
          </div>
          <div class="input-field col s5">
            <input id="descriz" type="text" class="validate">
            <label for="descriz">Descrizione</label>
          </div>
          <div class="input-field col s4">
            <select id="selezionaDocenti">
              <option value="" disabled selected class="grey-text">Seleziona insegnante</option>
            </select>
            <label>Scegli l'insegnante</label>
          </div>
        </div>
        <div class="row valign-wrapper">
          <div class="col s2">
            <p>
              <input name="tipo" value="0" type="radio" id="recupero" checked/>
              <label class="black-text" for="recupero">Recupero</label>
            </p>
            <p>
              <input name="tipo" value="1" type="radio" id="approfondimento" />
              <label class="black-text" for="approfondimento">Approf.</label>
            </p>
          </div>
          <div class="col s3">
            <p>
              <input name="continuita" value="1" type="radio" id="con_continuita" checked/>
              <label class="black-text" for="con_continuita">Con continuità</label>
            </p>
            <p>
              <input name="continuita" value="0" type="radio" id="senza_continuita" />
              <label class="black-text" for="senza_continuita">Senza continuità</label>
            </p>
          </div>
          <div class="input-field col s2">
            <select id="selezionaClassi" multiple>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            <label>Classi</label>
          </div>
          <div class="input-field col s2 valign">
            <input id="ore" type="text" class="validate">
            <label for="ore">Ore</label>
          </div>
          <div class="col s3 center">
            <a id="inserisciOre" class="waves-effect btn waves-light red">Inserisci ore</a>
          </div>
        </div>
        <div id="ore_future">
        </div>
        <div class="center">
          <a onclick="aggiungiCorso()" class="waves-effect waves-light btn-large red">Aggiungi</a>
        </div>
      </div>
</div>
</div>
<div class="container" style="margin-top:3em; width:97%">
      <ul class="collection with-header z-depth-1" id="dettagliStudenti">
          <li class="collection-header blue-text center"><h4 class="light">Elenco corsi</h4></li>
          <li class="collection-item">
            <form  action="gestisciCorsi.php" method="POST">
              <div class="row ">

              <div class="col s2 valign">
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["concontinuita"])) || (!(isset($_POST["concontinuita"])) && !(isset($_POST["senzacontinuita"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" name="concontinuita" id="concontinuita" />
                        <label for="concontinuita">Con continuità</label>
                    </p>
                  </div>
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input  <?php
                          if((isset($_POST["senzacontinuita"])) || (!(isset($_POST["concontinuita"])) && !(isset($_POST["senzacontinuita"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" name="senzacontinuita" id="senzacontinuita" />
                        <label for="senzacontinuita">Senza continuità</label>
                    </p>
                  </div>
                </div>

                <div class="col s1 valign">
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["recupero"])) || (!(isset($_POST["approfondimento"])) && !(isset($_POST["recupero"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" name="recupero" id="recuperoCerca" />
                        <label for="recuperoCerca">Recupero</label>
                    </p>
                  </div>
                  <div class="row" style="margin-bottom:5px;">
                    <p>
                        <input <?php
                          if((isset($_POST["approfondimento"])) || (!(isset($_POST["approfondimento"])) && !(isset($_POST["recupero"])))){
                            echo "checked";
                          }
                        ?> type="checkbox" name="approfondimento" id="approfondimentoCerca" />
                        <label for="approfondimentoCerca">Approf.</label>
                    </p>
                  </div>
                </div>

                <div class="input-field col s2 valign">
                  <input id="professore" name="professore" type="text"  value="<?php echo $_POST["professore"]?>" class="validate valign">
                  <label for="professore">Professore</label>
                </div>

                <div class="input-field col s2 valign">
                  <input id="titoloCerca" name="titolo" type="text" value="<?php echo $_POST["titolo"]?>" class="validate valign">
                  <label for="titoloCerca">Titolo - descrizione</label>
                </div>
                <div class="col s1 valign-wrapper" style="font-size:120%;">
                  <p class="valign">
                    Mostra
                  </p>
                </div>

                <div class="input-field col s1 valign">
                  <input id="min" name="min" type="text" class="validate valign" value="<?php echo $min?>" required>
                  <label for="min">Da:</label>
                </div>

                <div class="input-field col s1 valign">
                  <input id="max" name="max" type="text" class="validate valign" value="<?php echo $max?>" required>
                  <label for="max">A:</label>
                </div>
                <div class="col s1">
                  <div class="input-field">
                    <button class="btn waves-effect waves-light red white-text" type="submit" name="action">Aggiorna
                      <i class="material-icons right">send</i>
                    </button>
                  </div>
                </div>

            </div>
              </form>
          </li>
          <?php


          while($dettagli = $result->fetch_assoc()){
          ?>
          <li class="collection-item">
            <div class="row valign-wrapper" style="margin-bottom:0px;">

            <div class="col s1 valign bold">
              ID: <?php echo $dettagli["id"];?>
            </div>
            <div class="input-field col valign s2">
              <input id="titoloCorso<?php echo $dettagli["id"];?>" type="text" class="validate" value="<?php echo $dettagli["titolo"];?>" required>
              <label for="titoloCorso<?php echo $dettagli["id"];?>">Titolo</label>
            </div>
            <div class="input-field col valign s3">
              <input id="descrizioneCorso<?php echo $dettagli["id"];?>" type="text" class="validate" value="<?php echo $dettagli["descrizione"];?>" required>
              <label for="descrizioneCorso<?php echo $dettagli["id"];?>">Descrizione</label>
            </div>
            <div class="input-field col valign s2">
              <input id="professoreCorso<?php echo $dettagli["id"];?>" type="text" class="validate" style="color: black" disabled value="<?php echo $dettagli["nome"][0].". ".$dettagli["cognome"];?>" required>
              <label for="professoreCorso<?php echo $dettagli["id"];?>">Professore</label>
            </div>
            <div class="input-field col s1">
              <select id="continuitaCorso<?php echo $dettagli["id"];?>">
                <option value="1" <?php if($dettagli["continuita"]) echo "selected"?>>Con</option>
                <option value="0" <?php if(!$dettagli["continuita"]) echo "selected"?>>Senza</option>
              </select>
              <label>Continuita</label>
            </div>
            <div class="input-field col s1">
              <select id="tipoCorso<?php echo $dettagli["id"];?>">
                <option value="0" <?php if(!$dettagli["tipo"]) echo "selected"?>>Recupero</option>
                <option value="1" <?php if($dettagli["tipo"]) echo "selected"?>>Approf.</option>
              </select>
              <label>Tipo</label>
            </div>
            <div class="col s2 cente valign">
              <p style="margin-bottom:5px;"><a onclick='modificaCorso(<?php echo $dettagli['id'];?>)' class="waves-effect waves-light btn red valign" style="width:98%;" >Modifica</a></p>
              <p style="margin-top:5px;"><a class="waves-effect waves-light btn red valign" onclick="mostraOreModifica(<?php echo $dettagli['id'];?>)" style="width:98%;">modifica ore</a></p>
            </div>
            </div>
          </li><?php
        }

        $resultConta = $db->query("SELECT  corsi.id as conta
                              FROM corsi, utenti
                              WHERE (utenti.id = corsi.iddocente) AND $condizione") or die("BB: ".$db->error);
          if($resultConta->num_rows>($max-$min)){
            ?>
            <li class="collection-item row center valign-wrapper">
              <div class"center center-text">
              <form action = "gestisciCorsi.php" method="post">
                <input type="hidden" name = "min" value="<?php echo ($max)?>"></input>
                <input type="hidden" name = "max" value="<?php echo ($max+$max-$min)?>"></input>
                <button class="btn center-text waves-effect waves-light red white-text" type="submit" name="action">Avanti
                </button>
              </div>
              </form>
            </li>
            <?php
          }
        ?>
</ul>
    </div>

    <div id="modal-orario" class="modal modal-fixed-footer" style="marign-top:5em;">
      <div class="modal-content">
          <h1 class="light-blue-text thin center" style="margin-bottom:0.3em;">Orario</h1>
      </div>
      <div class="modal-footer">
        <a href="#!" style="margin-bottom:1em;" class="modal-action modal-close waves-effect waves-red red-text btn-flat">CHIUDI</a>
      </div>
    </div>

    <div id="modal-ore" class="modal modal-fixed-footer">

  </div>

  <div id="wait" class="center-align valign-wrapper">
    <div id="contenitore-cerchio" class="valign">
      <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-blue-only">
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/materialize.js"></script>
    <script src="js/init.js"></script>
    <script src="js/admin.js"></script>
</body>
