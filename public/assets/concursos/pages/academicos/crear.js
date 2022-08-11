$(document).ready(function () {
	APP.validateGeneral('form-general');

	//***********************************//
	// For select 2
	//***********************************//
	$(".select2").select2();

	/*datwpicker*/
	jQuery('#datepicker-autoclose').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
        orientation: "bottom auto",
		todayHighlight: true
	});

    function passDate() {
        var value = $("#datepicker-autoclose").val();
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#fecha_graduac").val(valuen);
    }

    $("#datepicker-autoclose").blur(function () {
        passDate();
    });

    $("#datepicker-autoclose").keyup(function () {
        passDate();
    });

    $("#datepicker-autoclose").mouseout(function () {
        passDate();
    });

    $("#datepicker-autoclose").change(function () {
        passDate();
    });

    $("#institucion").click(function () {
        passDate();
    });

    $("#profesion").click(function () {
        passDate();
    });

    $("#dato_adic").click(function () {
        passDate();
    });

});
