var materialColors;
(function($) {
  $(function() {

    $("#aspettoTrigger").click(function(){
      $("#cover").fadeOut();
      $("#aspetto").delay(500).fadeIn();
      $("#side div").removeClass("active");
      $("#aspettoTrigger").addClass("active");
    });

    $(document).on("click", ".variation", function(e){
      var hex = $(this).data("hex");
      var quale = $(this).data("target");
      console.log(quale);
      $.post("./include/cambiaColore.php",
          {
            "hex":hex,
            "target":quale
          })
        .done(function(data){
            if(data=="SUCCESS"){
              Materialize.toast('Colore modificato con successo', 4000);
            }else{
              Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
              console.log(data);
            }
        });
    });

    $(document).on("click", ".variations-trigger",function(e){
        var colorpicker = $(this).data("colorpicker");
        var nickname = $(this).data("nickname");
        $.each(materialColors, function(i,color){
            if(color["nickname"]==nickname){
              html="";
              $.each(color["variations"], function(index, variation) {
                var forecolor, waves;
                var rgbHex = variation["hex"].substring(1);
                var rgb = parseInt(rgbHex, 16);
                var r = (rgb >> 16) & 0xff;
                var g = (rgb >>  8) & 0xff;
                var b = (rgb >>  0) & 0xff;

                var luma = 0.2526*r + 0.7152*g + 0.0722*b;

                if (luma < 120) {
                  forecolor = "#fff";
                  waves = "waves-light";
                }
                else{
                  forecolor = "#000";
                }
                if (luma < 160) {
                  waves = "waves-light";
                }
                if((r==255)&&(g==255)&&(b==255)){
                  forecolor = "#000";
                  waves = "";
                }

                html +='<div data-hex="'+variation["hex"]+'" data-target="'+colorpicker+'" class="variation waves-effect '+waves+'" style="background-color:'+variation["hex"]+'">\
                          <div class="detail condensed" style="color: '+forecolor+'; ">\
                            <div>'+variation["hex"]+'</div>\
                            <div class="bold">'+variation["weight"]+'</div>\
                        </div></div>';
              });
              $(".variations", $("#"+colorpicker))
                    .animate({"opacity":"0"}, 200)
                    .queue(function(){
                      $(this).html(html).dequeue();
                    })
                    .animate({"opacity":"1"}, 200);
            }
        });
    });

    $.getJSON("./js/materialColors.json")
    .done(function(materialColorsJSON){
      materialColors = materialColorsJSON;
      $(".variations").html("<span class='noColor condensed'>Selezionare un colore</span>")
      $(".colorpicker").each(function(index, colorpicker){
        var html = '<div class="listaColori">';
        $.each(materialColors, function(i, el){
          if(i % 2 ){
            html += "<div> \
            <input name='"+$(colorpicker).attr('id')+"' class='"+el["nickname"]+"Check materialPickerCheck' id='"+el["nickname"]+index+"'type='radio'> \
            <label data-nickname='"+el["nickname"]+"' data-colorpicker='"+$(colorpicker).attr('id')+"' class='variations-trigger' for='"+el["nickname"]+index+"'></label></div>";
          }
          else {
            html +="</div><div class='listaColori'><div> \
            <input name='"+$(colorpicker).attr('id')+"' class='"+el["nickname"]+"Check materialPickerCheck' id='"+el["nickname"]+index+"'type='radio'> \
            <label data-nickname='"+el["nickname"]+"' data-colorpicker='"+$(colorpicker).attr('id')+"' class='variations-trigger' for='"+el["nickname"]+index+"'></label></div>";
          }
        });
        $(".maincolor", colorpicker).html(html);
      });
    });
  });
})(jQuery);
