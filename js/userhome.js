function aggiornaCorsi(idCorso){
  var act = 0;
  console.log("AGGIORNO");
  if($("#collapsible"+idCorso).hasClass("active")){
    act = idCorso;
  }
  else {
    act = -1;
  }
  var posting = $.post(
    '../include/listaCorsiStudente.php',
    {"active":act}
  );
  posting.done(function( data ){
    if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    }
    else{
      $("#elencoCorsiStudente").html(data);
      $('.collapsible').collapsible({
        accordion : true
      });
      caricaInfo(idCorso);
      setTimeout(function(){
        $(window).scrollTop($.cookie("scroll"));
      }, 200);
    }
  });
}

function inserisciOra(idOra, idCorso){
  var posting = $.post(
    '../include/iscriviOra.php',
    {
      "id_ora":idOra,
      "id_corso":idCorso
    });
    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Ti sto iscrivendo...', 2000);
        $.cookie("scroll", $(window).scrollTop());
        //  $("#elencoCorsiStudente").fadeOut();
        aggiornaCorsi(idCorso);
        aggiornaOrario();
      }
    else if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    } else{
      console.log(data);
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> '+data, 4000);
    }
    });

  }

function aggiornaOrario(){
  var div = "";
  if($(window).width()>=993){
    div = "card-orario-gen";
  }
 else{
  div = "card-orario-gen-piccolo";
 }
  $("#"+div + " table").animate({opacity:0});
  var posting = $.post(
    '../include/generaOrario.php',
    {1:1});
  posting.done(function(data){
    if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    }else{
      $("#"+div).html('<div class="card-content" style="padding-top:10px;">'+data+'</div>');
      $("#"+div+" table").animate({opacity:1});
      Materialize.toast('Operazione completata!', 2000);
    }
  });
}

function rimuoviOra(idOra, idCorso){
  var posting = $.post(
    '../include/rimuoviOra.php',
    {
      "id_ora":idOra,
      "id_corso":idCorso
    });
    posting.done(function( data ){
      if(data=="SUCCESS"){
        $.cookie("scroll", $(window).scrollTop());
        Materialize.toast('Sto annullando l\'iscrizone...', 2000);
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
      else if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
          Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> '+data, 4000);
          console.log(data);
      }

    });
}

function scegliQuale(ora){
  var posting = $.post(
    '../include/mostraPossibilita.php',
    {
      "ora":ora
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
        $("#modal-scegliquale .modal-content").html(data);
        $("#modal-scegliquale").css("margin-top","3em");
        $("#modal-scegliquale").openModal();
      }
    });
}


function inserisciCorso(idCorso){
  $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("light-blue");
  $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("lighten-2");
  var posting = $.post(
    '../include/iscriviCorso.php',
    {
      "id_corso":idCorso
    });
    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Ti sto iscrivendo...', 2000);
        $.cookie("scroll", $(window).scrollTop());
        //  $("#elencoCorsiStudente").fadeOut();
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
    else if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    }else{
      console.log(data);
      $("#collapsibleCorso"+idCorso+" .collapsible-header").removeClass("light-blue");
      $("#collapsibleCorso"+idCorso+" .collapsible-header").removeClass("lighten-2");
      $('#iscriviCorso'+idCorso).attr('checked', false);
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> '+data, 4000);
    }
    });

}


function rimuoviCorso(idCorso){
  $("#collapsibleCorso"+idCorso+" .collapsible-header").removeClass("light-blue");
  $("#collapsibleCorso"+idCorso+" .collapsible-header").removeClass("lighten-2");
  var posting = $.post(
    '../include/rimuoviCorso.php',
    {
      "id_corso":idCorso
    });
    posting.done(function( data ){
      if(data=="SUCCESS"){
        $.cookie("scroll", $(window).scrollTop());
        Materialize.toast('Sto annullando l\'iscrizone...', 2000);
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
      else if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
        $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("light-blue");
        $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("lighten-2");
        console.log(data);
          Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore', 4000);
      }

    });
}

function mostraCoincidenze(corso){
  var posting = $.post(
    '../include/generaCoincidenze.php',
    {
      "idCorso":corso
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
        $("#modal-continuita .modal-content").html(data);
        $("#modal-continuita").css("margin-top","5em");
        $("#modal-continuita").openModal();
      }
    });
   var evt = this.event ? this.event : window.event;
   if (evt.stopPropagation)    evt.stopPropagation();
   if (evt.cancelBubble!=null) evt.cancelBubble = true;
}

function aggiungiPartecipa(idOra, idCorso){
  var posting = $.post(
    '../include/aggiungiPartecipa.php',
    {
      "idCorso":idCorso,
      "idOra":idOra
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      } else if(data!="SUCCESS"){
          console.log(data);
        }
    });
}

function rimuoviPartecipa(idOra){
  var posting = $.post(
    '../include/rimuoviPartecipa.php',
    {
      "idOra":idOra
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      } else if(!(data=="SUCCESS")){
          console.log(data);
        }
    });
}

function cambiaModificaPartecipa(idCorso, idOra){
    console.log("selezionato");
    rimuoviPartecipa(idOra);
    aggiungiPartecipa(idOra, idCorso);
    aggiornaOrario();
}

function caricaInfo(idCorso){
  var posting = $.post(
    '../include/dettagliCorso.php',
    {
      "idCorso":idCorso
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
           $("#collapsibleCorso"+idCorso+" .collapsible-body").html(data);
        }
    });
}

function invia_mail(){
  alert("ENTRATO");
  Materialize.toast('Sto inviando...', 1000);
  var posting = $.post(
    '../include/mandaEmail.php',
    {
      recapito:$("#recapito").val(),
      email:$("#email").val(),
      testo:$("#testoEmail").val()
    }
  );
  posting.done(function(data){
    if(data=="SUCCESS"){
        Materialize.toast('Email inviata!', 1000);
    }
    else if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    }else{
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (5)', 4000);
      console.log(data);
    }
  });
}

(function($){
  $(function(){
    aggiornaOrario();
    aggiornaCorsi(-1);
    //$('#collapsible32').addClass("active");
    $('.collapsible').collapsible({
      accordion : true
    });
    $('.modal-trigger').leanModal();
    if ( $( "#modal-primoAccesso" ).length ) {
      $( "#modal-primoAccesso" ).openModal();
    }
    $('.tooltipped').tooltip({delay: 50});

    $(document).on('change', '.iscriviOra', function(e){
      var el = "#"+e.target.id;
      var idOra = $(el).data("id-ora");
      var idCorso = $(el).data("id-corso");
      if($(el).is(':checked')){
        inserisciOra(idOra, idCorso);
      }
      else{
        rimuoviOra(idOra, idCorso);
      }
    });

    $("#formCerca").submit( function(e){
        e.preventDefault();
      if($("#filtro").val().toUpperCase() == "FLAVIO"){
        $("#miao").openModal();
      }else if($("#filtro").val().toUpperCase() == "BOSS"){
        $("#miaomiao").openModal();
      }
      else{
      Materialize.toast('Sto cercando...', 1000);
      var approfondimentoVal, recuperoVal, concontinuitaVal, senzacontinuitaVal;

      if($("#approfondimentoCerca").is(":checked")){
        approfondimentoVal = '1';
      }
      else {
        approfondimentoVal = '0';
      }

      if($("#recuperoCerca").is(":checked")){
        recuperoVal = '1';
      }
      else {
        recuperoVal = '0';
      }

      if($("#concontinuitaCerca").is(":checked")){
        concontinuitaVal = '1';
      }
      else {
        concontinuitaVal = '0';
      }

      if($("#senzacontinuitaCerca").is(":checked")){
        senzacontinuitaVal = '1';
      }
      else {
        senzacontinuitaVal = '0';
      }

      var posting = $.post(
        '../include/listaCorsiStudente.php',
        {
          filtro: $("#filtro").val(),
          concontinuita: concontinuitaVal,
          senzacontinuita: senzacontinuitaVal,
          recupero: recuperoVal,
          approfondimento: approfondimentoVal,
          active: -1
        }
      );
      posting.done(function(data){
        if(data == "LOGINPROBLEM"){
          window.location = "index.php";
        } else {
        $("#elencoCorsiStudente").html(data);
        $('.collapsible').collapsible({
          accordion : true
        });
        Materialize.toast('Ricerca completata!', 1000);
      }
      });
    }
    });




    $("#formCercaP").submit( function(e){
      e.preventDefault();
      Materialize.toast('Sto cercando...', 1000);
      var approfondimentoVal, recuperoVal, concontinuitaVal, senzacontinuitaVal;

      if($("#approfondimentoCercaP").is(":checked")){
        approfondimentoVal = '1';
      }
      else {
        approfondimentoVal = '0';
      }

      if($("#recuperoCercaP").is(":checked")){
        recuperoVal = '1';
      }
      else {
        recuperoVal = '0';
      }

      if($("#concontinuitaCercaP").is(":checked")){
        concontinuitaVal = '1';
      }
      else {
        concontinuitaVal = '0';
      }

      if($("#senzacontinuitaCercaP").is(":checked")){
        senzacontinuitaVal = '1';
      }
      else {
        senzacontinuitaVal = '0';
      }

      var posting = $.post(
        '../include/listaCorsiStudente.php',
        {
          filtro: $("#filtroP").val(),
          concontinuita: concontinuitaVal,
          senzacontinuita: senzacontinuitaVal,
          recupero: recuperoVal,
          approfondimento: approfondimentoVal,
          active: -1
        }
      );
      posting.done(function(data){
        if(data == "LOGINPROBLEM"){
          window.location = "index.php";
        } else {
        $("#elencoCorsiStudente").html(data);
        $('.collapsible').collapsible({
          accordion : true
        });
        Materialize.toast('Ricerca completata!', 1000);
      }
      });
    });



    var lastWindowWidth;
    $(window).resize(function() {
       var windowWidth = $(window).width();
       if((lastWindowWidth < 993) && (windowWidth >= 993)){
               lastWindowWidth = windowWidth;
               $("#card-orario-gen").html($("#card-orario-gen-piccolo").html());
       }
       if((lastWindowWidth > 993) && (windowWidth <= 993)){
               lastWindowWidth = windowWidth;
               $("#card-orario-gen-piccolo").html($("#card-orario-gen").html());
       }
       lastWindowWidth = windowWidth;
     });

     $("#cambiaPassword").click(function (e){
       e.preventDefault();
       if(($("#cane").val()==$("#ripeti").val()) && ($("#cane").val()!="") && ($("#ripeti").val()!="")){
         var posting = $.post(
           '../include/cambiaPassword.php',
           {
             "cane":$("#cane").val()
           });
           posting.done(function( data ){
             if(data == "LOGINPROBLEM"){
               window.location = "index.php";
             } else if(data == "SUCCESS"){
               $("#modal-primoAccesso").closeModal();
               Materialize.toast('Password cambiata con successo!', 4000);
               $("#modal-help").openModal();

             }
             else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>  Si è verificato un problema.', 4000);
                 console.log(data);
               }
           });
       }
       else if(($("#cane").val() == "") || ($("#cane").val() == "")){
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Prego, riempi i campi', 4000);
       }
       else{
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Le due password non sono uguali', 4000);
       }

     });

     $(window).scroll(function(){
        $.cookie("scroll", $(window).scrollTop());
    })

    $(document).on('change', '.iscriviCorso', function(e){
      var el = "#"+e.target.id;
      var idCorso = $(el).data("id-corso");
      if($(el).is(':checked')){
        setTimeout(function(){inserisciCorso(idCorso);}, 200);

      }
      else{
        rimuoviCorso(idCorso);
      }
    });
  });
})(jQuery);
