$(document).ready(function () {
	APP.validateGeneral('form-general');

	//***********************************//
	// For select 2
	//***********************************//
	$(".select2").select2();

	/*datwpicker*/
	jQuery('#datepicker-autoclose_ini').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
        orientation: "bottom auto",
		todayHighlight: true
	});
	jQuery('#datepicker-autoclose_fin').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
        orientation: "bottom auto",
		todayHighlight: true
	});

    function passDateIni() {
        var value = $("#datepicker-autoclose_ini").val();
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#fecha_ini").val(valuen);
    }

    function passDateFin() {
        var value = $("#datepicker-autoclose_fin").val();
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#fecha_fin").val(valuen);
    }


    $("#datepicker-autoclose_ini").blur(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_ini").keyup(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_ini").change(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_fin").blur(function () {
        passDateFin();
    });

    $("#datepicker-autoclose_fin").keyup(function () {
        passDateFin();
    });

    $("#datepicker-autoclose_fin").change(function () {
        passDateFin();
    });

    $("#ref_laboral").click(function () {
        passDateIni();
        passDateFin();
    });

    $("#tel_ref_lab").click(function () {
        passDateIni();
        passDateFin();
    });

    $("#constancia").click(function () {
        passDateIni();
        passDateFin();
    });

});
