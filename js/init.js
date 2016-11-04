(function($){
  $(function(){
  //   $('.modal').modal();
     $('.modal').modal();
    $('.collapsible').collapsible({
      accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });

    $('select').material_select();
  Materialize.updateTextFields()
  }); // end of document ready
})(jQuery); // end of jQuery name space
