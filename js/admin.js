function aggiornaListaDocenti() {
    var posting = $.post(
        '../include/generaElencoDocenti.php', {
            1: 1
        }
    );
    posting.done(function(data) {
        $("#selezionaDocenti").html('<option value="" disabled selected class="grey-text">Seleziona insegnante</option>' + data);
        $('select').material_select();
    });
}


function modificaDocente(user_id, quanti, page, filtro) {
    if (($("#nome" + user_id).val() == "") || ($("#cognome" + user_id).val() == "") || ($("#username" + user_id).val() == ""))
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
    else {
        var posting = $.post(
            '../include/modificaUtente.php', {
                id: user_id,
                nome: $("#nome" + user_id).val(),
                cognome: $("#cognome" + user_id).val(),
                username: $("#username" + user_id).val(),d
            }
        );
        posting.done(function(data) {
            if (data == "SUCCESS") {
                Materialize.toast('Utente modificato con succeso!', 4000);
                aggiornaDettagliUtenti(quanti, page, filtro, 1);
                aggiornaListaDocenti();
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}


function modificaStudente(user_id, quanti, page, filtro) {
    if (($("#nome" + user_id + "Studente").val() == "") ||
        ($("#cognome" + user_id + "Studente").val() == "") ||
        ($("#username" + user_id + "Studente").val() == "") ||
        ($("#classeStudente" + user_id).val() == "")) {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
        console.log($("#username" + user_id + "Studente").val());
    } else {
        var posting = $.post(
            '../include/modificaUtente.php', {
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
                aggiornaDettagliUtenti(quanti, page, filtro, 0);
                aggiornaListaDocenti();
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}


function aggiornaDettagliUtenti(quantiN, pageN, filtro, level) {
    console.log(filtro);
    var posting = $.post(
        '../include/elencoUtenti.php', {
            "level": level,
            "quanti": quantiN,
            "page": pageN,
            "username": filtro
        }
    );
    posting.done(function(data) {
        if (level == 0)
            $("#dettagliStudenti").html(data);
        if (level == 1)
            $("#dettagliDocenti").html(data);
    });

}

function eliminaAula(id, quanti, page, filtro){
  var posting = $.post(
      '../include/eliminaAula.php', {
          id: id
      }
  );
  posting.done(function(data){
    if(data == "SUCCESS"){
      Materialize.toast('Utente eliminato con successo!', 4000);
      aggiornaDettagliUtenti(quanti, page, filtro, level);
      aggiornaListaDocenti();
    } else {
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
    console.log(data);
}
  });
}

function eliminaUtente(id, quanti, page, filtro, level) {
    var posting = $.post(
        '../include/eliminaUtente.php', {
            id: id
        }
    );
    posting.done(function(data) {
        if (data == "SUCCESS") {
            Materialize.toast('Utente eliminato con successo!', 4000);
            aggiornaDettagliUtenti(quanti, page, filtro, level);
            aggiornaListaDocenti();
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function passwordReset(user_id) {
    var posting = $.post(
        '../include/passwordReset.php', {
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

function aggiungiCorso() {
    var res = 1;
    var posting_corso = $.post(
        '../include/aggiungiCorso.php', {
            titolo: $("#titolo").val(),
            descriz: $("#descriz").val(),
            tipo: $("#ins-corso input[name=tipo]:checked").val(),
            continuita: $("#ins-corso input[name=continuita]:checked").val(),
            iddocente: $("#selezionaDocenti").val(),
            "classi[]": $("#selezionaClassi").val()
        }
    );
    idCorso = -1;
    posting_corso.done(function(data) {
        if (isNaN(data)) {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (1). Controlla la console', 4000);
            console.log(data);
        } else {
            idCorso = data
            res = 1;
            $(".ora_da_inserire").each(function(i) {
                console.log("ORA");
                var ora = (parseInt($("#selezionaGiorno" + i).val()) - 1) * 6 + parseInt($("#selezionaOra" + i).val());
                var posting = $.post(
                    '../include/aggiungiOra.php', {
                        "idcorso": idCorso,
                        "titolo": $("#nomeOra" + i).val(),
                        "ora": ora,
                        "idAula": $("#selezionaAula" + i).val()
                    });
                posting.done(function(data) {
                    if (!(data == "SUCCESS INSERT ORA")) {
                        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (2). Controlla la console', 4000);
                        console.log(data);
                        res = 0;
                        return false;
                    }
                });
            });
            if (res == 1){
                Materialize.toast('<i class="material-icons green-text style="margin-right:0.25em">done</i> Corso aggiunto con successo!', 4000);
                aggiornaListaCorsi();
            }else{
              eliminaCorso(idCorso);
            }
        }
    });

}

function elencoNonAccessi() {
    var posting = $.post(
        '../include/elencoNonAccessi.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "index.php";
        }
        window.location.href = "include/" + data;
        console.log(data);
    });
}


function elencoNonAbbastanza() {
    var posting = $.post(
        '../include/elencoNonAbbastanza.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "index.php";
        }
        window.location.href = "include/" + data;
        console.log(data);
    });
}

function generaRegistrini() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var i = 0;
    var counter = 1;
    $.post('../include/registroCorso.php')
      .done(function(data) {
            if (data == "LOGINPROBLEM") {
                window.location = "../index.php";
            }
            if(data == "SUCCESS"){
              window.location = "/include/tmp/registrini.zip";
            }
            console.log(data);
        });
}

function modficaAula(idAula) {
    if (($("#maxStudenti" + idAula).val() == "") || ($("#nomeAula" + idAula).val() == ""))
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Non è possibile lasciare un campo vuoto!', 4000);
    else {
        var posting = $.post(
            '../include/modificaAula.php', {
                "id": idAula,
                "maxStudenti": $("#maxStudenti" + idAula).val(),
                "nomeAula": $("#nomeAula" + idAula).val()
            });
        posting.done(function(data) {
            if (data == "SUCCESS") {
                Materialize.toast('Aula modificata con successo!', 4000);
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console.', 4000);
            }
        });
    }
}

function generaOreBuche() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var i = 0;
    var counter = 1;
    for (i = 1; i < 24; i++) {
        var posting = $.post(
            '../include/oreBuche.php', {
                "ora": i
            });
        posting.done(function(data) {
            if (data == "LOGINPROBLEM") {
                window.location = "../index.php";
            }
            window.open("../include/tmp/orebuche/" + data, '_blank');
            console.log(data);
            if (counter >= 23) {
                $("#wait").fadeOut();
            }
            $(".determinate").css("width", counter / 23 * 100 + "%");
            counter++;
        });
    }

}

function generaCorsiByDocenti() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var posting = $.post(
        '../include/corsiByDocente.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "../index.php";
        }
        window.location = "../include/tmp/" + data;
        console.log(data);
    });
}


function generaCorsiByTitolo() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var posting = $.post(
        '../include/corsiByTitolo.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "../index.php";
        }
        window.location = "../include/tmp/" + data;
        console.log(data);
    });
}


function generaCorsiByAula() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var posting = $.post(
        '../include/corsiByAula.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "../index.php";
        }
        window.location = "../include/tmp/" + data;
        console.log(data);
    });
}



function generaCorsiByOra() {
    $("#wait").css("height:100%");
    $("#wait").fadeIn();
    var posting = $.post(
        '../include/corsiByOra.php', {
            1: 1
        });
    posting.done(function(data) {
        if (data == "LOGINPROBLEM") {
            window.location = "../index.php";
        }
        window.location = "../include/tmp/" + data;
        console.log(data);
    });
}


function mostraOrarioStudente(idUtente) {
    var posting = $.post(
        '../include/generaOrario.php', {
            "id": idUtente
        });
    posting.done(function(data) {
        $("#modal-orario .modal-content").html("<div class='container'>" + data + "</div>");
        $("#modal-orario").modal("open");
    });
}

function mostraOrarioDocente(idUtente) {
    var posting = $.post(
        '../include/generaOrario.php', {
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
        '../include/cercaStudente.php', {
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

function mostraModalDettagli(id, idDocente) {
    var posting = $.post(
        '../include/mostraOre.php', {
            idCorso: id
        }
    );
    posting.done(function(data) {
        $("#modal-ore").html(data);
        $('select').material_select();
        var posting = $.post(
            '../include/generaElencoDocenti.php', {
                "idDocente": idDocente
            }
        );
        posting.done(function(data) {
            $("#docenteCorsoModifica").html('<option value="" disabled selected class="grey-text">Seleziona insegnante</option>' + data);
            $('select').material_select();
        });
        aggiornaListaOre(id);
        $("#modal-ore").modal("open");
    });
}

function eliminaCorso(id){
    console.log("cane");
    var posting = $.post("../include/eliminaCorso.php", {
      "id":id
    });
    posting.done(function(data) {
        if (data == "SUCCESS") {
            aggiornaListaCorsi();
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function eliminaClasse(id) {
    var posting = $.post('../include/eliminaClasse.php', {
        "id": id
    });
    posting.done(function(data) {
        if (data == "SUCCESS") {
            aggiornaListaSezioni();
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function aggiornaListaOre(id){
  posting = $.post(
    "../include/elencoOre.php",
    {
        "idCorso":id
    });
    posting.done(function(data){
      $("#listaOreModifica").html(data);
      $('select').material_select();
    });
}

function aggiornaListaCorsi(){
  var posting = $.post('../include/elencoCorsi.php');
  posting.done(function(data) {
      $("#dettagliCorsi").html(data);
  });
}

function aggiornaListaSezioni() {
    var posting = $.post('../include/elencoSezioni.php', {
        1: 1
    });
    posting.done(function(data) {
        $("#dettagliSezioni").html('	<li class="collection-header blue-text center"><h4 class="condensed light">ELENCO SEZIONI</h4></li>' + data);
    });
}

function applicaModificaOre(idCorso) {
    console.log("CIAO");
    var posting = $.post(
      '../include/modificaCorso.php',
      {
          "id":idCorso,
          "titolo":$("#titoloCorsoModifica").val(),
          "idDocente":$("#docenteCorsoModifica").val(),
          "tipo": $("input[name=tipoCorsoModifica]:checked").val(),
          "continuita": $("input[name=continuitaCorsoModifica]:checked").val(),
          "descrizione":$("#descrizioneCorsoModifica").val(),
          "classi[]":$("#classiCorsoModifica").val()
      }
    );
    posting.done(function(data){
      console.log(data);
    });
}


(function($) {
    $(function() {
        aggiornaListaDocenti();
        aggiornaListaSezioni();
        aggiornaListaCorsi();
        $("#aggiungi-docente").submit(function(e) {
            e.preventDefault();
            if ($("#password").val() != $("#ripeti_password").val())
                Materialize.toast('Le due password non coincidono!', 4000);
            else {
                var posting = $.post(
                    '../include/aggiungiDocente.php', {
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

        $("#aggiungiClasse").on("click", function() {
            var posting = $.post(
                '../include/creaClasse.php', {
                    "classe": $("#selezionaClasseStudente").val(),
                    "sezione": $("#sezione").val()
                }
            );
            posting.done(function(data) {
                if (data == "SUCCESS") {
                    Materialize.toast('Classe creata con successo!', 4000);
                    aggiornaListaSezioni();
                } else {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                    console.log(data);
                }
            });
        });

        $("#aggiungi-studente").submit(function(e) {
            e.preventDefault();
            if ($("#passwordStudente").val() != $("#ripeti_passwordStudente").val())
                Materialize.toast('Le due password non coincidono!', 4000);
            else {
                var posting = $.post(
                    '../include/aggiungiStudente.php', {
                        submit: 1,
                        username: $("#usernameStudente").val(),
                        classe: $("#selezionaClasseStudente").val(),
                        password: $("#passwordStudente").val(),
                        nome: $("#nomeStudente").val(),
                        cognome: $("#cognomeStudente").val()
                    }
                );
                posting.done(function(data) {
                    if (data == "SUCCESS") {
                        Materialize.toast('Studente aggiunto con successo!', 4000);
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

        $("#inserisciOre").on("click", function() {
            var val = $("#ore").val();
            $("#ore_future").hide();
            if (val == "" || isNaN(val))
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore non valido', 4000);
            else if (val > 20)
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore troppo alto, massimo 20', 4000);
            else {
                $("#wait").css("height:100%");
                $("#wait").fadeIn();
                var posting = $.post(
                    '../include/creaOre.php', {
                        ore: val
                    }
                );
                posting.done(function(data) {
                    $("#ore_future").html('<ul id="elenco_ore">' + data + '</ul> ');
                    $("#elenco_ore").css("opacity: 0");
                    $('#ore_future').show().css("opacity:0");
                    $("#elenco_ore").fadeIn();
                    $('select').material_select();
                    $("#aggiungiCorso").removeClass("disabled");
                    $("#wait").fadeOut();
                });

            }
        });

        $("#aggiungi-aula").submit(function(e) {
            e.preventDefault();
            var posting = $.post(
                '../include/aggiungiAula.php', {
                    submit: 1,
                    nomeAula: $("#nomeAula").val(),
                    maxStudenti: $("#maxStudenti").val()
                });
            posting.done(function(data) {
                if (data == "SUCCESS") {
                    Materialize.toast('Aula aggiunto con successo!', 4000);
                } else if (data == "EXISTS") {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Aula già esistente', 4000);
                    console.log(data);
                } else {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                    console.log(data);
                }
            });
        });

    });
})(jQuery);
