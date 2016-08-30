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
        $("#modal-ore").openModal();
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
        aggiornaListaCorsi();
        $('.modal-trigger').leanModal();

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
