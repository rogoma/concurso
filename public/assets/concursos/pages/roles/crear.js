$(document).ready(function () {
    APP.validateGeneral('form-general');

    $("#rol").keyup(function () {
        var value = $(this).val();
        value = value.toUpperCase();
        $("#rol").val(value);
    });

    $("#rol").keyup(function () {
        var value = $(this).val();
        value = value.toLowerCase();
        $("#slug").val(value);
    });
});