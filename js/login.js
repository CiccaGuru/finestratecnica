$(function(){
	$("#form-login").submit(function(e){
		e.preventDefault();
		$("#wait").fadeIn();
		var posting = $.post(
		  './include/login.php',
			{submit: 1, username: $("#username").val(), password: $("#password").val()}
		);
		posting.done(function( data ) {
			if(data=="PROCEDISTUDENTE")
				window.location = "./userhome.php";
			else if(data=="PROCEDIPROFESSORE")
				window.location = "./docente.php";
			else if(data=="PROCEDIADMIN")
				window.location = "./admin.php";
			else
				$("#contenitore-cerchio").html("<p id='errore-login' class='white-text'>"+data+"</p> <a id='riprova' onclick='riprova()' class='waves-effect waves-light btn-large light-blue'>Riprova</a>");
		});
	});



});

function riprova(){
		$("#wait").fadeOut().html('<div id="contenitore-cerchio" class="valign"> 	<div class="preloader-wrapper big active"> <div class="spinner-layer spinner-primary-only"> <div class="circle-clipper right"> <div class="circle"></div>  </div> </div> </div>');
	}
