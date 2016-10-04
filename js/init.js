(function($){
  $(function(){

    $('.collapsible').collapsible({
      accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });

    //$('ul.tabs').tabss('select_tab', '#a');
    $('.tooltipped').tooltip({delay: 50});
    $('select').material_select();

    var clicked=false;
    $("#help").click(function(){
      if(!clicked){

        $("#first").fadeOut();
        $("#help").animate({ left:"20%"}, 400);
        $("#back").fadeIn();
        $("#login").animate({top:"50em"}, 500, function(){
          $("#login").css("display","none");
          setTimeout(function(){
            $("#infos").css("top","50em");
            $("#infos").show();
            $("#infos").animate({top:"0em"}, 400);
          }, 200);
        });

        clicked = true;
      }
      else
      {
        $("#back").fadeOut();
        $("#help").animate({ left:"80%"});
        $("#first").fadeIn();
        $("#infos").animate({top:"50em"}, 500, function(){
          $("#infos").css("display","none");
          setTimeout(function(){
            $("#login").css("top","50em");
            $("#login").show();
            $("#login").animate({top:"0em"}, 400);
          }, 200);
        });
        clicked=false;
      }
    });
  Materialize.updateTextFields()
  }); // end of document ready
})(jQuery); // end of jQuery name space
