function modificaDocente(user_id, quanti, page, filtro) {
    if (($("#nome" + user_id).val() == "") || ($("#cognome" + user_id).val() == "") || ($("#username" + user_id).val() == ""))
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
    else {
        var posting = $.post(
            './include/modificaUtente.php', {
                id: user_id,
                nome: $("#nome" + user_id).val(),
                cognome: $("#cognome" + user_id).val(),
                username: $("#username" + user_id).val(),
            }
        );
        posting.done(function(data) {
            if (data == "SUCCESS") {
                Materialize.toast('Utente modificato con succeso!', 4000);
                aggiornaDettagliUtenti(1);
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}


function modificaStudente(user_id) {
    if (($("#nome" + user_id + "Studente").val() == "") ||
        ($("#cognome" + user_id + "Studente").val() == "") ||
        ($("#username" + user_id + "Studente").val() == "") ||
        ($("#classeStudente" + user_id).val() == "")) {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
        console.log($("#username" + user_id + "Studente").val());
    } else {
        var posting = $.post(
            './include/modificaUtente.php', {
                id: user_id,
                nome: $("#nome" + user_id + "Studente").val(),
                cognome: $("#cognome" + user_id + "Studente").val(),
                username: $("#username" + user_id + "Studente").val(),
                classe: $("#classeStudente" + user_id).val()
            }
        );
        posting.done(function(data) {
            if (data == "SUCCESS") {
                Materialize.toast('Utente modificato con succeso!', 4000);
                aggiornaDettagliUtenti(0);
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}

function modificaStudenteMobile(user_id) {
    if (($("#nome" + user_id + "StudenteMobile").val() == "") ||
        ($("#cognome" + user_id + "StudenteMobile").val() == "") ||
        ($("#username" + user_id + "StudenteMobile").val() == "")) {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
        console.log($("#username" + user_id + "Studente").val());
    } else {
        var posting = $.post(
            './include/modificaUtente.php', {
                id: user_id,
                nome: $("#nome" + user_id + "StudenteMobile").val(),
                cognome: $("#cognome" + user_id + "StudenteMobile").val(),
                username: $("#username" + user_id + "StudenteMobile").val()
            }
        );
        posting.done(function(data) {
            if (data == "SUCCESS") {
                Materialize.toast('Utente modificato con succeso!', 4000);
                aggiornaDettagliUtenti(0);
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}


function aggiornaDettagliUtenti(level, pagina) {
    quanti = $("#quanti").val();
    if(quanti<=0){
      quanti = 20;
    }
    filtro = $("#filtro").val();
    if(pagina == undefined){
      pagina = 1;
    }
    var posting = $.post(
        './include/elencoUtenti.php', {
            "level": level,
            "quanti": quanti,
            "page": pagina,
            "filtro": filtro
        }
    );
    posting.done(function(data) {
      $("#dettagliStudenti .collection-item.studenti").remove();
      $("#dettagliStudenti .collection-item.paginazione").remove();
      var json = JSON.parse(data);
        if (level == 0){
            if(json == 0){
              $("#dettagliStudenti").append('\
                <li class="collection-item">\
                    <div class="accent-text condensed center-align" style="font-size:150%; margin:1em;">Nessun risultato trovato</div>\
                </li>');
            }
            else{
              $.each(json["studenti"], function(index, utente){
                $("#dettagliStudenti").append('\
                <li class="collection-item hide-on-med-and-down studenti row valign-wrapper">\
                  <div class="col s1 accent-text">\
                    <i class="material-icons waves-effect waves-accent waves-circle" style="border-radius:50%;" onclick="eliminaUtente('+utente["id"]+', 0)">close</i>\
                  </div>\
                  <div class="col s1 bold">\
                    ID:'+utente["id"]+'\
                  </div>\
                  <div class="input-field col s2 valign">\
                    <input id="nome'+utente["id"]+'Studente" type="text" class="validate valign" value="'+utente["nome"]+'" requiaccent>\
                    <label for="nome'+utente["id"]+'Studente">Nome</label>\
                  </div>\
                  <div class="input-field col s2 valign">\
                    <input id="cognome'+utente["cognome"]+'Studente" type="text" class="validate valign" value="'+utente["cognome"]+'" requiaccent>\
                    <label for="cognome'+utente["cognome"]+'Studente">Cognome</label>\
                  </div>\
                  <div class="input-field col s2 valign">\
                    <input id="username'+utente["id"]+'>Studente" type="text" class="validatevalign" value="'+utente["username"]+'" requiaccent>\
                    <label for="nom'+utente["id"]+'Studente">Username</label>\
                  </div>\
                  <div class="input-field col s1 valign">\
                    <a class="valgin waves-accent waves-effect btn-flat condensed" onclick="mostraModalScegliClasse('+utente["id"]+')">CLASSE</a>\
                  </div>\
                  <div class="col s2 cente valign">\
                    <p style="margin-bottom:5px;">\
                      <a onclick="modificaStudente('+utente["id"]+')" class="waves-effect center-align waves-accent btn-flat accent-text valign" style="width:98%">\
                        Modifica\
                      </a>\
                    </p>\
                    <p style="margin-top:5px;">\
                      <a class="waves-effect waves-accent center-align btn-flat accent-text valign" onclick="mostraOrarioStudente('+utente["id"]+')" style="width:98%;">Orario</a>\
                    </p>\
                  </div>\
                  <div class="col s1 center valign" style="padding:0px;">\
                    <a class="waves-effect small-icon condensed waves-accent fill-width fake-button valign accent-text" onclick="passwordReset('+utente["id"]+')" >\
                      <i class="material-icons ">refresh</i> <br/>RESET\
                    </a>\
                  </div>\
                </li>\
                ');
                $("#dettagliStudentiMobile").append('\
                <li>\
                <div class="collapsible-header">\
                  <div class="row">\
                  <div class="col s2 bold">\
                    ID:'+utente["id"]+'\
                  </div>\
                  <div class="col s3 valign">\
                    '+utente["nome"]+'\
                  </div>\
                  <div class="col s3 valign">\
                    '+utente["cognome"]+'\
                  </div>\
                  <div class="col s3 valign">\
                    '+utente["username"]+'\
                  </div>\
                  <div class="col s1 left">\
                  <i class="material-icons">arrow_drop_down</i>\
                  </div>\
                  </div>\
                </div>\
                <div class="collapsible-body">\
                <div class="container">\
                  <div class="input-field">\
                    <input id="nome'+utente["id"]+'StudenteMobile" type="text" class="validate valign" value="'+utente["nome"]+'" requiaccent>\
                    <label for="nome'+utente["id"]+'StudenteMobile">Nome</label>\
                  </div>\
                  <div class="input-field">\
                    <input id="cognome'+utente["cognome"]+'StudenteMobile" type="text" class="validate valign" value="'+utente["cognome"]+'" requiaccent>\
                    <label for="cognome'+utente["cognome"]+'StudenteMobile">Cognome</label>\
                  </div>\
                  <div class="input-field">\
                    <input id="username'+utente["id"]+'>StudenteMobile" type="text" class="validatevalign" value="'+utente["username"]+'" requiaccent>\
                    <label for="nom'+utente["id"]+'StudenteMobile">Username</label>\
                  </div>\
                  <div class="center">\
                    <a class="valgin waves-light waves-effect btn accent condensed" onclick="mostraModalScegliClasse('+utente["id"]+')">SELEZIONA CLASSE</a>\
                  </div>\
                  <div class="row" style="margin-top:2em;">\
                  <div class="col s6 center valign">\
                    <p style="margin:5px;padding:0.2em;">\
                      <a onclick="modificaStudenteMobile('+utente["id"]+')" class="waves-effect center-align waves-accent btn-flat accent-text valign" style="width:98%">\
                        Modifica\
                      </a>\
                    </p>\
                    <p style="margin:5px;padding:0.2em;">\
                      <a class="waves-effect waves-accent center-align btn-flat accent-text valign" onclick="mostraOrarioStudente('+utente["id"]+')" style="width:98%;">Orario</a>\
                    </p>\
                  </div>\
                  <div class="col s6 center valign" style="padding:0px;">\
                    <a class="waves-effect small-icon condensed waves-accent fill-width fake-button valign accent-text" onclick="passwordReset('+utente["id"]+')" >\
                      <i class="material-icons ">refresh</i> <br/>RESET\
                    </a>\
                    </div>\
                    </div>\
                  </div>\
                </li>\
                ');
              });
            }
            if(json["numRisultato"]>quanti){
            var classePrimaPagina = "";
            if(pagina==1){
              classePrimaPagina = 'disabled';
            }
            else{
              classePrimaPagina = 'waves-effect waves-accent';
            }
            var classeUltimaPagina = "";
            if(pagina*quanti>=json["numRisultato"]){
              classeUltimaPagina = "disabled";
            }
            else{
              classeUltimaPagina = 'waves-effect waves-accent';
            }
            var impaginazione = '\
            <li class="collection-item center paginazione">\
              <ul class="pagination">\
                <li class="'+classePrimaPagina+'" onclick="aggiornaDettagliUtenti(0, '+(pagina-1)+')">\
                  <a href="#!"><i class="material-icons">chevron_left</i></a>\
                </li>';
                var i=1;
                while(i*quanti<=json["numRisultato"]){
                  var classePagina = "";
                  if(i == pagina){
                    classePagina = "accent";
                  }
                  else{
                    classePagina = 'waves-effect waves-accent';
                  }
                  var classeNumeroPagina = "";
                  if(i == pagina){
                    classeNumeroPagina = "text-on-accent";
                  }
                  impaginazione+='<li onclick="aggiornaDettagliUtenti(0, '+i+')" class="'+classePagina+'"><a href="#!" class="'+classeNumeroPagina+'">'+i+'</a></li>';
                  i++;
                }

                impaginazione += '\
                    <li class="'+classeUltimaPagina+'" onclick="aggiornaDettagliUtenti(0,'+(pagina+1)+')">\
                      <a href="#!"><i class="material-icons">chevron_right</i></a>\
                    </li>\
                  </ul>\
                </li>';
                $("#dettagliStudenti").append(impaginazione);
            }
        }
        if (level == 1){
            $("#dettagliDocenti").html(data);
        }
          Materialize.updateTextFields();
          $('select').material_select();
    });
}

function eliminaUtente(id, level) {
    var posting = $.post(
        './include/eliminaUtente.php', {
            id: id
        }
    );
    posting.done(function(data) {
        if (data == "SUCCESS") {
            Materialize.toast('Utente eliminato con successo!', 4000);
            aggiornaDettagliUtenti(level);
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function passwordReset(user_id) {
    var posting = $.post(
        './include/passwordReset.php', {
            id: user_id
        }
    );
    posting.done(function(data) {
        if (data == "SUCCESS") {
            Materialize.toast('Password reimpostata con successo!', 4000);
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function mostraOrarioStudente(idUtente) {
    var posting = $.post(
        './include/generaOrario.php', {
            "id": idUtente
        });
    posting.done(function(data) {
        $("#modal-orario .modal-content").html("<div class='container'>" + data + "</div>");
        $("#modal-orario").modal("open");
    });
}

function mostraModalScegliClasse(idUtente){
  $.post(
    "./include/classeStudente.php",
    {
      "id":idUtente
    })
    .done(function(data){
      $("#modal-scegli-classe .modal-content").html(data);
      $("#modal-scegli-classe").modal("open");
      $('select').material_select();
    });

}

function mostraOrarioDocente(idUtente) {
    var posting = $.post(
        './include/generaOrario.php', {
            "id": idUtente
        });
    posting.done(function(data) {
        $("#modal-orario .modal-content").html("<div class='container'>" + data + "</div>");
        $("#modal-orario").modal("open");
    });
}

function cercaStudente() {
    var oraNum = (parseInt($("#selezionaGiornoCerca").val()) - 1) * 6 + parseInt($("#selezionaOraCerca").val());
    var posting = $.post(
        './include/cercaStudente.php', {
            nome: $("#nomeStudenteCerca").val(),
            cognome: $("#cognomeStudenteCerca").val(),
            ora: oraNum
        }
    );
    posting.done(function(data) {
        $("#modal-orario .modal-content").html(data);
        $("#modal-orario").modal("open");
    });

}

(function($) {
    $(function() {
        if($("body#docenti").length>0){
          aggiornaDettagliUtenti(1);
        }
        if($("body#studenti").length>0){
          aggiornaDettagliUtenti(0);
        }


        $("#selezionaClasseStudente").change(function(){
          $.post("include/elencoSezioniAnno.php",
              {
                  "anno":$(this).val()
              })
          .done(function(data){
            console.log(data);
            $("#selezionaSezioneStudente").html(data);
            $('select').material_select();
          });
        });

        $(document).on("change", "#cambiaClasseStudente", function(){
          console.log("cc");
          $.post("include/elencoSezioniAnno.php",
              {
                  "anno":$(this).val()
              })
          .done(function(data){
            console.log(data);
            $("#cambiaSezioneStudente").html(data);
            $('select').material_select();
          });
        });

        $(document).on("click", "#applicaCambiaClasse", function(e){
          $.post("./include/modificaClasseStudente.php",
            {
              "idUtente":$("#idUtenteClasse").val(),
              "classe":$("#cambiaClasseStudente").val(),
              "idSezione":$("#cambiaSezioneStudente").val()
            })
            .done(function(data){
              if(data == "SUCCESS"){
                Materialize.toast('Classe modificata con successo', 4000);
                $("#modal-scegli-classe").modal("close");
              }
              else{
                Materialize.toast('Si è verificato un errore', 4000);
                console.log(data);
              }
            })
        })

        $(document).on("submit","#filtraDocenti", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            aggiornaDettagliUtenti(1);
        });

        $(document).on("submit","#filtraStudenti", function(e) {
          e.preventDefault();
          e.stopImmediatePropagation();
          aggiornaDettagliUtenti(0);
        });


        $("#aggiungi-docente").submit(function(e) {
            e.preventDefault();
            if ($("#password").val() != $("#ripeti_password").val())
                Materialize.toast('Le due password non coincidono!', 4000);
            else {
                var posting = $.post(
                    './include/aggiungiDocente.php', {
                        submit: 1,
                        username: $("#username").val(),
                        password: $("#password").val(),
                        nome: $("#nome").val(),
                        cognome: $("#cognome").val()
                    }
                );
                posting.done(function(data) {
                    if (data == "SUCCESS") {
                        Materialize.toast('Docente aggiunto con successo!', 4000);
                        aggiornaDettagliUtenti(1);
                    } else if (data == "EXISTS") {
                        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Nome utente già esistente', 4000);
                        console.log(data);
                    } else {
                        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                        console.log(data);
                    }

                });
            }
        });

            $("#aggiungi-studente").submit(function(e) {
            e.preventDefault();
            if ($("#passwordStudente").val() != $("#ripeti_passwordStudente").val())
                Materialize.toast('Le due password non coincidono!', 4000);
            else {
                var posting = $.post(
                    './include/aggiungiStudente.php', {
                        submit: 1,
                        username: $("#usernameStudente").val(),
                        classe: $("#selezionaClasseStudente").val(),
                        sezione: $("#selezionaSezioneStudente").val(),
                        password: $("#passwordStudente").val(),
                        nome: $("#nomeStudente").val(),
                        cognome: $("#cognomeStudente").val()
                    }
                );
                posting.done(function(data) {
                    if (data == "SUCCESS") {
                        Materialize.toast('Studente aggiunto con successo!', 4000);
                        aggiornaDettagliUtenti(0);
                    } else if (data == "EXISTS") {
                        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Nome utente già esistente', 4000);
                        console.log(data);
                    } else {
                        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                        console.log(data);
                    }

                });
            }
        });
    });
})(jQuery);
