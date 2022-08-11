$(document).ready(function () {
    APP.validateGeneral('form-general');

    $("#descripcion").keyup(function () {
        var value = $(this).val();
        value = value.toUpperCase();
        $("#descripcion").val(value);
    });
});
