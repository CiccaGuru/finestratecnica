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

if(isset($_POST["quanti"])){
  $quanti = $_POST["quanti"];
}else{
  $quanti = 20;
}

if(isset($_POST["page"])){
  $page = $_POST["page"];
}
else{
  $page = 1;
}
$db = database_connect();

$result = $db->query("SELECT * FROM aule");

?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Admin - Aule</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
  <link rel="stylesheet" href="css/material-scrolltop.css">
</head>
<body>
  <nav class="light-blue">
    <ul id="utenti-dropDown" class="dropdown-content">
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciStudenti.php">STUDENTI</a></li>
  	<li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciDocenti.php">DOCENTI</a></li>
  </ul>
  	<div class="navbar-fixed">
  		<nav id="intestaz" class="light-blue">
  			<div class="nav-wrapper">
  				<a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
  				<a href="#" class="brand-logo center light">Settimana tecnica</a>
  				<ul id="nav-mobile" class="right hide-on-med-and-down">
  					<li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
            <li class="active"><a href="gestisciAule.php" class="waves-effect waves-light condensed">AULE</a></li>
  					<li><a href="gestisciCorsi.php" class="waves-effect waves-light condensed">CORSI</a></li>
  					<li><a href="#!" class="dropdown-button waves-effect waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">UTENTI<i class="material-icons right">arrow_drop_down</i></a></li>
  					<li><a href="logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
  				</ul>
  			</div>
  		</nav>
  	</div>
	</nav>

  <div class="container"  style="margin-top:3em;">
  <div class="card">
    <form id="aggiungi-aula">
      <div class="card-content center-align"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <div class="card-title blue-text center-align condensed" style="margin-bottom:1em;">Aggiungi una nuova aula</div>
        <div class="row valign-wrapper">
          <div class="input-field col s4 offset-s1 valign">
            <input id="nomeAula" type="text" class="validate" required>
            <label class="condensed" for="nomeAula">Nome aula</label>
          </div>
          <div class="input-field col s3 valign">
            <input id="maxStudenti" type="text" class="validate" required>
            <label class="condensed" for="maxStudenti">Numero studenti</label>
          </div>
          <div class="col s3 valign">
          <button type="submit" class="waves-effect waves-light btn-large red condensed">
            <i class="material-icons left">add_location</i>Aggiungi
          </button>
        </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="container" style="width:70%">
  <ul class="collection with-header z-depth-1" id="dettagliAule">
    <li class="collection-header blue-text center"><h4 class="condensed light">ELENCO AULE</h4></li>
    <li class="collection-item center">
      <form  action="gestisciAule.php" method="POST">
        <div class="row">
          <div class="col s2" style="font-size:120%; margin-top:0.5em">
            <p class="condensed red-text">
              <i class="material-icons left">search</i>Cerca
            </p>
          </div>

          <div class="input-field col s4">
            <input id="usernameSearch" name="username" type="text" class="validate" value="<?php
                        if(isset($_POST["username"]) && ($_POST["username"]!="")){
                          echo $_POST["username"];
                        }
                        ?>">
            <label for="usernameSearch" class="condensed">Parola chiave</label>
          </div>
          <div class="col s2 offset-s1" style="margin-top:0.6em;">
            <p class="condensed">
              Risultati per pagina:
            </p>
          </div>
          <div class="input-field col s1">
            <input id="min" name="quanti" type="text" class="validate" value="<?php echo $quanti?>" required>
          </div>
          <div class="input-field col s1 right">
            <button class="btn-floating btn-large waves-effect waves-light red condensed white-text" type="submit" name="action">
              <i class="material-icons">search</i>
            </button>
          </div>
        </div>
      </form>
    </li>
  <?php
  if($result->num_rows==0){
    ?>  <li class="collection-item">
          <div class="red-text condensed center-align" style="font-size:150%; margin:1em;">Nessuna aula</div>
        </li>
      <?php
  }
   while($row = $result->fetch_assoc())
    { ?>
      <li class="collection-item row valign-wrapper">
        <div class="col s1 red-text">
            <i class="material-icons waves-effect waves-red waves-circle" style="border-radius:50%;" onclick="eliminaAula(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>', 1)">close</i>
        </div>
        <div class="col s2 bold">
          ID: <?php echo $row["id"];?>
        </div>
        <div class="input-field col s3 valign">
          <input id="nomeAula<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nomeAula']; ?>" required>
            <label for="nomeAula<?php echo $row['id']; ?>">Nome aula</label>
        </div>
        <div class="input-field col s3 valign">
          <input id="maxStudenti<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['maxStudenti']; ?>" required>
          <label for="maxStudenti<?php echo $row['id']; ?>">N° studenti</label>
        </div>
        <div class="col s3 center valign">
          <a onclick="modficaAula(<?php echo $row['id'];?>)" class="waves-effect small-icon condensed waves-red fill-width fake-button valign red-text">
            	<i class="material-icons ">create</i> <br/>MODIFICA
          </a>
        </div>
      </li>
      <?php  }
      if($num>$quanti){
        ?>
        <li class="collection-item row center">
          <div class"center">
            <ul class="pagination center">
                <li <?php if($page=='1') echo 'class="disabled"'?>>
                  <form action="gestisciDocenti.php" id="paginaIndietro"  method="post">
                    <input type="hidden" name="page" value="<?php echo ($page -1)?>">
                    <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                    <?php if(isset($_POST["username"])){?>
                    <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                    <?php }?>
                    <a onclick="$('#paginaIndietro').submit();">
                    <i class="material-icons">chevron_left</i>
                  </a>
                </form>

                </li>

                <?php
                    while($i*$quanti<=$num){
                        ?>
                      <form action="gestisciDocenti.php" id="pagina<?php echo $i; ?>" method="post" style="display:inline;">
                        <li class="waves-effect waves-red <?php if($i==$page) echo "active"?>">
                              <input type="hidden" name="page" value="<?php echo $i?>">
                              <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                              <?php if(isset($_POST["username"])){?>
                              <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                              <?php }?>
                              <a onclick="$('#pagina<?php echo $i?>').submit();">
                                <?php echo $i ?>
                              <a/>
                          </li>
                        </form>
                      <?php
                        $i++;
                    }
                ?>
                <li <?php if($page==($i-1)) echo 'class="disabled"'?>class="waves-effect">
                  <form action="gestisciDocenti.php" id="paginaAvanti" method="post">
                    <input type="hidden" name="page" value="<?php echo ($page +1)?>">
                    <input type="hidden" name="quanti" value="<?php echo $quanti?>">
                    <?php if(isset($_POST["username"])){?>
                    <input type="hidden" name="username" value="<?php echo $_POST["username"]?>">
                    <?php }?>
                    <a onclick="$('#paginaAvanti').submit();">
                    <i class="material-icons">chevron_right</i>
                  </a>
                </form>
                </li>
            </ul>
          </div>
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
  <!-- material-scrolltop button -->

</div>
<button class="material-scrolltop" type="button"><i class="material-icons white-text">keyboard_arrow_up</i></button>

<!-- material-scrolltop plugin -->
<script src="js/material-scrolltop.js"></script>

<!-- Initialize material-scrolltop with (minimal) -->
<script>
  $('body').materialScrollTop();
</script>
</body>
<?php
$db->close();
?>
