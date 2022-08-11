$(document).ready(function () {
    APP.validateGeneral('form-general');

    const generateDatepicker = (name)=>{
      return jQuery(`#${name}`).datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        orientation: "bottom auto",
        todayHighlight: true
      });
    }

    const array = [
        'ini_post',
        'fin_post',
        'ini_eva_doc',
        'fin_eva_doc',
        'ini_eva_cur',
        'fin_eva_cur',
        'ini_examen',
        'fin_examen',
        'ini_entrevista',
        'fin_entrevista'
    ]
    for(const data of array){
        generateDatepicker('datepicker-autoclose_'+data)
    }

    function passDate(date, objeto) {
        var value = date;
        arr = value.split('-');
        if (arr[2].length == 2) {
            valuen = arr[0] + '-' + arr[1] + '-' + arr[2];
        } else {
            valuen = arr[2] + '-' + arr[1] + '-' + arr[0];
        }
        $("#"+objeto).val(valuen);
    }

    /*$("#datepicker-autoclose_ini").blur(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_ini").keyup(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_ini").mouseout(function () {
        passDateIni();
    });

    $("#datepicker-autoclose_ini").change(function () {
        passDateIni();
    });*/


    $("#institucion").click(function () {
        passDateIni();
        passDateFin();
    });

    $("#constancia").click(function () {
        passDateIni();
        passDateFin();
    });


});
/*
'ini_post' => 'required|date_format:Y-m-d',
            'fin_post' => 'required|date_format:Y-m-d',
            'ini_eva_doc' => 'required|date_format:Y-m-d',
            'fin_eva_doc' => 'required|date_format:Y-m-d',
            'ini_eva_cur' => 'required|date_format:Y-m-d',
            'fin_eva_cur' => 'required|date_format:Y-m-d',
            'ini_examen' => 'required|date_format:Y-m-d',
            'fin_examen' => 'required|date_format:Y-m-d',
            'ini_entrevista' => 'required|date_format:Y-m-d',
            'fin_entrevista' => 'required|date_format:Y-m-d',


 */
