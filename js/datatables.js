// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "order": [[ 0, "desc" ]]
  } );
});

//SLOVAK TRANSLATION
/*$(document).ready(function() {
  $('#dataTable').DataTable({
    "language": {
      "lengthMenu": "Zobraziť _MENU_ záznamov na stránku",
      "zeroRecords": "Nič sa nenašlo",
      "info": "Zobrazuje sa stránka _PAGE_ z _PAGES_",
      "infoEmpty": "K dispozícii nie sú žiadne záznamy",
      "infoFiltered": "(filtrované z celkového počtu záznamov _MAX_)",
      "search": "Vyhľadávanie:",
      "paginate": {
        "previous": "Predchádzajúca",
        "next": "Ďalšia"
      }
    },
    "order": [[ 0, "desc" ]]
  } );
});*/