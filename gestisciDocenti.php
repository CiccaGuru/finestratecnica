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

if(isset($_POST["username"]) && ($_POST["username"]!="")){
  $filtro = $_POST["username"];
  $result = $db->query("SELECT * FROM utenti WHERE (username LIKE '$filtro' or nome like  '$filtro' or cognome like '$filtro') and level = '1'  ORDER BY cognome,nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: ' . $db->error);
  $resultAA = $db->query("SELECT id FROM utenti WHERE (username LIKE '".$_POST["username"]."' or nome like  '".$_POST["username"]."' or cognome like '".$_POST["username"]."') and level = '1'") or  die('ERRORE: ' . $db->error);
}
else{
  $result = $db->query("SELECT * FROM utenti WHERE level='1' ORDER BY cognome, nome LIMIT ".(($page-1)*$quanti+1).", ".$quanti) or  die('ERRORE: R' . $db->error);
  $resultAA = $db->query("SELECT * FROM utenti WHERE level='1'") or  die('ERRORE: ' . $db->error);
}

$num = $resultAA->num_rows;
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
  <link href="css/admin.css" type="text/css" rel="stylesheet" media="screen"/>
  <link rel="stylesheet" href="css/material-scrolltop.css">
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
<body>
  <nav class="light-blue">
    <ul id="utenti-dropDown" class="dropdown-content">
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciStudenti.php">STUDENTI</a></li>
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciDocenti.php">DOCENTI</a></li>
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciCorsi.php">CORSI</a></li>
    <li><a class="waves-effect waves-blue condensed light-blue-text" href="gestisciClassi.php">CLASSI</a></li>
  </ul>
    <div class="navbar-fixed">
      <nav id="intestaz" class="light-blue">
        <div class="nav-wrapper">
          <a class="hide-on-small-only left condensed letter-spacing-1" style="margin-left:2%;"> AMMINISTRATORE</a>
          <a href="#" class="brand-logo center light">Settimana tecnica</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="admin.php" class="waves-effect waves-light condensed">HOME</a></li>
            <li class="active"><a href="#!" class="dropdown-button waves-effect active waves-light condensed" data-beloworigin="true" data-hover="true" data-activates="utenti-dropDown">GESTISCI<i class="material-icons right">arrow_drop_down</i></a></li>
            <li><a href="logout.php" class="waves-effect waves-light condensed"><i class="material-icons left">exit_to_app</i>LOGOUT</a></li>
          </ul>
        </div>
      </nav>
    </div>
  </nav>

  <div class="container"  style="margin-top:3em;">
  <div class="card">
    <form id="aggiungi-docente">
      <div class="card-content center-align"  style="padding-left:5%; padding-right:5%; padding-bottom:5%">
        <span class="card-title blue-text center-align condensed">Aggiungi un nuovo docente</span>
        <div class="row">
          <div class="input-field col s4">
            <input id="nome" type="text" class="validate" required>
            <label class="condensed" for="last_name">Nome</label>
          </div>
          <div class="input-field col s4">
            <input id="cognome" type="text" class="validate" required>
            <label class="condensed" for="last_name">Cognome</label>
          </div>
          <div class="input-field col s4">
            <input id="username" type="text" class="validate" required>
            <label class="condensed" for="last_name">Nome utente</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s4">
            <input id="password" type="password" class="validate" required>
            <label class="condensed" for="last_name">Password</label>
          </div>
          <div class="input-field col s4">
            <input id="ripeti_password" type="password" class="validate" required>
            <label class="condensed" for="last_name">Ripeti password</label>
          </div>
          <div class="col s4 center">
            <button type="submit" class="waves-effect waves-light btn-large red condensed">
              <i class="material-icons left">person_add</i>Aggiungi</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="container" style="width:90%">
  <ul class="collection with-header z-depth-1" id="dettagliDocenti">
    <li class="collection-header blue-text center"><h4 class="condensed light">ELENCO DOCENTI</h4></li>
    <li class="collection-item center">
      <form  action="gestisciDocenti.php" method="POST">
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
          <div class="red-text condensed center-align" style="font-size:150%; margin:1em;">Nessun risultato trovato</div>
        </li>
      <?php
  }
   while($row = $result->fetch_assoc())
    { ?>
      <li class="collection-item row valign-wrapper">
        <div class="col s1 red-text">
            <i class="material-icons waves-effect waves-red waves-circle" style="border-radius:50%;" onclick="eliminaUtente(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>', 1)">close</i>
        </div>
        <div class="col s1 bold">
          ID: <?php echo $row["id"];?>
        </div>
        <div class="input-field col s2 valign">
          <input id="nome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['nome']; ?>" required>
            <label for="nome<?php echo $row['id']; ?>">Nome</label>
        </div>
        <div class="input-field col s2 valign">
          <input id="cognome<?php echo $row['id']; ?>" type="text" class="validate valign" value="<?php echo $row['cognome']; ?>" required>
          <label for="cognome<?php echo $row['id']; ?>">Cognome</label>
        </div>
        <div class="input-field col s2 valign">
          <input id="username<?php echo $row['id']; ?>" type="text" class="validatevalign" value="<?php echo $row['username']; ?>" required>
          <label for="username<?php echo $row['id']; ?>">Username</label>
        </div>
        <div class="col s2 cente valign">
          <p>
            <a onclick="modificaDocente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect center-align waves-red btn-flat red-text valign" style="width:98%">
              Modifica
            </a>
          </p>
          <p>
            <a class="waves-effect waves-red center-align btn-flat red-text valign" onclick="alert('Non è ancora possibile mostrare l\'orario del docente')" style="width:98%">
              Orario
            </a>
          </p>
        </div>
        <div class="col s2 center valign">
          <a onclick="passwordReset(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect small-icon condensed waves-red fill-width fake-button valign red-text">
            	<i class="material-icons ">refresh</i> <br/>RESET
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



</div>

<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>
<script src="js/admin.js"></script>
<!-- material-scrolltop button -->
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
