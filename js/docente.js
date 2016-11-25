var ore = []
var aule = "";
function caricaInfo(idCorso){
  var posting = $.post(
    './include/dettagliCorsoDocente.php',
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

function aggiungiOraAggiungere(ora){
  var giorni = [];
  $.post('./include/nomeOreSettimana.php', {dataType: "JSON"}).done(function(data) {
      giorni =  $.parseJSON(data);
      var ore_giornaliere = Number(giorni[0]);
      ora = Number(ora);
      var i = 1;
      var num_giorno = Math.floor(ora / ore_giornaliere)+1;
      var num_ora = (ora - 1) % ore_giornaliere +1;
      if(num_ora == ore_giornaliere){
        num_giorno --;
      }
      var string = ' \
                <div style="margin-bottom:0px;" class="row oreDaAggiungere valign-wrapper" data-ora = '+ora+'> \
                  <div class="col s3 bold">'+giorni[num_giorno]+', '+num_ora+'^ ora</div> \
                  <div class="col s5 input-field">  \
                      <input class="titoloLezione" id="titoloLezione'+ora+'" type="text">\
                      <label for="titoloLezione">Titolo <span class="italic">(facoltativo)</span></label>\
                  </div>\
                  <div class="col s4 input-field"><select>'+aule+'</select></div> \
                </div>';
          var fatto = false;
          if($(".oreDaAggiungere").length==0){
              $("#elencoOreAggiungere .card-content").html("");
          }
          $($(".oreDaAggiungere").get().reverse()).each(function(){
            console.log(ora +" "+$(this).data("ora"));
          if(ora>$(this).data("ora")){
            $(string).insertAfter($(this));
            fatto = true;
            $("select", $(this).next()).material_select();
          }
          if(ora>=$(this).data("ora")){
            return false;
          }
        });
        if(!fatto){
          $("#elencoOreAggiungere .card-content").html(string + $("#elencoOreAggiungere .card-content").html() );
          $("#elencoOreAggiungere select:last").material_select();
          console.log("pippo");
        }

  });
}

function rimuoviOraAggiungere(ora){
  $(".oreDaAggiungere").each(function(){
    if($(this).data("ora")==ora){
      $(this).remove();
    }
  });
  if($(".oreDaAggiungere").length==0){
      $("#elencoOreAggiungere .card-content").html('	<span class="italic">Selezionare delle ore nella tabella orario a fianco per iniziare.</span>');
  }
}


function eliminaCorso(id){
    var posting = $.post("./include/eliminaCorso.php", {
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


function getElenco(idLezione){
  var posting = $.post(
    './include/fileElenco.php',
    {
      "id":idLezione
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }
        var win = window.open("include/"+data, '_blank');
        win.focus();
      });
}

(function($){
  $(function(){
    $('.collapsible').collapsible({
      accordion : true
    });
    $("#elencoOreAggiungere").css("max-height", $("#cardOrarioAggiungi").height());
    $("#elencoOreAggiungere").css("overflowY","auto");
    if ( $( "#modal-primoAccesso" ).length ) {
      $( "#modal-primoAccesso" ).modal("open");
    }
    $('.tooltipped').tooltip({delay: 50});


     $("#cambiaPassword").click(function (){
       if(($("#cane").val()==$("#ripeti").val()) && ($("#cane").val()!="")){
         var posting = $.post(
           './include/cambiaPassword.php',
           {
             "cane":$("#cane").val()
           });
           posting.done(function( data ){
             if(data == "LOGINPROBLEM"){
               window.location("index.php");
             } else if(data == "SUCCESS"){
               $("#modal-primoAccesso").closeModal();
               Materialize.toast('Password cambiata con successo!', 4000);
               $("#modal-help").modal("open");

             }
             else {
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>  Si è verificato un problema.', 4000);
                 console.log(data);
               }
           });
       }
       else if($("#cane").val() == ""){
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Prego, riempi i campi', 4000);
       }
       else{
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Le due password non sono uguali', 4000);
        $("#modal-primoAccesso").modal("open");
       }

     });

    $(".aggiungiOraCella").mouseenter(function(){
      if(!$(this).hasClass("added")){
        $(this).html("<i class='material-icons white-text'>add</i>");
        $(this).addClass("accent");
      }
      else{
        $(this).html("<i class='material-icons white-text'>remove</i>");
        $(this).removeClass("primary");
        $(this).addClass("primary-light");
      }
    });
    $(".aggiungiOraCella").mouseleave(function(){
      if(!$(this).hasClass("added")){
        $(this).html("");
        $(this).removeClass("accent");
      }
      else{
        $(this).html("");
        $(this).removeClass("primary-light");
        $(this).addClass("primary");
      }
    });

    $(".aggiungiOraCella").click(function(){
      if($(this).hasClass("added")){ //rimuovi ora
        $(this).removeClass("added primary-light primary");
        $(this).addClass("accent");
        $(this).html('<i class="material-icons white-text">add</i>');
        rimuoviOraAggiungere($(this).data("ora"));
      }
      else{  //aggiungi ora
        $(this).addClass("added primary-light");
        $(this).removeClass("accent primary");
        $(this).html("<i class='material-icons white-text'>remove</i>");
        aggiungiOraAggiungere($(this).data("ora"));
      }
    });

     fatto = 0;
     $("#btn-eliminaCorso").click(function(){
       if(fatto==0){

         $(".collapsibleCorso").each(function(){
           $(".descrizioneCorso", this).removeClass("s6");
           $(".descrizioneCorso", this).addClass("s5");
           $("<div class='col s1 delete' data-idcorso = '"+$(this).data("idcorso")+"'><i class='material-icons accent-text waves-effect waves-accent'>delete</i></div>").insertBefore($(".titoloCorso", this));
         });
         setTimeout(function(){
           $("#btn-eliminaCorso i").fadeOut();
           $("#btn-eliminaCorso span").fadeOut();
           setTimeout(function(){
             $("#btn-eliminaCorso i").html("arrow_back").fadeIn();
             $("#btn-eliminaCorso span").html("INDIETRO").fadeIn();
            }, 300);
         }, 200);

         fatto=1;
       }
       else{
         $(".collapsibleCorso").each(function(){
           $(".descrizioneCorso", this).addClass("s6");
           $(".descrizioneCorso", this).removeClass("s5");
           $(".delete", this).remove();
         });
         setTimeout(function(){
           $("#btn-eliminaCorso i").fadeOut();
           $("#btn-eliminaCorso span").fadeOut();
           setTimeout(function(){
             $("#btn-eliminaCorso i").html("delete_forever").fadeIn();
              $("#btn-eliminaCorso span").html("ELIMINA CORSO").fadeIn();
           }, 300);
         }, 200);

         fatto=0;
       }

     });

     $.post('./include/elencoAule.php').done(function(data){
       aule = data;
     });



     $("#btn-SalvaCorso").click(function(){
         if(($("#titolo").val()=="")||($("#descriz").val()=="")||($("#selezionaClassi").val().length==0)){
           Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Completare tutti i campi nei dettagli corso!', 4000);
           return false;
         }
         $.post('./include/aggiungiCorso.php',
         {
           titolo: $("#titolo").val(),
           descriz: $("#descriz").val(),
           tipo: $("#inserisciCorso input[name=tipo]:checked").val(),
           continuita: $("#inserisciCorso input[name=continuita]:checked").val(),
           "classi[]": $("#selezionaClassi").val()
         }).done(function(data) {
           if (isNaN(data)) {
             console.log(data);
             return false;
           }
           var idCorso = data;
           $.post('./include/assegnaCorsoDocenti.php',
             {
               "idCorso":idCorso
             }).done(function(data){
             if(data != "SUCCESS"){
               Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (1). Controlla la console', 4000);
               eliminaCorso(idCorso);
               return false;
             }
           });

           $(".oreDaAggiungere").each(function(i) {
             var ora = $(this).data("ora");
             $.post(
               './include/aggiungiOra.php', {
                 "idcorso": idCorso,
                 "titolo": $(".titoloLezione", this).val(),
                 "ora": ora,
                 "idAula": $("select", this).val()
               }).done(function(data) {
                 if (data != "SUCCESS INSERT ORA") {
                   Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (2). Controlla la console', 4000);
                   console.log(data);
                   eliminaCorso(idCorso);
                   return false;
                 }
               });
             });
             $(window).off("beforeunload");
             $("#wait").fadeIn();
             Materialize.toast('<i class="material-icons green-text style="margin-right:0.25em">done</i> Corso aggiunto con successo!', 4000);

           });


     });












     $(window).scroll(function(){
        $.cookie("scroll", $(window).scrollTop());
    });
  });
})(jQuery);
