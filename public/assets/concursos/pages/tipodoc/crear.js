$(document).ready(function () {
    APP.validateGeneral('form-general');

    $("#documento").keyup(function () {
        var value = $(this).val();
        value = value.toUpperCase();
        $("#documento").val(value);
    });
});