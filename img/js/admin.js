function aggiornaDocenti(){
	var posting = $.post(
		'../include/generaElencoDocenti.php', {1:1}
	);
	posting.done(function( data ){
		$("#selezionaDocenti").html('<option value="" disabled selected class="grey-text">Seleziona insegnante</option>'+data);
		$('select').material_select();
	});
}


function dettagliDocenti(){
	var posting = $.post(
		'../include/elencoUtenti.php', {"level":1}
	);
	posting.done(function( data ){
		$("#dettagliDocenti").html('<li class="collection-header blue-text center"><h4 class="light">Elenco docenti</h4></li>'+data);
	});

}

function dettagliStudenti(){
	var posting = $.post(
		'../include/elencoUtenti.php', {"level":0}
	);
	posting.done(function( data ){
		$("#dettagliStudenti").html('<li class="collection-header blue-text center"><h4 class="light">Elenco studenti</h4></li>'+data);
		 $('select').material_select();
	});

}

function eliminaDocente(id_docente){
	var posting = $.post(
		'../include/eliminaDocente.php',
		{id:id_docente}
	);
	posting.done(function( data ){
		if(data=="SUCCESS"){
			 Materialize.toast('Docente eliminato con successo!', 4000);
			 dettagliDocenti();
			 aggiornaDocenti();
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

function modificaDocente(user_id){
  if(($("#nome"+user_id).val()=="") || ($("#cognome"+user_id).val()=="") || ($("#username"+user_id).val()==""))
    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
  else{
    var posting = $.post(
      '../include/modificaUtente.php',
      {
        id:user_id,
        nome:$("#nome"+user_id).val(),
        cognome:$("#cognome"+user_id).val(),
        username:$("#username"+user_id).val()
      }
    );
    posting.done(function( data ){
      if(data=="SUCCESS"){
        Materialize.toast('Utente modificato con succeso!', 4000);
        dettagliDocenti();
        aggiornaDocenti();
      }
      else {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
        console.log(data);
      }
    });
  }
}

function mostraOrario(idUtente){
	var posting = $.post(
		'../include/generaOrario.php',
		{"id":idUtente});
	posting.done(function(data){
		$("#modal-orario .modal-content").html(data);
		$("#modal-orario").openModal();
	});
}

function modificaStudente(user_id){
  if(($("#nome"+user_id).val()=="") || ($("#cognome"+user_id).val()=="") || ($("#username"+user_id).val()==""))
    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i>Dati non validi', 4000);
  else{
    var posting = $.post(
      '../include/modificaStudente.php',
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
        dettagliDocenti();
        aggiornaStudenti();
      }
      else {
        Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
        console.log(data);
      }
    });
  }
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

function mostraOreModifica(id){
	var posting = $.post(
		'../include/mostraOre.php',
		{
			idCorso:id
		}
	);
	posting.done(function(data){
		$("#modal-ore").html(data);
		$('select').material_select();
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
				else{
					Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore (3). Controlla la console', 4000);
					console.log(data);

				}
			});
	});
}

(function($){
	$(function(){
		aggiornaDocenti();
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
						 aggiornaDocfaceboxenti();
						 dettagliDocenti();
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
					$("#wait").fadeOut();
				});

			}
		});
	  });
})(jQuery);
