<!--<?php
/*if(!file_exists("/include/config.php")){
  $config = fopen("include/config.php", "w")  or die("Non riesco a creare il file di configurazione!");
  fclose($config);
}*/?>-->
<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Settimana tecnica</title>
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

<body id="install-body" class="valign-wrapper">
  <div id="install-card" class="card valign z-depth-4 white">
    <div class="title condensed primary light">Installazione</div>
    <div class="card-content center-align primary white">
      <div id="install-1">
        <div><i class="material-icons" style="font-size:800%;"><span class="ruota">settings</span> build</i></div>
        <div class="condensed" style="font-size:200%; margin-bottom:1em; margin-top:1em;">Questa procedura guidata ti accompagnerà passo per passo nell'installazione di finestratecnica</div>
      </div>
      <div id="install-2" style="display:none">
        <h4 class="condensed primary-text" style="margin-top:0px;">Step 1: Licenza del software</h4>
        <p>Finestratecnica è rilasciato sotto la licenza GNU General Public License v3. Ciò significa che puoi:
          <ul>
            <li>Modificare finestratecnica</li>
            <li>Ridistribuire finestratecnica e le tue modifiche</li>
            <li>Usare finestratecnica per fini commerciali</li>
          </ul>
          purché tu mantenga la licenza GNU GPL v3.
        </p>
        <p>
          In questo modo, tutti avranno la possibilità di usare finestratecnica come stai facendo tu ora.
        </p>
        <p class="center-align">
          <a class="btn primary center" style="margin:2em;" target="_blank" href="https://www.gnu.org/licenses/gpl-3.0.txt">LICENZA COMPLETA</a>
        </p>
        <p class="center-align">
          <input class="filled-in" type="checkbox" id="accept" />
          <label for="accept">Accetto i termini della licenza</label>
        </p>
      </div>
      <div id="install-3" style="display:none">
        <h4 class="condensed primary-text" style="margin-top:0px;">Step 3: Configurazione del database</h4>
        <p>Per poter usare finestratecnica, c'è bisogno di un database MySql funzionante. Lo script di installazione creerà anche l'utente di amministrazione.</p>
        <div class="row">
          <div class="col s6 distribut-container">
            <div class="input-field distribute">
              <input id="DBLocation" type="text" class="validate">
              <label for="DBLocation">Posizione database:</label>
            </div>
            <div class="input-field distribute">
              <input id="DBUser" type="text" class="validate">
              <label for="DBUser">Username:</label>
            </div>
            <div class="input-field distribute">
              <input id="DBPassword" type="password" class="validate">
              <label for="DBPassword">Password:</label>
            </div>
          </div>
          <div class="col s6">
            <div class="input-field distribute">
              <input id="DBName" type="text" class="validate">
              <label for="DBName">Nome del database:</label>
            </div>
            <div class="input-field distribute">
              <input id="adminUsername" type="text" class="validate">
              <label for="adminUsername">Username amministratore:</label>
            </div>
            <div class="input-field distribute">
              <input id="adminPassword" type="password" class="validate">
              <label for="adminPassword">Password amministratore:</label>
            </div>
          </div>
        </div>
      </div>

      <div id="install-4" style="display:none">
        <h4 class="condensed primary-text" style="margin-top:0px;">Step 4: Generazione struttura database</h4>
        <span id="install-4-content">
        <div class="contieni-spinner" style="margin-top:3em; margin-bottom:3em;">
        <div class="preloader-wrapper active center-align">
          <div class="spinner-layer biggest spinner-primary-only">
            <div class="circle-clipper left">
              <div class="circle"></div>
            </div><div class="gap-patch">
              <div class="circle"></div>
            </div><div class="circle-clipper right">
              <div class="circle"></div>
            </div>
          </div>
        </div>
          </div>
          <div class="condensed light center-align" style="font-size:150%">Generazione tabelle in corso...</div>
        </span>
      </div>

      <div id="install-5" style="display:none">
        <h4 class="condensed primary-text" style="margin-top:0px;">Installazione compleatata!</h4>
        <i class="material-icons center-align primary-text" style="font-size:800%; margin-top:0.5em;">sentiment_very_satisfied</i>
      </div>


    </div>
    <div class="primary card-action center" style="letter-spacing:3px;">
      <span id="dot-1" class="dot primary" style="font-size:100%;">●</span>
      <span id="dot-2" class="dot primary-darkest-text" style="font-size:65%;">●</span>
      <span id="dot-3" class="dot primary-darkest-text" style="font-size:65%;">●</span>
      <span id="dot-4" class="dot primary-darkest-text" style="font-size:65%;">●</span>
      <span id="dot-5" class="dot primary-darkest-text" style="font-size:65%;">●</span>
      <span id="dot-6" class="dot primary-darkest-text" style="font-size:65%;">●</span>
    </div>
  </div>
</div>

<div id="sfondo-grigio" style="display:none;" class="valign-wrapper">
  <div class="card valign center-align" id="avviso-floating">
    <span id="avviso-floating-content">
    <div class="titolo condensed primary-text center-align">Verifica della connessione a MySql...</div>
    <div>
  <div class="contieni-spinner">
  <div class="preloader-wrapper active center-align">
    <div class="spinner-layer big spinner-primary-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div><div class="gap-patch">
        <div class="circle"></div>
      </div><div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>
    </div>
  </div>
</span>
</div>
  </div>

<a id="procedi" class="btn white waves-effect primary-text">Procedi</a>
<!--  Scripts-->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>
<script src="js/install.js"></script>
</body>
</html>
