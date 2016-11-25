function eliminaAula(id, quanti, page, filtro){
  var posting = $.post(
      './include/eliminaAula.php', {
          id: id
      }
  );
  posting.done(function(data){
    if(data == "SUCCESS"){
      Materialize.toast('Utente eliminato con successo!', 4000);
      aggiornaDettagliAule(quanti, page, fiiltro);
    } else {
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
      console.log(data);
    }
  });
}

function modficaAula(idAula) {
    if (($("#maxStudenti" + idAula).val() == "") || ($("#nomeAula" + idAula).val() == ""))
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Non è possibile lasciare un campo vuoto!', 4000);
    else {
        var posting = $.post(
            './include/modificaAula.php', {
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

function aggiornaDettagliAule(pagina){
  var quanti = $("#quanti").val();
  if(quanti<=0){
    quanti = 20;
  }
  var filtro = $("#filtro").val();
  if(pagina == undefined){
    pagina = 1;
  }
  var posting = $.post(
      './include/elencoDettagliAule.php', {
          "quanti": quanti,
          "page": pagina,
          "filtro": filtro
      }
  );
  posting.done(function(data) {
      $("#dettagliAule").html(data);
      Materialize.updateTextFields();
      $('select').material_select();
  });
}

(function($) {
    $(function() {
      aggiornaDettagliAule();
        $("#aggiungi-aula").submit(function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var posting = $.post(
                './include/aggiungiAula.php', {
                    submit: 1,
                    nomeAula: $("#nomeAula").val(),
                    maxStudenti: $("#maxStudenti").val()
                });        $(document).on("submit","#filtraDocenti", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            aggiornaDettagliUtenti(1);
        });
            posting.done(function(data) {
                if (data == "SUCCESS") {
                    Materialize.toast('Aula aggiunto con successo!', 4000);
                    aggiornaDettagliAule();
                } else if (data == "EXISTS") {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Aula già esistente', 4000);
                    console.log(data);
                } else {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                    console.log(data);
                }
            });
        });

        $(document).on("submit","#filtraAule", function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            aggiornaDettagliAule();
        });

    });
})(jQuery);
