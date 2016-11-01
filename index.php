<?php if(!file_exists("include/config.php")){
  header("location: install.php");
}?>
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

<body id="index">

    <div class="row" style="margin-top:auto; margin-bottom:auto">
      <div class="col s12 m8 l4 offset-l4 offset-m2">
        <div id="login" class="card z-depth-4">
            <div id="titoloLogin" class="condensed light primary lighten-1 white-text"> Settimana tecnica</div>
          <div class="card-content">
            <form id="form-login" method="post">
              <div class="row row-margin">
                <div class="input-field col s12">
                  <i class="material-icons prefix">account_circle</i>
                  <input name="username" id="username" type="text" class="validate">
                  <label for="username">Username</label>
                </div>
              </div>
              <div class="row row-margin">
                <div class="input-field col s12">
                  <i class="material-icons prefix">vpn_key</i>
                  <input name="password" id="password" type="password" class="validate">
                  <label for="password">Password</label>
                </div>
              </div>
              <div class="right-align">
                <button id="send" name="submit" style="font-size:120%;" class=" condensed primary-text waves-effect waves-primary btn-flat">
                  LOGIN
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  <div id="wait" class="center-align valign-wrapper">
    <div id="contenitore-cerchio" class="valign">
      <div class="preloader-wrapper big active">
        <div class="spinner-layer spinner-primary-only">
          <div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="center-align accent-text condensed">
      &copy 2016 Roberto Ciccarelli
    </div>
  </footer>
  <!--  Scripts-->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/jquery-ui.min.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/init.js"></script>
  <script src="js/login.js"></script>
</body>
</html>
