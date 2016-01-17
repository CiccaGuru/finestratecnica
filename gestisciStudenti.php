<?php


require_once('include/funzioni.php'); // Includes Login Script
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

if(isset($_POST["username"])){
  $min = 0;
  $max = 30;
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
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Studenti</title>
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
				<li ><a href="admin.php" class="waves-effect waves-light">Home</a></li>
				<li><a href="gestisciDocenti.php" class="waves-effect waves-light">Docenti</a></li>
				<li><a href="gestisciCorsi.php" class="waves-effect waves-light">Corsi</a></li>
				<li class="active"><a href="gestisciStudenti.php" class="waves-effect waves-light">Studenti</a></li>
				<li><a href="logout.php" class="waves-effect waves-light">Logout</a></li>
			</ul>
		</div>
	</nav>

  <div class="container"  style="margin-top:3em;">
    <div class="card">
      <div class="card-content"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title blue-text center">Cerca studente</span>
        <div class="row">
          <div class="input-field col s3">
            <input id="nomeStudenteCerca" type="text" class="validate" required>
            <label for="nomeStudenteCerca">Nome</label>
          </div>
          <div class="input-field col s3">
            <input id="cognomeStudenteCerca" type="text" class="validate" required>
            <label for="cognomeStudenteCerca">Cognome</label>
          </div>
          <div class="input-field col s3">
            <select id="selezionaGiornoCerca">
              <?php echo $giorni;?>
            </select>
            <label>Giorno</label>
          </div>
          <div class="col s3">
            <div class="input-field">
              <select id="selezionaOraCerca">
                <?php echo $ore_elenco;?>
              </select>
              <label>Ora</label>
            </div>
          </div>
        </div>
        <div class="center">
          <a onclick="cercaStudente()" class="waves-effect waves-light btn-large red">Cerca</a>
        </div>

      </div>
    </div>
    <div class="card">
      <form id="aggiungi-studente">
        <div class="card-content"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
          <span class="card-title blue-text center">Aggiungi un nuovo studente</span>
          <div class="row">
            <div class="input-field col s4">
              <input id="nomeStudente" type="text" class="validate" required>
              <label for="nomeStudente">Nome</label>
            </div>
            <div class="input-field col s4">
              <input id="cognomeStudente" type="text" class="validate" required>
              <label for="cognomeStudente">Cognome</label>
            </div>
            <div class="input-field col s4">
              <input id="usernameStudente" type="text" class="validate" required>
              <label for="usernameStudente">Nome utente</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s4">
              <input id="passwordStudente" type="password" class="validate" required>
              <label for="passwordStudente">Password</label>
            </div>
            <div class="input-field col s3">
              <input id="ripeti_passwordStudente" type="password" class="validate" required>
              <label for="ripeti_passwordStudente">Ripeti password</label>
            </div>
            <div class="input-field col s2">
              <select id="selezionaClasseStudente">
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
              <label>Classe</label>
            </div>
            <div class="col s3 center">
              <button type="submit" class="waves-effect waves-light btn-large red">Aggiungi</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="container" style="width:75%">
    <ul class="collection with-header z-depth-1" id="dettagliStudenti">
        <li class="collection-header blue-text center"><h4 class="light">Elenco studenti</h4></li>
      <li class="collection-item center">
        <form  action="gestisciStudenti.php" method="POST">
          <div class="row">
            <div class="col offset-s1 s1 valign-wrapper" style="font-size:120%;">
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
            <div class="input-field col s1 valign">
              <button class="btn waves-effect waves-light red white-text" type="submit" <?php if(isset($_POST["username"])) echo "value = \"".$_POST["username"]."\""?> name="action">Aggiorna
                <i class="material-icons right">send</i>
              </button>
            </div>
          </form>
          <form action="gestisciStudenti.php" method="POST">
            <div class="col s1 offset-s2" style="font-size:120%;">
              <p class="valign">
                Cerca:
              </p>
            </div>
            <div class="input-field col s2 valign">
              <input id="username" name="username" type="text" class="validate valign" required>
              <label for="username">Username</label>
            </div>
            <div class="input-field col s1 valign">
              <button class="btn waves-effect waves-light red white-text" type="submit" name="action">Cerca
                <i class="material-icons right">send</i>
              </button>
            </div>
          </div>
        </form>
      </li>

      <?php

      if(isset($_POST["username"])){
        $result = $db->query("SELECT * FROM utenti WHERE username LIKE '".$_POST["username"]."'") or  die('ERRORE: ' . $db->error);
        $numRow = 0;
      }
      else{
        $result = $db->query("SELECT * FROM utenti WHERE level='0' ORDER BY cognome, nome LIMIT $min, ".($max-$min)) or  die('ERRORE: R' . $db->error);
        $resultPP = $db->query("SELECT * FROM utenti WHERE level='0' ORDER BY cognome, nome") or  die('ERRORE: R' . $db->error);
        $numRow = $resultPP->num_rows;
      }

      while($row = $result->fetch_assoc())
      {
        ?>

        <li class="collection-item row valign-wrapper">
          <div class="col s1 bold">
            ID: <?php echo $row["id"];?>
          </div>
          <div class="input-field col s2 valign">
            <input id="nome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
            <label for="nome<?php echo $row['id']; ?>Studente">Nome</label>
          </div>
          <div class="input-field col s2 valign">
            <input id="cognome<?php echo $row['id']; ?>Studente" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
            <label for="nome<?php echo $row['id']; ?>Studente">Cognome</label>
          </div>
          <div class="input-field col s2 valign">
            <input id="username<?php echo $row['id']; ?>Studente" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
            <label for="nome<?php echo $row['id']; ?>Studente">Username</label>
          </div>
          <div class="input-field col s1">
            <select id="classeStudente<?php echo $row['id'];?>">
              <option value="1" <?php if($row["classe"]=='1') echo "selected";?>>1</option>
              <option value="2" <?php if($row["classe"]=='2') echo "selected";?>>2</option>
              <option value="3" <?php if($row["classe"]=='3') echo "selected";?>>3</option>
              <option value="4" <?php if($row["classe"]=='4') echo "selected";?>>4</option>
              <option value="5" <?php if($row["classe"]=='5') echo "selected";?>>5</option>
            </select>
            <label>Classe</label>
          </div>
          <div class="col s2 cente valign">
            <p style="margin-bottom:5px;"><a onclick='modificaStudente(<?php echo $row['id'];?>)' class="waves-effect waves-light btn red valign" style="width:98%; ">Modifica</a></p>
            <p style="margin-top:5px;"><a class="waves-effect waves-light btn red valign" onclick="mostraOrario(<?php echo $row['id'];?>)" style="width:98%;">Orario</a></p>
          </div>
          <div class="col s2 center valign">
            <a onclick="passwordReset(<?php echo $row['id'];?>)" class="waves-effect waves-light btn-large valign red">Reset</a>
          </div>
        </li>
        <?php
      }
      ?>
      <?php
      if($numRow>($max-$min)){
        ?>
        <li class="collection-item row center valign-wrapper">
          <div class"center center-text">
          <form action = "gestisciStudenti.php" method="post">
            <input type="hidden" name = "min" value="<?php echo ($max)?>"></input>
            <input type="hidden" name = "max" value="<?php echo ($max+$max-$min)?>"></input>
            <button class="btn center-text waves-effect waves-light red white-text" type="submit" name="action">Avanti
            </button>
          </div>
          </form>
        </li>
        <?php
      }?>
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

  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/admin.js"></script>
</body>
</html>
<?php

$db->close();
?>
