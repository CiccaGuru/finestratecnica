function aggiungiCorso() {
  if(!$("#aggiungiCorso").hasClass("disabled")){
    if(($("#titolo").val()=="")||($("#descriz").val()=="")||($("#selezionaClassi").val().length==0)||($("#ChipsDocenti .chip").length==0)){
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Completare tutti i campi nei dettagli corso!', 4000);
      return false;
    }
    $(".ora_da_inserire").each(function(i){
      if(($("#selezionaGiorno"+i).val=="")||($("#selezionaOra"+i).val()=="")||($("selezionaAula"+i).val()=="")){
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Completare tutti i campi nei dettagli ore!', 4000);
        return false;
      }
    });
    $.post('../include/aggiungiCorso.php',
    {
      titolo: $("#titolo").val(),
      descriz: $("#descriz").val(),
      tipo: $("#ins-corso input[name=tipo]:checked").val(),
      continuita: $("#ins-corso input[name=continuita]:checked").val(),
      "classi[]": $("#selezionaClassi").val()
    }).done(function(data) {
      if (isNaN(data)) {
        console.log(data);
        return false;
      }
      var idCorso = data;
      var docenti = listaDocentiCorso();
      $.post('../include/assegnaCorsoDocenti.php',
        {
          "idCorso":idCorso,
          "docenti":docenti
        }).done(function(data){
        if(data != "SUCCESS"){
          Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (1). Controlla la console', 4000);
          eliminaCorso(idCorso);
          return false;
        }
      });

      $(".ora_da_inserire").each(function(i) {
        var ora = (parseInt($("#selezionaGiorno" + i).val()) - 1) * 6 + parseInt($("#selezionaOra" + i).val());
        $.post(
          '../include/aggiungiOra.php', {
            "idcorso": idCorso,
            "titolo": $("#nomeOra" + i).val(),
            "ora": ora,
            "idAula": $("#selezionaAula" + i).val()
          }).done(function(data) {
            if (data != "SUCCESS INSERT ORA") {
              Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (2). Controlla la console', 4000);
              console.log(data);
              eliminaCorso(idCorso);
              return false;
            }
          });
        });
        Materialize.toast('<i class="material-icons green-text style="margin-right:0.25em">done</i> Corso aggiunto con successo!', 4000);
        aggiornaListaCorsi();
      });
    }
  }

function listaDocentiCorso(){
  listaDocenti = []
  $("#ChipsDocenti .chip").each(function(){
    listaDocenti.push($(this).data("iddocente"));
  });
  return listaDocenti;
}

function listaDocentiCorsoDettagli(){
  listaDocenti = []
  $("#ChipsDocentiDettagli .chip").each(function(){
    listaDocenti.push($(this).data("iddocente"));
    console.log($(this).data("iddocente"));
  });
  return listaDocenti;
}

function qualeListaChipDocenti(){ // 1 se è aggiunta corso, 0 se è modifica corso
  return $("#modal-ore").css("opacity")== "0" || $("#modal-ore").css("display")== "none";
}

function aggiornaListaDocenti() {
  keyword = $("#cercaScegliDocenti").val();
  if(qualeListaChipDocenti()) {
    listaDocenti = listaDocentiCorso();
  }
  else{
    listaDocenti = listaDocentiCorsoDettagli();
  }

  $.post(
        '../include/elencoScegliDocenti.php', {
            "keyword": keyword,
            "listaDocenti": listaDocenti
        }
    ).done(function(data) {
        $("#elencoScegliDocenti").html(data);
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
  $('#modalCorsiIncompatibili').modal("open");
}

function mostraModalScegliDocenti(){
  aggiornaListaDocenti();
  $('#modalScegliDocenti').modal("open");
}

function mostraModalCorsiObbligatori(id){
  aggiornaListaCorsiObbligatori(id);
  $('#modalCorsiObbligatori').modal("open");
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

        aggiornaListaOre(id);
        aggiornaChipsIncompatibili(id);
        aggiornaChipsObbligatori(id);
        aggiornaChipsDocentiDettagli(id);
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

function aggiornaListaOre(id){
  posting = $.post(
    "../include/elencoOre.php",
    {
        "idCorso":id
    });
    posting.done(function(data){
      $("#listaOreModifica").html(data);
      $.post('../include/elencoAule.php').done(function(data){
        $(".selezionaAula").html(data);
      });
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
  $(".oraLista").each(function(i){
    var ora = (parseInt($("#selezionaGiornoModificaOre" + i).val()) - 1) * 6 + parseInt($("#selezionaOraModifcaOre" + i).val());
    $.post(
      '../include/modificaOra.php',
      {
        "idOra":$(this).data("idLezione"),
        "titolo":$("#titoloModificaOre"+i).val(),
        "aula":$("selezionaAulaModificaOre"+i).val(),
        "giorno": $("selezionaGiornoModificaOre"+i).val(),
        "ora": ora
      }
    ).done(function(data){
      if(data!="SUCCESS"){
        console.log(data);
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
        return false;
      }
    });
  });
  $.post(
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
  ).done(function(data){
    if(data!="SUCCESS"){
      console.log(data);
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      return false;
    }
  });
  var listaDocenti = listaDocentiCorsoDettagli();
  if(listaDocenti.length==0){
    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> E\' necessario scegiere almeno un docente.', 4000);
    return false;
  }else{
  $.post(
    '../include/modificaDocentiCorso.php',
    {
      "idCorso":idCorso,
      "docenti[]":listaDocenti
    }
  ).done(function(data){
    if(data!="SUCCESS"){
      console.log(data);
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      return false;
    }
    else{
      Materialize.toast('Corso modificato con successo.', 4000);
    }
  });
}
aggiornaListaCorsi();
}


function aggiornaChipsIncompatibili(idCorso){
  var   posting = $.post(
      "../include/mostraChipsIncompatibili.php",
      {
        "idCorso": idCorso
      });
      posting.done(function(data){
        $("#ChipsIncompatibili").html(data);
      });
}

function aggiornaChipsDocentiDettagli(idCorso){
  var   posting = $.post(
      "../include/mostraChipsDocentiDettagli.php",
      {
        "idCorso": idCorso
      });
      posting.done(function(data){
        $("#ChipsDocentiDettagli").html(data);
      });
}


function aggiornaChipsObbligatori(idCorso){
  var   posting = $.post(
      "../include/mostraChipsObbligatori.php",
      {
        "idCorso": idCorso
      });
      posting.done(function(data){
        $("#ChipsObbligatori").html(data);
      });
}

function aggiungiScegliDocenti(idDocente, nomeDocente){
  if(qualeListaChipDocenti()){
    $("#ChipsDocenti").append('<div class="chip" data-iddocente = "'+idDocente+'">'+nomeDocente+' <i class="close material-icons">close</i></div>');
  }
  else{
    $("#ChipsDocentiDettagli").append('<div class="chip" data-iddocente = "'+idDocente+'">'+nomeDocente+'<i class="close material-icons">close</i></div>');
  }
  $("#modalScegliDocenti").modal("close");
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
      $("#modalCorsiIncompatibili").modal("close");
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
      $("#modalCorsiObbligatori").modal("close");
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

        $("#ore").keyup(function() {
            var val = $("#ore").val();
            if((val == "") || (val == 0)){
                $("#ore_future").html('<div class="center-align italic" style="margin:2em;">Scegliere un numero di ore</div>');
                $("#aggiungiCorso").addClass("disabled");
            }
            else if (isNaN(val)){
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore non valido', 4000);
                $("#aggiungiCorso").addClass("disabled");
            }
            else {
              if (val > 20){
                  Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore troppo alto, massimo 20', 4000);
                  $("#ore").val(20);
                  val = 20;
                }
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
                    //$("#wait").fadeOut();
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

        $("#cercaScegliDocenti").keyup(function(){
          aggiornaListaDocenti();
        });


        $("#cercaCorsiObbligatori").keyup(function(){
          aggiornaListaCorsiObbligatori();
        });


        $("#aggiungiCorso").click(function(){
          aggiungiCorso();
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
