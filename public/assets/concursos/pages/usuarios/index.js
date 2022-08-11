$(document).ready(function () {
    var form;

    $('#data-table').DataTable({
        //"ServerSide": true,
        //"ajax": {{ url('api/usuarios') }},
        /*"comluns": [
            {data: 'id'},
            {data: 'ci'},
            {data: 'email'},
            {data: 'fecha_crea'},
        ],*/
        language: {
            url: '/assets/assets/extra-libs/DataTables/es_es.json'
        }
    });

    $('.boton-eliminar').on('click', function (event) {
        event.preventDefault();
        form = $(this).parents('form:first');
        $('#confirmar-eliminar').modal('show');
    });

    $('#accion-eliminar').on('click', function (event) {
        event.preventDefault();
        $('#confirmar-eliminar').modal('hide');
        form.submit();
    });
});
