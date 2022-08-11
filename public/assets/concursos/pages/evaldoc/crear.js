$(document).ready(function () {
	APP.validateGeneral('form-general');

    $.fn.dataTable.moment('DD-MM-YYYY HH:mm');
	$('#data-table').DataTable({
        //"ServerSide": true,
        //"ajax": {{ url('api/usuarios') }},
        /*"comluns": [
            {data: 'id'},
            {data: 'ci'},
            {data: 'email'},
            {data: 'fecha_crea'},
        ],*/
        "ordering": false,
        //"order": [[ 3, "asc" ]],
        language: {
            url: '/assets/assets/extra-libs/DataTables/es_es.json'
        }
    });


	//***********************************//
	// For select 2
	//***********************************//
	$(".select2").select2();

	/*datwpicker*/
	jQuery('#datepicker-autoclose_desde').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
        orientation: "bottom auto",
		todayHighlight: true
	});
	jQuery('#datepicker-autoclose_hasta').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
        orientation: "bottom auto",
		todayHighlight: true
	});

    function passDateIni() {
        var value = $("#datepicker-autoclose_desde").val();
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#fecha_desde").val(valuen);
    }

    function passDateFin() {
        var value = $("#datepicker-autoclose_hasta").val();
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#fecha_hasta").val(valuen);
    }


    $("#datepicker-autoclose_desde").blur(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_desde").keyup(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_desde").change(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_hasta").blur(function () {
        passDateFin();
    });

    $("#datepicker-autoclose_hasta").keyup(function () {
        passDateFin();
    });

    $("#datepicker-autoclose_hasta").change(function () {
        passDateFin();
    });
});
