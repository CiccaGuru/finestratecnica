function eliminaClasse(id) {
    var posting = $.post('../include/eliminaClasse.php', {
        "id": id
    });
    posting.done(function(data) {
        if (data == "SUCCESS") {
            aggiornaListaSezioni();
        } else {
            Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore. Controlla la console', 4000);
            console.log(data);
        }
    });
}

function aggiornaListaSezioni() {
    var posting = $.post('../include/elencoSezioni.php', {
        1: 1
    });
    posting.done(function(data) {
        $("#dettagliSezioni").html('	<li class="collection-header blue-text center"><h4 class="condensed light">ELENCO SEZIONI</h4></li>' + data);
    });
}

(function($) {
    $(function() {
        aggiornaListaSezioni();
        $("#aggiungiClasse").on("click", function() {
            var posting = $.post(
                '../include/creaClasse.php', {
                    "classe": $("#selezionaClasseStudente").val(),
                    "sezione": $("#sezione").val()
                }
            );
            posting.done(function(data) {
                if (data == "SUCCESS") {
                    Materialize.toast('Classe creata con successo!', 4000);
                    aggiornaListaSezioni();
                } else {
                    Materialize.toast('<i class="material-icons red-text" style="margin-right:0.2em">error</i> Si è verificato un errore, controlla la console', 4000);
                    console.log(data);
                }
            });
        });
    });
})(jQuery);
