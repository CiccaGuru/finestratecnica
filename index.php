<!DOCTYPE html>
<html lang="it">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <title>Settimana tecnica</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen"/>
</head>

<body id="index">
  <nav class="light-blue">
    <div class="nav-wrapper">
      <a href="#" class="brand-logo center light">Settimana tecnica</a>
    </div>
  </nav>
  <div id="help" class="right hide-on-small-only">
    <a class="btn-floating btn-large waves-effect waves-light red">
      <i id="back" class="material-icons">arrow_back</i>
      <i id="first" class="material-icons">more_horiz</i>
    </a>
  </div>

  <div class="container">
    <div class="hide-on-med-and-down h-5em"></div>
    <div class="row">
      <div class="col s12 m6 offset-m3 center-align">
        <div id="login" class="card">
          <div class="card-content">
            <span class="card-title blue-text">Login</span>
            <form id="form-login" method="post">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix">account_circle</i>
                  <input name="username" id="username" type="text" class="validate">
                  <label for="username">Username</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix">vpn_key</i>
                  <input name="password" id="password" type="password" class="validate">
                  <label for="password">Password</label>
                </div>
              </div>
              <div class="center-align">
                <button id="send" name="submit" class="btn-floating btn-large waves-effect waves-light center-align red">
                  <i class="material-icons">send</i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div id="infos" class="card hoverable">
          <div class="card-content">
            <span class="card-title blue-text">Informazioni</span><br/><br/>
            <b>Iscrizioni ai corsi</b><br/>
            <p class="left-align">
              E' possibile effettuare l'accesso usando le credenziali inviate per email.<br/>
              In caso di problemi, contattate Roberto Ciccarelli (robyciccarelli.rc@gmail.com) o Filippo Quattrocchi (quattrocchifilippo@gmail.com).
              <br/><br/>
              Realizzato da Roberto Ciccarelli.
            </p>
          </div>
        </div>
      </div>
    </div>
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
  </div>

  <footer>
    <div class="center-align red-text condensed">
      Realizzato da Roberto Ciccarelli e Filippo Quattrocchi
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
