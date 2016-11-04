function modificaDocente(user_id, quanti, page, filtro) {
    if (($("#nome" + user_id).val() == "") || ($("#cognome" + user_id).val() == "") || ($("#username" + user_id).val() == ""))
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
    else {
        var posting = $.post(
            '../include/modificaUtente.php', {
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
                aggiornaDettagliUtenti(0);
            } else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
            }
        });
    }
}


function aggiornaDettagliUtenti(level) {
    quanti = $("#quanti").val();
    console.log(quanti);
    if(quanti<=0){
      quanti = 20;
    }
    filtro = $("#filtro").val();
    console.log(filtro);
    pagina = $("#page").val();
    var posting = $.post(
        '../include/elencoUtenti.php', {
            "level": level,
            "quanti": quanti,
            "page": pagina,
            "filtro": filtro
        }
    );
    posting.done(function(data) {
        if (level == 0){
            var html = '  <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO STUDENTI</h4></li><li class="collection-item center">'+$("#dettagliStudenti li:nth-child(2)").html()+"</li>";
            $("#dettagliStudenti").html(html+data);
            $("#dettagliStudenti input").focus();
            $("#dettagliStudenti input").first().focus();
        }
        if (level == 1){
            var html = '  <li class="collection-header primary-text center"><h4 class="condensed light">ELENCO DOCENTI</h4></li><li class="collection-item center">'+$("#dettagliDocenti li:nth-child(2)").html()+"</li>";
            $("#dettagliDocenti").html(data);
            $("#dettagliDocenti input").focus();
            $("#dettagliDocenti input").first().focus();
        }
          Materialize.updateTextFields();
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
            aggiornaDettagliUtenti(level);
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

(function($) {
    $(function() {
        if($("body#docenti").length>0){
          aggiornaDettagliUtenti(1);
        }
        if($("body#studenti").length>0){
          aggiornaDettagliUtenti(0);
        }


        $(document).on("submit","#filtraDocenti", function(e) {
            e.preventDefault();
            aggiornaDettagliUtenti(1);
        });


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
