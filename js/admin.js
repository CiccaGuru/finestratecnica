function aggiornaListaDocenti(){
	var posting = $.post(
		'../include/generaElencoDocenti.php', {1:1}
	);
	posting.done(function( data ){
		$("#selezionaDocenti").html('<option value="" disabled selected class="grey-text">Seleziona insegnante</option>'+data);
		$('select').material_select();
	});
}


function modificaDocente(user_id, quanti, page, filtro){
  if(($("#nome"+user_id).val()=="") || ($("#cognome"+user_id).val()=="") || ($("#username"+user_id).val()==""))
    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
  else{
    var posting = $.post(
      '../include/modificaUtente.php',
      {
        id:user_id,
        nome:$("#nome"+user_id).val(),
        cognome:$("#cognome"+user_id).val(),
        username:$("#username"+user_id).val(),
      }
    );
    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Utente modificato con succeso!', 4000);
        aggiornaDettagliUtenti(quanti, page, filtro, 1);
        aggiornaListaDocenti();
      }
      else {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
        console.log(data);
      }
    });
  }
}


function modificaStudente(user_id, quanti, page, filtro){
  if(($("#nome"+user_id+"Studente").val()=="") ||
			($("#cognome"+user_id+"Studente").val()=="") ||
			($("#username"+user_id+"Studente").val()=="") ||
			($("#classeStudente"+user_id).val() == "")){
    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
		console.log($("#username"+user_id+"Studente").val());
	}
  else{
    var posting = $.post(
      '../include/modificaUtente.php',
      {
        id:user_id,
        nome:$("#nome"+user_id+"Studente").val(),
        cognome:$("#cognome"+user_id+"Studente").val(),
        username:$("#username"+user_id+"Studente").val(),
				classe:$("#classeStudente"+user_id).val()
      }
    );
		posting.done(function( data ){
			if(data=="SUCCESS"){
				Materialize.toast('Utente modificato con succeso!', 4000);
				aggiornaDettagliUtenti(quanti, page, filtro, 0);
				aggiornaListaDocenti();
			}
			else {
				Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
				console.log(data);
			}
		});
  }
}


function aggiornaDettagliUtenti(quantiN, pageN, filtro, level){
	console.log(filtro);
	var posting = $.post(
		'../include/elencoUtenti.php', {
			"level":level,
			"quanti":quantiN,
			"page":pageN,
			"username":filtro
		}
	);
	posting.done(function( data ){
		if(level==0)
			$("#dettagliStudenti").html(data);
		if(level==1)
		  $("#dettagliDocenti").html(data);
	});

}

function eliminaUtente(id, quanti, page, filtro, level){
	var posting = $.post(
		'../include/eliminaUtente.php',
		{id:id}
	);
	posting.done(function( data ){
		if(data=="SUCCESS"){
			 Materialize.toast('Utente eliminato con successo!', 4000);
			 aggiornaDettagliUtenti(quanti,page,filtro,level);
			 aggiornaListaDocenti();
		}
		else {
			Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
			console.log(data);
		}
});
}

function passwordReset(user_id){
	var posting = $.post(
		'../include/passwordReset.php',
		{id:user_id}
	);
	posting.done(function( data ){
		if(data=="SUCCESS"){
			 Materialize.toast('Password reimpostata con successo!', 4000);
		}
		else {
			Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
			console.log(data);
		}
});
}

function aggiungiCorso(){
	var res=1;
	var posting_corso = $.post(
		'../include/aggiungiCorso.php',
		{
			titolo:$("#titolo").val(),
			descriz:$("#descriz").val(),
			tipo:$("#gestioneCorsi input[name=tipo]:checked").val(),
			continuita:$("#gestioneCorsi input[name=continuita]:checked").val(),
			iddocente:$("#selezionaDocenti").val(),
			"classi[]":$("#selezionaClassi").val()
		}
	);
	posting_corso.done(function( data ){
		if(isNaN(data)){
			Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (1). Controlla la console', 4000);
			console.log(data);
		}
		else{
			res=1;
			 $(".ora_da_inserire").each(function(i){
				 console.log("ORA");
				 var ora = (parseInt($("#selezionaGiorno"+i).val())-1)*6+parseInt($("#selezionaOra"+i).val());
				 var posting = $.post(
					 '../include/aggiungiOra.php',
					 {
						 idcorso:data,
						 titolo:$("#nomeOra"+i).val(),
						 ora:ora,
						 aula:$("#aulaOra"+i).val(),
						 maxIscritti:$("#maxIscrittiOra"+i).val()
					 });
					 posting.done(function(data){
						 if(!(data=="SUCCESS INSERT ORA")){
							 Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (2). Controlla la console', 4000);
							 console.log(data);
							 res=0;
							 return false;
						 }
					 });
			 });
			 if(res==1)
			 	Materialize.toast('<i class="material-icons green-text style="margin-right:0.25em">done</i> Corso aggiunto con successo!', 4000);
		}
	});

}

function elencoNonAccessi(){
  var posting = $.post(
    '../include/elencoNonAccessi.php',
    {
      1:1
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }
        window.location.href = "include/"+data;
        console.log(data);
    });
}


function elencoNonAbbastanza(){
  var posting = $.post(
    '../include/elencoNonAbbastanza.php',
    {
      1:1
    });
    posting.done(function( data ){
      if(data == "LOGINPROBLEM"){
        window.location = "index.php";
      }
        window.location.href = "include/"+data;
        console.log(data);
    });
}

function generaRegistrini(){
	$("#wait").css("height:100%");
	$("#wait").fadeIn();
	var i = 0;
	var counter = 1;
	for(i = 1; i<24; i++){
		var posting = $.post(
			'../include/registroCorso.php',
			{
			 "ora" : i
			});
			posting.done(function( data ){
				if(data == "LOGINPROBLEM"){
					window.location = "../index.php";
				}
					window.open("../include/tmp/registrini/"+data, '_blank');
					console.log(data);
					if(counter>=23){
						$("#wait").fadeOut();
					}
					$(".determinate").css("width", counter/23*100+"%");
					counter++;
			});
	}
}

function generaOreBuche(){
	$("#wait").css("height:100%");
	$("#wait").fadeIn();
	var i = 0;
	var counter = 1;
	for(i = 1; i<24; i++){
		var posting = $.post(
			'../include/oreBuche.php',
			{
			 "ora" : i
			});
			posting.done(function( data ){
				if(data == "LOGINPROBLEM"){
					window.location = "../index.php";
				}
					window.open("../include/tmp/orebuche/"+data, '_blank');
					console.log(data);
					if(counter>=23){
						$("#wait").fadeOut();
					}
					$(".determinate").css("width", counter/23*100+"%");
					counter++;
			});
	}

}

function generaCorsiByDocenti(){
	$("#wait").css("height:100%");
	$("#wait").fadeIn();
	var posting = $.post(
		'../include/corsiByDocente.php',
		{
			1:1
		});
		posting.done(function( data ){
			if(data == "LOGINPROBLEM"){
				window.location = "../index.php";
			}
			window.location = "../include/tmp/"+data;
			console.log(data);
		});
	}


	function generaCorsiByTitolo(){
		$("#wait").css("height:100%");
		$("#wait").fadeIn();
		var posting = $.post(
			'../include/corsiByTitolo.php',
			{
				1:1
			});
			posting.done(function( data ){
				if(data == "LOGINPROBLEM"){
					window.location = "../index.php";
				}
				window.location = "../include/tmp/"+data;
				console.log(data);
			});
		}


		function generaCorsiByAula(){
			$("#wait").css("height:100%");
			$("#wait").fadeIn();
			var posting = $.post(
				'../include/corsiByAula.php',
				{
					1:1
				});
				posting.done(function( data ){
					if(data == "LOGINPROBLEM"){
						window.location = "../index.php";
					}
					window.location = "../include/tmp/"+data;
					console.log(data);
				});
			}



	function generaCorsiByOra(){
		$("#wait").css("height:100%");
		$("#wait").fadeIn();
		var posting = $.post(
			'../include/corsiByOra.php',
			{
				1:1
			});
			posting.done(function( data ){
				if(data == "LOGINPROBLEM"){
					window.location = "../index.php";
				}
				window.location = "../include/tmp/"+data;
				console.log(data);
			});
		}


function mostraOrarioStudente(idUtente){
	var posting = $.post(
		'../include/generaOrario.php',
		{"id":idUtente});
	posting.done(function(data){
		$("#modal-orario .modal-content").html("<div class='container'>"+data+"</div>");
		$("#modal-orario").openModal();
	});
}

function mostraOrarioDocente(idUtente){
	var posting = $.post(
		'../include/generaOrario.php',
		{"id":idUtente});
	posting.done(function(data){
		$("#modal-orario .modal-content").html("<div class='container'>"+data+"</div>");
		$("#modal-orario").openModal();
	});
}

function cercaStudente(){
	var oraNum = (parseInt($("#selezionaGiornoCerca").val())-1)*6+parseInt($("#selezionaOraCerca").val());
	var posting = $.post(
		'../include/cercaStudente.php',
		{
			nome:$("#nomeStudenteCerca").val(),
			cognome:$("#cognomeStudenteCerca").val(),
			ora:oraNum
		}
	);
	posting.done(function(data){
		$("#modal-orario .modal-content").html(data);
		$("#modal-orario").openModal();
	});

}

function mostraModalDettagli(id, idDocente){
	var posting = $.post(
		'../include/mostraOre.php',
		{
			idCorso:id
		}
	);
	posting.done(function(data){
		$("#modal-ore").html(data);
		$('select').material_select();
		var posting = $.post(
			'../include/generaElencoDocenti.php', {"idDocente":idDocente}
		);
		posting.done(function( data ){
			$("#selezionaDocentiCorso").html('<option value="" disabled selected class="grey-text">Seleziona insegnante</option>'+data);
			$('select').material_select();
		});
		$("#modal-ore").openModal();
	});
}

function modificaCorso(id){
	var posting = $.post(
		'../include/modificaCorsi.php',
		{
			idCorso: id,
			titolo: $("#titoloCorso"+id).val(),
			descrizione: $("#descrizioneCorso"+id).val(),
			continuita:  $("#continuitaCorso"+id).val(),
			tipo: $("#tipoCorso"+id).val()
		}
	);
	posting.done(function(data){
		if(data == "SUCCESS"){
			Materialize.toast('Corso modificato con successo!', 4000);
		}
		else{
			Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
			console.log(data);
		}
	});
}

function eliminaClasse(id){
	var posting = $.post('../include/eliminaClasse.php',{"id":id});
	posting.done(function(data){
		if(data == "SUCCESS"){
			aggiornaListaSezioni();
		}
		else{
			Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
			console.log(data);
		}
	});
}
function aggiornaListaSezioni(){
	var posting = $.post('../include/elencoSezioni.php',{1:1});
	posting.done(function(data){
		$("#dettagliSezioni").html('	<li class="collection-header blue-text center"><h4 class="condensed light">ELENCO SEZIONI</h4></li>'+data);
	});
}

function applicaModificaOre(idCorso){
	console.log("CIAO");
	$("#modal-ore .row").each(function(i){
		var ora = (parseInt($("#selezionaGiornoModificaOre"+i).val())-1)*6+parseInt($("#selezionaOraModificaOre"+i).val());
		var posting = $.post(
			'../include/modificaOra.php',
			{
				idLezione:$("#row"+i).data("idlezione"),
				titolo:$("#titoloModificaOre"+i).val(),
				ora:ora,
				aula:$("#aulaModificaOre"+i).val(),
				maxIscritti:$("#maxIscrittiModificaOre"+i).val()
			});
			posting.done(function(data){
				if(data=="SUCCESS"){
						Materialize.toast('Modifica effettuata con successo', 4000);
				}
				else if(data=="MAXISCRITTI_ERROR"){
					Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero massimo di iscritti inferiore agli iscritti!', 4000);
				}	else if(data=="DOMENICA_ERROR"){
						Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Non è possibile modificare l\'ora: ci sono studenti iscritti.', 4000);
				}	else{
					Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (3). Controlla la console', 4000);
					console.log(data);

				}
			});
	});
}

(function($){
	$(function(){
		aggiornaListaDocenti();
		aggiornaListaSezioni();
		$('.modal-trigger').leanModal();
		$("#aggiungi-docente").submit(function(e){
			e.preventDefault();
			if($("#password").val() !=$("#ripeti_password").val())
				 Materialize.toast('Le due password non coincidono!', 4000);
			else{
				var posting = $.post(
					'../include/aggiungiDocente.php',
					{submit: 1, username: $("#username").val(), password: $("#password").val(), nome: $("#nome").val(), cognome: $("#cognome").val()}
				);
				posting.done(function( data ){
					if(data=="SUCCESS"){
						 Materialize.toast('Docente aggiunto con successo!', 4000);
					}
					else if (data=="EXISTS")
					{
						Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Nome utente già esistente', 4000);
						console.log(data);
					}
					else{
						 Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
						console.log(data);
					}

				});
			}
		});

		$("#aggiungiClasse").on("click", function(){
			var posting = $.post(
				'../include/creaClasse.php',
				{
					"classe":$("#selezionaClasseStudente").val(),
					"sezione":$("#sezione").val()
				}
			);
			posting.done(function(data){
				if(data == "SUCCESS"){
					 Materialize.toast('Classe creata con successo!', 4000);
					 aggiornaListaSezioni();
				}
				else{
					 Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
					 console.log(data);
				}
			});
		});

		$("#aggiungi-studente").submit(function(e){
			e.preventDefault();
			if($("#passwordStudente").val() !=$("#ripeti_passwordStudente").val())
				 Materialize.toast('Le due password non coincidono!', 4000);
			else{
				var posting = $.post(
					'../include/aggiungiStudente.php',
					{
						submit: 1,
						username: $("#usernameStudente").val(),
						classe: $("#selezionaClasseStudente").val(),
						password: $("#passwordStudente").val(),
						nome: $("#nomeStudente").val(),
						cognome: $("#cognomeStudente").val()}
				);
				posting.done(function( data ){
					if(data=="SUCCESS"){
						 Materialize.toast('Studente aggiunto con successo!', 4000);
					}
					else if (data=="EXISTS")
					{
						Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Nome utente già esistente', 4000);
						console.log(data);
					}
					else{
						 Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
						console.log(data);
					}

				});
			}
		});

		$("#inserisciOre").on("click", function(){
			var val = $("#ore").val();
			$("#ore_future").hide();
			if(val == "" || isNaN(val))
				Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore non valido', 4000);
			else if(val>20)
				Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Numero di ore troppo alto, massimo 20', 4000);
			else{
				$("#wait").css("height:100%");
				$("#wait").fadeIn();
				var posting = $.post(
					'../include/creaOre.php',
					{ore: val}
				);
				posting.done(function( data ){
					$("#ore_future").html('<ul id="elenco_ore">'+data+'</ul> ');
					$("#elenco_ore").css("opacity: 0");
					$('#ore_future').show().css("opacity:0");
					$("#elenco_ore").fadeIn();
					$('select').material_select();
					$("#aggiungiCorso").removeClass("disabled");
					$("#wait").fadeOut();
				});

			}
		});
	  });
})(jQuery);
