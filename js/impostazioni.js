var materialColorsBase, materialColorsAdvanced;

function getLuma(hexa){
  hexa = hexa.substring(1);
  var rgb = parseInt(hexa, 16);
  var r = (rgb >> 16) & 0xff;
  var g = (rgb >>  8) & 0xff;
  var b = (rgb >>  0) & 0xff;
  return (6*r + 7*g + 3*b)/20;
}

function getTextColor(hexa){
  var luma = getLuma(hexa);
  if (luma < 127) {
    forecolor = "#fafafa";
  }
  else{
    forecolor = "#263238";
  }
  return forecolor;
}

function getWavesColor(hex){
  var waves = ""
  var luma = getLuma(hex);
  if (luma < 160) {
    waves = "waves-light";
  }
  return waves;
}


function getLight(main, position){
  var res;
    $.each(materialColorsAdvanced, function(i,color){
        if(color["nickname"]==main){
          if((position-1)>=0){
            res = color["variations"][position-1]["hex"];
          }else{
              res = color["variations"][0]["hex"];
          }
        }
    });
    return res.toString();
}

function getDark(main, position){
  var res;
    $.each(materialColorsAdvanced, function(i,color){
        if(color["nickname"]==main){
          if(position+2>=color["variations"].length){
            res =  color["variations"][color["variations"].length - 1]["hex"];
          }else{
            res =  color["variations"][position+2]["hex"];
          }
        }
    });
    return res;
}

function getDarkest(main, position){
  var res;
    $.each(materialColorsAdvanced, function(i,color){
        if(color["nickname"]==main){
          if(position+3>=color["variations"].length){
            res = color["variations"][color["variations"].length -1]["hex"];
          }else{
            res = color["variations"][position+3]["hex"];
          }
        }
    });
    return res;
}

(function($) {
  $(function() {
    if(window.location.hash != ""){
      $("#main>div:not("+window.location.hash+")").hide();
      $(window.location.hash).delay(500).fadeIn();
      $("#side div").removeClass("active");
      $("#"+($(window.location.hash).data("trigged"))).addClass("active");
    }
    if(window.location.hash == ""){
      $("#main>div:not(#cover)").hide();
      $("#cover").delay(500).fadeIn();
      $("#side div").removeClass("active");
    }

    $(document).on("click", ".setting-trigger", function(){
      var aim = $(this).data("trigger");
      $("#main>div:not("+aim+")").fadeOut();
      $("#"+aim).delay(500).fadeIn();
      $("#side div").removeClass("active");
      $(this).addClass("active");
      window.location.hash = aim;
    });

    $(document).on("click", ".advanced .variation", function(e){
      var hex = $(this).data("hex");
      var quale = $(this).data("target");
      $.post("./include/cambiaProp.php",
          {
            "hex":hex,
            "target":quale
          })
        .done(function(data){
            if(data=="SUCCESS"){
              Materialize.toast('Colore modificato con successo', 4000);
              setTimeout(function(){
                document.location.reload(true);
              }, 1000);
            }else{
              Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
              console.log("ERROR: "+data);
            }
        });
    });

    $(document).on("click", ".base .variation", function(e){
      var lightColor, darkColor, darkestColor, darkText, text, lightText
      var variation = $(this);
      var hex = $(this).data("hex");
      var quale = $(this).data("target");
      var main = $(this).data("main");
      var position = parseInt($(variation).data("position"));
      $.post("./include/cambiaProp.php",
          {
            "val":hex,
            "target":quale
          })
        .done(function(data){
            if(data=="SUCCESS"){
              if(quale == "primaryColor"){
                lightColor = getLight(main, position);
                darkestColor =  getDarkest(main, position);
                darkColor =  getDark(main, position);
                text = getTextColor(hex);
                lightText = getTextColor(lightColor);
                darkText = getTextColor(darkColor);
                $.post("./include/cambiaProp.php",{"val":lightColor,"target":"primaryColorLight"});
                $.post("./include/cambiaProp.php",{"val":darkColor,"target":"primaryColorDark"});
                $.post("./include/cambiaProp.php",{"val":darkestColor,"target":"primaryColorDarkest"});
                $.post("./include/cambiaProp.php",{"val":text,"target":"primaryText"});
                $.post("./include/cambiaProp.php",{"val":lightText,"target":"primaryLightText"});
                $.post("./include/cambiaProp.php",{"val":darkText,"target":"primaryDarkText"})
              }
              if(quale == "accentColor"){
                lightColor = getLight(main, position);
                text = getTextColor(hex);
                $.post("./include/cambiaProp.php",{"hex":lightColor,"target":"accentColorLight"});
                $.post("./include/cambiaProp.php",{"hex":text,"target":"accentText"});
              }
              Materialize.toast('Colore modificato con successo', 4000);
              setTimeout(function(){
                document.location.reload(true);
              }, 1000);
            }else{
              Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
              console.log(data);
            }
        });
    });


    $(document).on("click", ".advanced .variations-trigger",function(e){
      var colorpicker = $("#"+($(this).data("colorpicker")));
      var nickname = $(this).data("nickname");
        $.each(materialColorsAdvanced, function(i,color){
            if(color["nickname"]==nickname){
              html="";
              $.each(color["variations"], function(index, variation) {
                var forecolor = getTextColor(variation["hex"]);
                var waves = getWavesColor(variation["hex"]);
                html +='<div data-hex="'+variation["hex"]+'" data-target="'+$(colorpicker).data("prop")+'" class="variation waves-effect '+waves+'" style="background-color:'+variation["hex"]+'">\
                          <div class="detail condensed" style="color: '+forecolor+'; ">\
                            <div>'+variation["hex"]+'</div>\
                            <div class="bold">'+variation["weight"]+'</div>\
                        </div></div>';
              });
              $(".variations", $(colorpicker))
                .animate({"opacity":"0"}, 200)
                .queue(function(){
                  $(this).html(html).dequeue();
                })
                .animate({"opacity":"1"}, 200);
            }
        });
    });

    $(document).on("click", ".base .variations-trigger",function(e){
        var colorpicker = $("#"+($(this).data("colorpicker")));
        var nickname = $(this).data("nickname");

        $.each(materialColorsBase, function(i,color){
            if(color["nickname"]==nickname){
              html="";
              $.each(color["variations"], function(index, variation) {
                var waves = getWavesColor(variation["hex"]);

                if((index % 3)==2){
                  html +=' <div class="variation-cell"> \
                              <div data-position="'+index+' "data-main="'+color["nickname"]+'" data-hex="'+variation["hex"]+'" data-target="'+$(colorpicker).data("prop")+'" class="variation waves-effect '+waves+'" style="background-color:'+variation["hex"]+'">\
                              </div>  \
                            </div> \
                          </div>';
                }else if((index % 3)==0){
                  html +='<div class="variation-divider"> \
                            <div class="variation-cell"> \
                              <div data-position="'+index+' "data-main="'+color["nickname"]+'" data-hex="'+variation["hex"]+'" data-target="'+$(colorpicker).data("prop")+'" class="variation waves-effect '+waves+'" style="background-color:'+variation["hex"]+'">\
                              </div>\
                            </div>';

                }if((index % 3)==1){
                  html +='  <div class="variation-cell"> \
                              <div data-position="'+index+' "data-main="'+color["nickname"]+'" data-hex="'+variation["hex"]+'" data-target="'+$(colorpicker).data("prop")+'" class="variation waves-effect '+waves+'" style="background-color:'+variation["hex"]+'">\
                              </div> \
                            </div>';
                }
              });
              $(".variations", $(colorpicker))
                .animate({"opacity":"0"}, 200)
                .queue(function(){
                  $(this).html(html).dequeue();
                })
                .animate({"opacity":"1"}, 200);
            }
        });
    });

    $(document).on("submit", "#modificaAdmin", function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      if($("#admin-newpassword").val()!=$("#admin-renewpassword").val()){
        $("#admin-error").html( "<p class='red-text center-align condensed'>Le due password non sono uguali!</p>" );
      }else{
        $.post("./include/modificaPasswordAdmin.php",
        {
          "username":$("#admin-username").val(),
          "attuale":$("#admin-nowpassword").val(),
          "nuova":$("#admin-newpassword").val()
        }).done(function(data){
          if(data=="ERRATA"){
            $("#admin-error").html("<p class='red-text center-align condensed'>La password corrente è errata!</p>");
          } else if(data == "SUCCESS"){
            Materialize.toast('Utente admin modificato con successo', 4000);
          } else if(data="VUOTO"){
            Materialize.toast('Compilare almeno un campo!', 4000);
          } else{
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
          }
        });
      }
    });

    $(document).on("submit", "#modificaDatabase", function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      $.post("./include/modificaCredenzialiDatabase.php",
      {
          "user":$("#db-username").val(),
          "host":$("#db-host").val(),
          "password":$("#db-password").val(),
          "name":$("#db-name").val()
      }).done(function(data){
            if(data ==  "2002"){
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Impossibile connettersi alla posizione specificata.', 4000);
            }
            else if (data == "1045"){
              Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Accesso negato per l\'utente specificato', 4000);
            }else if(data == "SUCCESS"){
              Materialize.toast('Impostazioni modificate con successo', 4000);
            }else{
                Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
                console.log(data);
          }
        });
    });
      var conta;
    $(document).on("submit", "#modificaOrario", function(e){
      e.preventDefault();
      e.stopImmediatePropagation();
      conta = 0;
      $.post("./include/cambiaProp.php",
        {
          "target":"numero_giorni",
          "val":$("#numero_giorni").val()
      }).done(function(data){
        if(data=="SUCCESS"){
          $.post("./include/cambiaProp.php",
            {
              "target":"ore_per_giorno",
              "val":$("#ore_per_giorno").val()
          }).done(function(data){
            if(data=="SUCCESS"){
              $.post("./include/cambiaProp.php",
                {
                  "target":"soglia_minima",
                  "val":$("#soglia_minima").val()
              }).done(function(data){
                if(data=="SUCCESS"){
                  var aperturaArray = $("input[name='apertura_iscrizioni_submit']").val().split("/");
                  var apertura = new Date(aperturaArray[2], aperturaArray[1], aperturaArray[0], $("#ora-apertura").val(), $("#minuti-apertura").val());
                  console.log(aperturaArray);
                  console.log(apertura);
                  $.post("./include/cambiaProp.php",
                    {
                      "target":"apertura_iscrizioni",
                      "val":apertura.getTime()/1000
                  }).done(function(data){
                    if(data=="SUCCESS"){
                      var chiusuraArray = $("input[name='chiusura_iscrizioni_submit']").val().split("/");
                      var chiusura = new Date(chiusuraArray[2], chiusuraArray[1], chiusuraArray[0], $("#ora-chiusura").val(), $("#minuti-chiusura").val());
                      $.post("./include/cambiaProp.php",
                        {
                          "target":"chiusura_iscrizioni",
                          "val":chiusura.getTime()/1000
                      }).done(function(data){
                        if(data=="SUCCESS"){
                          Materialize.toast("Modifiche effettuate con successo!", 4000);
                        }
                      });
                    }
                  });
                }
              });
            }
          });
        }
      });
    });


    $.getJSON("./js/materialColorsAdvanced.json")
    .done(function(materialColorsJSON){
      materialColorsAdvanced = materialColorsJSON;
      $(".advanced .variations").html("<span class='noColor condensed'>Selezionare un colore</span>")
      $(".colorpicker.advanced").each(function(index, colorpicker){
        var html = '<div class="listaColori">';
        $.each(materialColorsAdvanced, function(i, el){
          if(i % 2 ){
            html += "<div> \
            <input name='"+$(colorpicker).attr('id')+"' class='"+el["nickname"]+"Check materialPickerCheck' id='"+el["nickname"]+$(colorpicker).attr('id')+"'type='radio'> \
            <label data-nickname='"+el["nickname"]+"' data-colorpicker='"+$(colorpicker).attr('id')+"' class='variations-trigger' for='"+el["nickname"]+$(colorpicker).attr('id')+"'></label></div>";
          }
          else {
            html +="</div><div class='listaColori'><div> \
            <input name='"+$(colorpicker).attr('id')+"' class='"+el["nickname"]+"Check materialPickerCheck' id='"+el["nickname"]+$(colorpicker).attr('id')+"'type='radio'> \
            <label data-nickname='"+el["nickname"]+"' data-colorpicker='"+$(colorpicker).attr('id')+"' class='variations-trigger' for='"+el["nickname"]+$(colorpicker).attr('id')+"'></label></div>";
          }
        });
        $(".maincolor", colorpicker).html(html);
      });
    });

    $.getJSON("./js/materialColorsBase.json")
    .done(function(materialColorsJSON){
      materialColorsBase = materialColorsJSON;
      $(".base .variations").html("<span class='noColor condensed'>Selezionare un colore</span>")
      $(".colorpicker.base").each(function(index, colorpicker){
        var html = '<div class="listaColori">';
        $.each(materialColorsBase, function(i, el){
            html += "<div> \
            <input name='"+$(colorpicker).attr('id')+"' class='"+el["nickname"]+"Check materialPickerCheck' id='"+el["nickname"]+$(colorpicker).attr('id')+"'type='radio'> \
            <label data-nickname='"+el["nickname"]+"' data-colorpicker='"+$(colorpicker).attr('id')+"' class='variations-trigger' for='"+el["nickname"]+$(colorpicker).attr('id')+"'></label></div>";
        });
        $(".maincolor", colorpicker).html(html);
      });
    });
  });
})(jQuery);
