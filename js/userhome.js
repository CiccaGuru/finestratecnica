var hoCercato = 0;

function aggiornaCorsi(idCorso){
  if(hoCercato){
    cercaSubmit("", 1);
  }
  else{
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

}



function iscriviOra(idOra, idCorso){
  var posting = $.post(
    '../include/iscriviOra.php',
    {
      "id_ora":idOra,
      "id_corso":idCorso
    });
    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Ti ho iscritto con successo! <a onclick="rimuoviOra('+idOra+', '+idCorso+'); disappearAllToasts(\'.iscrittoToast\');" class="btn-flat waves-effect waves-green condensed green-text" style="font-weight:500; margin-right:0px; margin-left:12px; padding-left:12px; padding-right:12px;">Annulla</a>', 4000, "iscrittoToast");
        $.cookie("scroll", $(window).scrollTop());
        aggiornaCorsi(idCorso);
        aggiornaOrario();
      }
    else if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    } else{
      console.log(data);
      Materialize.toast(' '+data, 1200);
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
        Materialize.toast('Iscrizione annullata con successo! <a onclick="iscriviOra('+idOra+', '+idCorso+'); disappearAllToasts(\'.rimossoToast\');" class="btn-flat waves-effect waves-red condensed red-text" style="font-weight:500; margin-right:0px; margin-left:12px; padding-left:12px; padding-right:12px;">Annulla</a>', 4000, "rimossoToast");
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
      else if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
          Materialize.toast(' '+data, 1200);
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


function iscriviCorso(idCorso){
  $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("light-blue");
  $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("lighten-2");
  var posting = $.post(
    '../include/iscriviCorso.php',
    {
      "idCorso":idCorso
    });

    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Ti ho iscritto con successo! <a onclick="rimuoviCorso('+idCorso+'); disappearAllToasts(\'.iscrittoToast\');" class="btn-flat waves-effect waves-green condensed green-text" style="font-weight:500; margin-right:0px; margin-left:12px; padding-left:12px; padding-right:12px;">Annulla</a>', 4000, "iscrittoToast");
        $.cookie("scroll", $(window).scrollTop());
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
    else if(data == "SOME"){
      Materialize.toast('Ti ho iscritto solo ad alcune ore. Controlla i dettagli <a onclick="rimuoviCorso('+idCorso+'); disappearAllToasts(\'.iscrittoToast\');" class="btn-flat waves-effect waves-orange condensed orange-text" style="font-weight:500; margin-right:0px; margin-left:12px; padding-left:12px; padding-right:12px;">Annulla</a>', 4000, "iscrittoToast");
      $.cookie("scroll", $(window).scrollTop());
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
      Materialize.toast(data, 1200);
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
        Materialize.toast('Iscrizione annullata con successo! <a onclick="iscriviCorso('+idCorso+'); disappearAllToasts(\'.rimossoToast\');" class="btn-flat waves-effect waves-red condensed red-text" style="font-weight:500; margin-right:0px; margin-left:12px; padding-left:12px; padding-right:12px;">Annulla</a>', 4000, "rimossoToast");
        aggiornaOrario();
        aggiornaCorsi(idCorso);
      }
      else if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }else{
        $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("light-blue");
        $("#collapsibleCorso"+idCorso+" .collapsible-header").addClass("lighten-2");
        console.log(data);
          Materialize.toast(' Si è verificato un errore', 1200);
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

function aggiornaPartecipa(){
  var lezioneId = $("input[name=group1]:checked").data("idlezione");
  var ora = $("input[name=group1]:checked").data("ora");
  var posting = $.post(
    '../include/aggiornaPartecipa.php',
    {
      "idLezione":lezioneId,
      "ora":ora
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      } else if(data!="SUCCESS"){
          console.log(data);
        }
        aggiornaOrario();
        aggiornaCorsi();
    });

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
  Materialize.toast('Sto inviando...', 1200);
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
        Materialize.toast('Email inviata!', 1200);
    }
    else if(data == "LOGINPROBLEM"){
      window.location = "index.php";
    }else{
      Materialize.toast(' Si è verificato un errore (5)', 1200);
      console.log(data);
    }
  });
}

function cercaSubmit(s, mute){
  hoCercato = 1;
  if(!mute){
    Materialize.toast('Sto cercando...', 1200);
  }
  var approfondimentoVal, recuperoVal, concontinuitaVal, senzacontinuitaVal;

  if($("#approfondimentoCerca"+s).is(":checked")){
    approfondimentoVal = '1';
  }
  else {
    approfondimentoVal = '0';
  }

  if($("#recuperoCerca"+s).is(":checked")){
    recuperoVal = '1';
  }
  else {
    recuperoVal = '0';
  }

  if($("#concontinuitaCerca"+s).is(":checked")){
    concontinuitaVal = '1';
  }
  else {
    concontinuitaVal = '0';
  }

  if($("#senzacontinuitaCerca"+s).is(":checked")){
    senzacontinuitaVal = '1';
  }
  else {
    senzacontinuitaVal = '0';
  }

  var posting = $.post(
    '../include/listaCorsiStudente.php',
    {
      filtro: $("#filtro"+s).val(),
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
      if(!mute){
        Materialize.toast('Ricerca completata!', 1200);
      }

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
        iscriviOra(idOra, idCorso);
      }
      else{
        rimuoviOra(idOra, idCorso);
      }
    });

    $(document).on('click', '.iscriviOraModal', function (e){
      var idOra = $(e.target).data("idora");
      var idCorso = $(e.target).data("idcorso");
      var id = $(e.target).attr("id");
      iscriviOra(idOra, idCorso);
      $(".iscriviOraModal").attr("disabled", "true");
      $(".iscriviOraModal").removeClass("waves-effect").removeClass("red-text").css({ "color": "#9F9F9F !important"});
      $("#"+id).removeClass("disabled").addClass("green-text").html("ISCRITTO!");

    });

    $("#formCerca").submit( function(e){
        e.preventDefault();
        cercaSubmit("");
    });

    $("#formCercaP").submit( function(e){
      e.preventDefault();
      cercaSubmit("P");
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
               Materialize.toast('Password cambiata con successo!', 1200);
               $("#modal-help").openModal();

             }
             else {
                Materialize.toast('Si è verificato un problema.', 1200);
                 console.log(data);
               }
           });
       }
       else if(($("#cane").val() == "") || ($("#cane").val() == "")){
         Materialize.toast(' Prego, riempi i campi', 1200);
       }
       else{
         Materialize.toast('Le due password non sono uguali', 1200);
       }

     });

     $(window).scroll(function(){
        $.cookie("scroll", $(window).scrollTop());
    })

    $(document).on('change', '.iscriviCorso', function(e){
      var el = "#"+e.target.id;
      var idCorso = $(el).data("id-corso");
      if($(el).is(':checked')){
        setTimeout(iscriviCorso(idCorso), 200);
      }
      else{
        rimuoviCorso(idCorso);
      }
    });
  });
})(jQuery);
