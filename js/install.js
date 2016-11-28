$(function(){
    var conta = 1;
    var top = ($("#install-1 div:last").position().top + $("#install-1 div:last").height() + $("#install-card").height() + $("#install-card").position().top + $("#dot-1").position().top - $("#procedi").height())/2 +20;
    var left = parseFloat($("#install-card").css("margin-left")) + $("#install-card").outerWidth()/2-$("#procedi").outerWidth()/2;
    $("#procedi").css({"top": top, "left": left, "display":"block"});

    $("#procedi").click(function(){
      if(conta == 1){
          $("#install-card .card-content")
                .animate({"opacity":"0"}, 100)
                .delay(200)
                .queue(function(){
                  $(this).removeClass("primary").addClass("white").dequeue();
                })
                .delay(200)
                .animate({"opacity":"1"}, 100);
          $("#procedi")
                .queue(function(){
                    $(this).addClass("nascosto").dequeue();
                })
                .delay(200)
                .animate({"top": $("#install-card .card-action").offset().top + $("#install-card .card-action").outerHeight()/2 - $("#procedi").outerHeight()/2,
                          "left": parseFloat($("#install-card").css("margin-left")) + $("#install-card").outerWidth() - $("#procedi").outerWidth() -20}, 10)
                .delay(600)
                .queue(function(){
                  $(this).addClass("white primary-text")
                  .removeClass("primary").removeClass("nascosto").dequeue();
                });
          $("#install-card .title")
                .delay(300)
                .animate({"font-size":"180%"}, 250);
          $("#install-1")
                .delay(400)
                .queue(function(){
                    $(this).removeClass("center center-align")
                        .addClass("left-align")
                        .html($("#install-2").html()).dequeue();
                })
                .animate({"color":"black"}, 100);
          $("#dot-1")
                .delay(350)
                .animate({"font-size":"65%"}, 100, "swing")
                .queue(function(){
                  $(this).removeClass("white-text")
                  .addClass("primary-darkest-text").dequeue();
                });
          $("#dot-2")
                .delay(650)
                .queue(function(){
                  $(this).addClass("white-text")
                  .removeClass("primary-darkest-text").dequeue();
                })
                .animate({"font-size":"100%"}, 20, "swing");
              conta ++;
      }
      else if(conta == 2){
        if($("#accept:checked").length==0){
          alert("Devi accettare la licenza per continuare");
        }
        else{
        $.post("./include/controllaCompatibilitaInstall.php")
        .done(function(data){
            $("#install-1")
                  .animate({"opacity":"0"},150, "swing")
                  .delay(100)
                  .queue(function(){
                      $(this).html(data).dequeue();
                  })
                  .delay(100)
                  .animate({"opacity":"1"}, 150, "swing");
            $("#dot-2")
                  .delay(350)
                  .animate({"font-size":"65%"}, 100, "swing")
                  .queue(function(){
                    $(this).removeClass("white-text")
                    .addClass("primary-darkest-text").dequeue();
                  });
            $("#dot-3")
                  .delay(650)
                  .queue(function(){
                    $(this).addClass("white-text")
                    .removeClass("primary-darkest-text").dequeue();
                  })
                  .animate({"font-size":"100%"}, 20, "swing");
        });
      conta ++;
    }
  }
    else if(conta==3){
      if((parseInt($("#numCheck").html())==0)){
        alert("Attenzione! Il tuo sistema non soddisfa alcuni requisiti e quindi finestratecnica potrebbe non funzionare. Se vuoi proseguire lo stesso, abilita manualmente il pulsante 'PROSEGUI', così da dimostrare che sai cosa stai facendo. ");
      }
      else{
        $("#install-1")
              .animate({"opacity":"0"},150, "swing")
              .delay(100)
              .queue(function(){
                  $(this).html($("#install-3").html()).dequeue();
              })
              .delay(100)
              .animate({"opacity":"1"}, 150, "swing");
        $("#dot-3")
              .delay(350)
              .animate({"font-size":"65%"}, 100, "swing")
              .queue(function(){
                $(this).removeClass("white-text")
                .addClass("primary-darkest-text").dequeue();
              });
        $("#dot-4")
              .delay(650)
              .queue(function(){
                $(this).addClass("white-text")
                .removeClass("primary-darkest-text").dequeue();
              })
              .animate({"font-size":"100%"}, 20, "swing");
        $("#prosegui").delay(500).queue(function(){$(this).removeClass("waves waves-effect").addClass("disabled");});
        conta ++;
      }
    }
    else if(conta==4){
        $("#avviso-floating-content").html(' \
        <div class="titolo condensed primary-text center-align">Verifica della connessione a MySql...</div> \
        <div>\
      <div class="contieni-spinner">\
      <div class="preloader-wrapper active center-align">\
        <div class="spinner-layer big spinner-primary-only">\
          <div class="circle-clipper left">\
            <div class="circle"></div>\
          </div><div class="gap-patch">\
            <div class="circle"></div>\
          </div><div class="circle-clipper right">\
            <div class="circle"></div>\
          </div>\
        </div>\
      </div>\
        </div>\
      </div>');
        $("#sfondo-grigio").fadeIn();
        $.post("./include/verifica-db.php",
        {
          "db-host":$("#DBLocation").val(),
          "db-user":$("#DBUser").val(),
          "db-password":$("#DBPassword").val(),
          "db-name":$("#DBName").val()
        })
      .done(function(data){
        if(data=="SUCCESS"){
          $("#avviso-floating-content")
            .animate({"opacity":"0"}, 200)
            .queue(function(){
              $(this).html(' <div class="titolo condensed green-text center-align">Connessione effettuata!</div> <span class="center-align"><i class="material-icons green-text" style="font-size:700%;">check_circle</i></span>')
                .dequeue();
            })
            .animate({"opacity":"1"}, 200);

          $("#sfondo-grigio").delay(2200).fadeOut();
          $("#install-1")
                .delay(2800)
                .animate({"opacity":"0"},150, "swing")
                .delay(100)
                .queue(function(){
                    $(this).addClass("center-align");
                    $(this).html($("#install-4").html()).dequeue();
                })
                .delay(100)
                .animate({"opacity":"1"}, 150, "swing");
          $("#dot-4")
                .delay(2800)
                .animate({"font-size":"65%"}, 100, "swing")
                .queue(function(){
                  $(this).removeClass("white-text")
                  .addClass("primary-darkest-text").dequeue();
                });
          $("#dot-5")
                .delay(3100)
                .queue(function(){
                  $(this).addClass("white-text")
                  .removeClass("primary-darkest-text").dequeue();
                })
          .animate({"font-size":"100%"}, 20, "swing");


          $.post("./include/creaTabelleDb.php",
          {
            "admin-username":$("#adminUsername").val(),
            "admin-password":$("#adminPassword").val()
          })
          .done(function(data){
            if(data=="SUCCESS"){
              $("#install-4-content")
              .animate({"opacity":"0"}, 300)
              .queue(function(){
                $(this).html(' <div class="center-align" style="margin:2em;"><i class="material-icons green-text" style="font-size:64px;">check_circle</i></div> <div class="titolo condensed green-text center-align" style="font-size:150%;">Operazione completata!</div> ')
                .dequeue();
              })
              .animate({"opacity":"1"}, 300)
              .delay(1500);

              $("#install-1")
              .delay(2100)
              .animate({"opacity":"0"},150, "swing")
              .delay(100)
              .queue(function(){
                $(this).addClass("center-align");
                $(this).html($("#install-5").html()).dequeue();
              })
              .delay(100)
              .animate({"opacity":"1"}, 150, "swing");
              $("#dot-5")
              .delay(2150)
              .animate({"font-size":"65%"}, 100, "swing")
              .queue(function(){
                $(this).removeClass("white-text")
                .addClass("primary-darkest-text").dequeue();
              });
              $("#dot-6")
              .delay(2450)
              .queue(function(){
                $(this).addClass("white-text")
                .removeClass("primary-darkest-text").dequeue();
              })
              .animate({"font-size":"100%"}, 20, "swing");
              $("#procedi").delay(2500)
              .queue(function(){
                $(this).html("Termina").dequeue();
              });
            }
            else{
              console.log(data);
              $("#install-4-content")
              .animate({"opacity":"0"}, 300)
              .queue(function(){
                $(this).html(' <div class="center-align" style="margin:2em;"><i class="material-icons red-text" style="font-size:64px;">error</i></div> <div class="titolo condensed red-text center-align" style="font-size:150%;">Si è verificato un errore!</div> ')
                .dequeue();
              })
              .animate({"opacity":"1"}, 300)
              .delay(1500);
            }
          });
          conta ++;
        }
        else{
            var errorMessage = "";
            console.log(data=="1045");
            if(data ==  "2002"){
                errorMessage = "Impossibile connettersi a "+$("#DBLocation").val();
            }
            else if (data == "1045"){
                  errorMessage = "Accesso negato per l'utente "+$("#DBUser").val()+"con la password specificata";
            }
            else{
                  errorMessage = "Si è verificato un errore inatteso.";
            }
            $("#avviso-floating-content")
            .animate({"opacity":"0"}, 200)
            .queue(function(){
              $(this).html(' <span class="center-align"><i class="material-icons red-text" style="font-size:600%;">error</i></span><div class="condensed red-text center-align" style="font-size:200%">'+errorMessage+'</div> ')
                .dequeue();
            })
            .animate({"opacity":"1"}, 200);
        }
      });

    }
    if(conta==5){
      window.location = "../index.php";
    }
    });
});
