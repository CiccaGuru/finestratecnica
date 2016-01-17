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
  $min = 1;
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
$db = database_connect();
$level = $_POST["level"];


if(isset($_POST["username"])){
  $result = $db->query("SELECT * FROM utenti WHERE username LIKE '".$_POST["username"]."' or cognome like '".$_POST["username"]."'") or  die('ERRORE: ' . $db->error);
}
else{
  $result = $db->query("SELECT * FROM utenti WHERE level='1' ORDER BY cognome, nome LIMIT $min, ".($max-$min)) or  die('ERRORE: R' . $db->error);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Docenti</title>
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
				<li class="active"><a href="gestisciDocenti.php" class="waves-effect waves-light">Docenti</a></li>
				<li><a href="gestisciCorsi.php" class="waves-effect waves-light">Corsi</a></li>
				<li><a href="gestisciStudenti.php" class="waves-effect waves-light">Studenti</a></li>
				<li><a href="logout.php" class="waves-effect waves-light">Logout</a></li>
			</ul>
		</div>
	</nav>

  <div class="container"  style="margin-top:3em;">
  <div class="card">
    <form id="aggiungi-docente">
      <div class="card-content"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title blue-text center">Aggiungi un nuovo docente</span>
        <div class="row">
          <div class="input-field col s4">
            <input id="nome" type="text" class="validate" required>
            <label for="last_name">Nome</label>
          </div>
          <div class="input-field col s4">
            <input id="cognome" type="text" class="validate" required>
            <label for="last_name">Cognome</label>
          </div>
          <div class="input-field col s4">
            <input id="username" type="text" class="validate" required>
            <label for="last_name">Nome utente</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s4">
            <input id="password" type="password" class="validate" required>
            <label for="last_name">Password</label>
          </div>
          <div class="input-field col s4">
            <input id="ripeti_password" type="password" class="validate" required>
            <label for="last_name">Ripeti password</label>
          </div>
          <div class="col s4 center">
            <button type="submit" class="waves-effect waves-light btn-large red">Aggiungi</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="container" style="width:75%">
  <ul class="collection with-header z-depth-1" id="dettagliDocenti">
    <li class="collection-header blue-text center"><h4 class="light">Elenco docenti</h4></li>
    <li class="collection-item center">
      <form  action="gestisciDocenti.php" method="POST">
        <div class="row">
          <div class="col s1 offset-s1 valign-wrapper" style="font-size:120%;">
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
        <form action="gestisciDocenti.php" method="POST">
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
   while($row = $result->fetch_assoc())
    { ?>
      <li class="collection-item row valign-wrapper">
        <div class="col s1 bold">
          ID: <?php echo $row["id"];?>
        </div>
        <div class="input-field col s3 valign">
          <input id="nome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
        </div>
        <div class="input-field col s2 valign">
          <input id="cognome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
        </div>
        <div class="input-field col s2 valign">
          <input id="username<?php echo $row['id']; ?>" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
        </div>
        <div class="col s2 cente valign">
          <p><a onclick='modificaDocente(<?php echo $row['id'];?>)' class="waves-effect waves-light btn red valign" style="width:98%">Modifica</a></p>
          <p><a class="waves-effect waves-light btn red valign" onclick="eliminaDocente(<?php echo $row['id'];?>)" style="width:98%">Elimina</a></p>
        </div>
        <div class="col s2 center valign">
          <a onclick="passwordReset(<?php echo $row['id'];?>)" class="waves-effect waves-light btn-large valign red">Reset</a>
        </div>
      </li>
      <?php  }
      $result = $db->query("SELECT COUNT(id) as count FROM utenti where level='1'");
      $num = $result->fetch_assoc();
      if($num["count"]>($max-$min)){
        ?>
        <li class="collection-item row center valign-wrapper">
          <div class"center center-text">
          <form action = "gestisciDocenti.php" method="post">
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
  <li class="collection-item row valign-wrapper">

  </ul>

  <script src="js/jquery-2.1.4.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/admin.js"></script>

</div>
</body>
<?php
$db->close();
?>
