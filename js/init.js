/*function caricaInfo(){
  return;
}
*/
(function($){
  $(function(){
    $('.modal').modal();
    $(".button-collapse").sideNav();
    $('.collapsible').collapsible({
      accordion : true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
    $('.tooltipped').tooltip({delay: 50});
    $('select').material_select();
    $('.datepicker').pickadate({
      labelMonthNext: 'Mese prossimo',
      labelMonthPrev: 'Mese precedente',

      // The title label to use for the dropdown selectors
      labelMonthSelect: 'Seleziona un mese',
      labelYearSelect: 'Seleziona un anno',

      // Months and weekdays
      monthsFull: [ 'Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre' ],
      monthsShort: [ 'Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic' ],
      weekdaysFull: [ 'Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato' ],
      weekdaysShort: [ 'Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab' ],

      // Materialize modified
      weekdaysLetter: [ 'D', 'L', 'M', 'M', 'G', 'V', 'S' ],

      // Today and clear
      today: 'Oggi',
      clear: 'Pulisci',
      close: 'Chiudi',
// Formats
format: 'ddd, dd mmmm yyyy',
  formatSubmit: 'dd/mm/yyyy'

}
    );
  Materialize.updateTextFields();
  }); // end of document ready
})(jQuery); // end of jQuery name space
