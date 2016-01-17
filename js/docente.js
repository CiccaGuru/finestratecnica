
function caricaInfo(idCorso){
  var posting = $.post(
    '../include/dettagliCorsoDocente.php',
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

function getElenco(idLezione){
  var posting = $.post(
    '../include/fileElenco.php',
    {
      "id":idLezione
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }
        window.location.href = "include/"+data;
        console.log(data);
    });
}

(function($){
  $(function(){
    $('.collapsible').collapsible({
      accordion : true
    });
    $('.modal-trigger').leanModal();
    if ( $( "#modal-primoAccesso" ).length ) {
      $( "#modal-primoAccesso" ).openModal();
    }
    $('.tooltipped').tooltip({delay: 50});

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
         var posting = $.post(
           '../include/cambiaPassword.php',
           {
             "cane":$("#cane").val()
           });
           posting.done(function( data ){
             if(data == "LOGINPROBLEM"){
               window.location("index.php");
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
       else if($("#cane").val() == ""){
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Prego, riempi i campi', 4000);
       }
       else{
         Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Le due password non sono uguali', 4000);
("#modal-primoAccesso").openModal();
       }

     });

     $(window).scroll(function(){
        $.cookie("scroll", $(window).scrollTop());
    });
  });
})(jQuery);
