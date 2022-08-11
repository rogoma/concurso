$(document).ready(function () {
    var form;

    $('#data-table').DataTable({
        "ordering": false,
        language: {
            url: '/assets/assets/extra-libs/DataTables/es_es.json'
        }
    });

    $('.boton-rendir').on('click', function (event) {
        event.preventDefault();
        form = $(this).parents('form:first');
        $('#confirmar-rendir').modal('show');
    });

    $('#accion-rendir').on('click', function (event) {
        event.preventDefault();
        $('#confirmar-rendir').modal('hide');
        form.submit();
    });
});
