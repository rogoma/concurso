$(document).ready(function () {

    APP.validateGeneral('form-general');

});

function setTwoNumberDecimal(event) {
    this.value = parseFloat(this.value).toFixed(2);
    this.value = this.value.replace(',', '.');
}
