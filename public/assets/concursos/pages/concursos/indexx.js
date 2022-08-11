$(document).ready(function () {
    var form;

    $('#data-table').DataTable({
        language: {
            url: '/assets/assets/extra-libs/DataTables/es_es.json'
        }
    });

    $('.boton-postulacion').on('click', function (event) {
        event.preventDefault();
        form = $(this).parents('form:first');
        $('#confirmar-postulacion').modal('show');
    });

    $('#accion-postulacion').on('click', function (event) {
        event.preventDefault();
        $('#confirmar-postulacion').modal('hide');
        form.submit();
    });
});
