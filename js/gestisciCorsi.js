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

function aggiornaListaCorsiIncompatibili(){
  idCorso = $("#idCorsoAttuale").data("idcorso");
  keyword = $("#cercaCorsiIncompatibili").val();
  console.log(keyword);
  var posting = $.post(
      '../include/elencoCorsiIncompatibili.php', {
          "keyword": keyword,
          "id": idCorso
      }
  );
  posting.done(function(data) {
    $("#elencoCorsiIncompatibili").html (data);
  });
}

function aggiornaListaCorsiObbligatori(){
  idCorso = $("#idCorsoAttuale").data("idcorso");
  keyword = $("#cercaCorsiObbligatori").val();
  console.log(keyword);
  var posting = $.post(
      '../include/elencoCorsiObbligatori.php', {
          "keyword": keyword,
          "id": idCorso
      }
  );
  posting.done(function(data) {
    $("#elencoCorsiObbligatori").html (data);
  });
}

function mostraModalCorsiIncompatibili(id){
  aggiornaListaCorsiIncompatibili(id);
  $('#modalCorsiIncompatibili').openModal();
}

function mostraModalCorsiObbligatori(id){
  aggiornaListaCorsiObbligatori(id);
  $('#modalCorsiObbligatori').openModal();
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
        aggiornaChipsIncompatibili(id);
        aggiornaChipsObbligatori(id);
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

function vaiPagina(pagina){
  console.log(pagina);
  var paginaMax = $("#paginaMax").html();
  if(pagina>paginaMax){
    pagina = paginaMax;
  }
  if(pagina<1){
    pagina = 1;
  }
  $("#pagina").html(pagina);
  aggiornaListaCorsi();
}
function aggiornaListaCorsi(){
  var posting = $.post('../include/elencoCorsi.php', {
    "mostra": $("#mostra").val(),
    "concontinuita": $("#concontinuita:checked").length,
    "senzacontinuita": $("#senzacontinuita:checked").length,
    "recupero": $("#recuperoCerca:checked").length,
    "approfondimento": $("#approfondimentoCerca:checked").length,
    "keyword": $("#parolaChiave").val(),
    "pagina": $("#pagina").html()
  });
  posting.done(function(data) {
      $("#dettagliCorsiContenuto").html(data);
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

function aggiornaChipsIncompatibili(idCorso){
  console.log("aggiorno");
  var   posting = $.post(
      "../include/mostraChipsIncompatibili.php",
      {
        "idCorso": idCorso
      });
      posting.done(function(data){
        $("#ChipsIncompatibili").html(data);
      });
}


function aggiornaChipsObbligatori(idCorso){
  console.log("aggiorno");
  var   posting = $.post(
      "../include/mostraChipsObbligatori.php",
      {
        "idCorso": idCorso
      });
      posting.done(function(data){
        $("#ChipsObbligatori").html(data);
      });
}

function aggiungiCorsoIncompatibile(idCorso1, idCorso2){
  var   posting = $.post(
      "../include/aggiungiCorsoIncompatibile.php",
      {
        "idCorso1": idCorso1,
        "idCorso2": idCorso2
      });
  posting.done(function(data){
    if(data=="SUCCESS"){
      $("#modalCorsiIncompatibili").closeModal();
      aggiornaChipsIncompatibili(idCorso1);
      Materialize.toast("Incompatibilità aggiunta con successo!", 2000);
    }
    else{
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      console.log(data);
    }
  });
}


function aggiungiCorsoObbligatorio(idCorso, idClasse){
  var   posting = $.post(
      "../include/aggiungiCorsoObbligatorio.php",
      {
        "idClasse": idClasse,
        "idCorso": idCorso
      });
  posting.done(function(data){
    if(data=="SUCCESS"){
      $("#modalCorsiObbligatori").closeModal();
      aggiornaChipsObbligatori(idCorso);
      Materialize.toast("Corso obbligatorio aggiunto con successo!", 2000);
    }
    else{
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      console.log(data);
    }
  });
}


function eliminaIncompatibilita(idCorso1, idCorso2){
  var   posting = $.post(
      "../include/rimuoviCorsoIncompatibile.php",
      {
        "idCorso1": idCorso1,
        "idCorso2": idCorso2
      });
  posting.done(function(data){
    if(data=="SUCCESS"){
      Materialize.toast("Incompatibilità rimossa con successo!", 2000);
      aggiornaChipsIncompatibili(idCorso1);
    }
    else{
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      console.log(data);
    }
  });
}

function eliminaObbligatori(idClasse, idCorso){
  var   posting = $.post(
      "../include/rimuoviCorsoObbligatorio.php",
      {
        "idClasse": idClasse,
        "idCorso": idCorso
      });
  posting.done(function(data){
    if(data=="SUCCESS"){
      Materialize.toast("Corso obbligatorio rimosso con successo!", 2000);
      aggiornaChipsObbligatori(idCorso);
    }
    else{
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      console.log(data);
    }
  });
}

(function($) {
    $(function() {
        aggiornaListaCorsi();
        aggiornaListaDocenti();
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

        $("#formCercaCorsi").submit(function(e){
            e.preventDefault();
            aggiornaListaCorsi();
            Materialize.toast("Ricerca completata", 2000);
        });

        $("#cercaCorsiIncompatibili").keyup(function(){
          aggiornaListaCorsiIncompatibili();
        });

        $("#cercaCorsiObbligatori").keyup(function(){
          aggiornaListaCorsiObbligatori();
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
