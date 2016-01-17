function animaBlu(idCorso, x_d, y_d){

  //jQuery time
  var d, x, y;
	parent = $("#collapsible"+idCorso);
  $("#collapsible"+idCorso+" .ink").removeClass("animate");

	if(! $("#collapsible"+idCorso+" .ink").height() && !  $("#collapsible"+idCorso+" .ink").width())
	{
		d = Math.max(parent.outerWidth(), parent.outerHeight());
	  $("#collapsible"+idCorso+" .ink").css({height: d, width: d});
	}

	//get click coordinates
	//logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
  alert(x_d);
	x = x_d - $("#collapsible"+idCorso+" .ink").width/2 ; //-  $("#collapsible"+idCorso).offset().left -  $("#collapsible"+idCorso+" .ink").width()/2;
	y = y_d - $("#collapsible"+idCorso+" .ink").height/2;// -  $("#collapsible"+idCorso).offset().top -  $("#collapsible"+idCorso+" .ink").height()/2;

	//set the position and add class .animate
	$("#collapsible"+idCorso+" .ink").css({top: y+'px', left: x+'px'}).addClass("animate");
}

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
    $("#elencoCorsiStudente").html(data);
    $('.collapsible').collapsible({
      accordion : true
    });
    caricaInfo(idCorso);
    setTimeout(function(){
      $(window).scrollTop($.cookie("scroll"));
    }, 200);
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
    else{
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
    $("#"+div).html('<div class="card-content" style="padding-top:10px;">'+data+'</div>');
    $("#"+div+" table").animate({opacity:1});
    Materialize.toast('Operazione completata!', 2000);
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
      else{
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
        $("#modal-scegliquale .modal-content").html(data);
        $("#modal-scegliquale").css("margin-top","3em");
        $("#modal-scegliquale").openModal();
    });
}


function inserisciCorso(idCorso){
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
    else{
      console.log(data);
      Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> '+data, 4000);
    }
    });

}


function rimuoviCorso(idCorso){
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
      else{
        console.log(data);
          Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> '+data, 4000);
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
        $("#modal-continuita .modal-content").html(data);
        $("#modal-continuita").css("margin-top","5em");
        $("#modal-continuita").openModal();
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
        if(data!="SUCCESS"){
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
        if(!(data=="SUCCESS")){
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
           $("#collapsibleCorso"+idCorso+" .collapsible-body").html(data);

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
    else{
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
        $("#elencoCorsiStudente").html(data);
        $('.collapsible').collapsible({
          accordion : true
        });
        Materialize.toast('Ricerca completata!', 1000);
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

     $("#cambiaPassword").click(function (){
       if(($("#cane").val()==$("#ripeti").val()) && ($("#cane").val()!="")){
         $("#modal-primoAccesso").closeModal();
         var posting = $.post(
           '../include/cambiaPassword.php',
           {
             "cane":$("#cane").val()
           });
           posting.done(function( data ){
               if(!(data=="SUCCESS")){
                 console.log(data);
               }
               ("#modal-info").openModal();
           });
       }
       else if($("#cane").val() == ""){
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Prego, riempi i campi', 4000);
       }
       else{
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Le due password non sono uguali', 4000);
("#modal-primoAccesso").openModal();
       }

     });

    $(document).on('change', '.iscriviCorso', function(e){
      var el = "#"+e.target.id;
      var idCorso = $(el).data("id-corso");
      if($(el).is(':checked')){
        //animaBlu(idCorso, e.pageX, e.pageY);
        setTimeout(function(){inserisciCorso(idCorso);}, 200);

      }
      else{
        rimuoviCorso(idCorso);
      }
    });
  });
})(jQuery);
