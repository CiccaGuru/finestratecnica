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


function modificaStudente(user_id, quanti, page, filtro) {
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


function aggiornaDettagliUtenti(level, pagina) {
    quanti = $("#quanti").val();
    console.log(quanti);
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
        if (level == 0){
            $("#dettagliStudenti").html(data);
        }
        if (level == 1){
            $("#dettagliDocenti").html(data);
        }
          Materialize.updateTextFields();
          $('select').material_select();
    });
}

function eliminaUtente(id, quanti, page, filtro, level) {
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
