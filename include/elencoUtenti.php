<?php
include 'funzioni.php';
$db = database_connect();
$level = $_POST["level"];

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

if(isset($_POST["username"]) && ($_POST["username"]!="")){
  $filtro = $_POST["username"];
  $result = $db->query("SELECT * FROM utenti WHERE (username LIKE '$filtro' or nome like  '$filtro' or cognome like '$filtro') and level = '$level'  ORDER BY cognome,nome LIMIT ".(($page-1)*$quanti).", ".$quanti) or  die('ERRORE: ' . $db->error);
  $resultAA = $db->query("SELECT id FROM utenti WHERE (username LIKE '".$_POST["username"]."' or nome like  '".$_POST["username"]."' or cognome like '".$_POST["username"]."') and level = '$level'") or  die('ERRORE: ' . $db->error);
}
else{
  $result = $db->query("SELECT * FROM utenti WHERE level='$level' ORDER BY cognome, nome LIMIT ".(($page-1)*$quanti+1).", ".$quanti) or  die('ERRORE: R' . $db->error);
  $resultAA = $db->query("SELECT * FROM utenti WHERE level='$level'") or  die('ERRORE: ' . $db->error);
}

$numRisultato = $resultAA->num_rows;

if($level == 1){
?>
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
          <input id="username" name="username" type="text" class="validate" value="<?php
                      if(isset($_POST["username"]) && ($_POST["username"]!="")){
                        echo $_POST["username"];
                      }
                      ?>">
          <label for="username" class="condensed">Parola chiave</label>
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
 while($row = $result->fetch_assoc())
  { ?>
    <li class="collection-item row valign-wrapper">
      <div class="col s1 bold">
        ID: <?php echo $row["id"];?>
      </div>
      <div class="input-field col s3 valign">
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
        <p><a onclick="modificaDocente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect center-align waves-red btn-flat red-text valign" style="width:98%">Modifica</a></p>
        <p><a class="waves-effect waves-red center-align btn-flat red-text valign" onclick="eliminaDocente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" style="width:98%">Elimina</a></p>
      </div>
      <div class="col s2 center valign">
        <a onclick="passwordReset(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect small-icon condensed waves-red fill-width fake-button valign red-text">
            <i class="material-icons ">refresh</i> <br/>RESET
        </a>
      </div>
    </li>
    <?php  }
    if($numRisultato>$quanti){
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
                  while($i*$quanti<=$numRisultato){
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
      <script>
        $("#dettagliDocenti input").focus();
        $("#dettagliDocenti input").first().focus();
      </script>
<?php
}
else{

    ?>

    <li class="collection-header blue-text center"><h4 class="condensed light">ELENCO STUDENTI</h4></li>
    <li class="collection-item center">
      <form  action="gestisciStudenti.php" method="POST">
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



    while($row = $result->fetch_assoc())
    {
      ?>

      <li class="collection-item row valign-wrapper">
        <div class="col s1 red-text">
            <i class="material-icons waves-effect waves-red waves-circle" style="border-radius:50%;" onclick="eliminaUtente(<?php echo $row["id"]?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')">close</i>
        </div>
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
          <p style="margin-bottom:5px;">
            <a onclick="modificaStudente(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" class="waves-effect center-align waves-red btn-flat red-text valign" style="width:98%">
              Modifica
            </a>
          </p>
          <p style="margin-top:5px;">
            <a class="waves-effect waves-red center-align btn-flat red-text valign" onclick="mostraOrario(<?php echo $row['id'];?>)" style="width:98%;">Orario</a>
          </p>
        </div>
        <div class="col s1 center valign" style="padding:0px;">
          <a class="waves-effect small-icon condensed waves-red fill-width fake-button valign red-text" onclick="passwordReset(<?php echo $row['id'];?>, <?php echo $quanti;?>, <?php echo $page;?>, '<?php echo $_POST["username"]?>')" >
              <i class="material-icons ">refresh</i> <br/>RESET
          </a>
        </div>
      </li>
      <?php
    }

    if($numRisultato>$quanti){
      ?>
      <li class="collection-item row center">
        <div class"center">
          <ul class="pagination center">
              <li <?php if($page=='1') echo 'class="disabled"'?>>
                <form action="gestisciStudenti.php" id="paginaIndietro"  method="post">
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
                  while($i*$quanti<=$numRisultato){
                      ?>
                    <form action="gestisciStudenti.php" id="pagina<?php echo $i; ?>" method="post" style="display:inline;">
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
                <form action="gestisciStudenti.php" id="paginaAvanti" method="post">
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
    <?php
  }
  ?>
  <script>
    $("#dettagliStudenti input").focus();
    $("#dettagliStudenti input").first().focus();
  </script>
  <?php
}
$db->close();
?>
