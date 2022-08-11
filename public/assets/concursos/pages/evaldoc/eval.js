$(document).ready(function () {

    APP.validateGeneral('form-general');

});

function selectAll() {
    var check = document.getElementsByName("All"),
        radios = document.matriz.elements;

        //If the first radio is checked
        if (check[0].checked) {
          for( i = 0; i < radios.length; i++ ) {
            //And the elements are radios
            if( radios[i].type == "radio" ) {
                //And the radio elements's value are 1
                if (radios[i].value == 1 ) {
                    //Check all radio elements with value = 1
                    radios[i].checked = true;
                }
            }//if
          }//for
        //If the second radio is checked
        } else {
            for( i = 0; i < radios.length; i++ ) {
                //And the elements are radios
                if( radios[i].type == "radio" ) {
                    //And the radio elements's value are 0
                    if (radios[i].value == 0 ) {
                        //Check all radio elements with value = 0
                        radios[i].checked = true;
                    }
                }//if
            }//for
        };//if
    return null;
}

function putRequired(id) {
    var str_cumple = 'cumple'+id;
    var str_rechazo = 'rechazo'+id;
    //var str_obs = 'obs'+id;
    document.getElementById(str_rechazo).setAttribute('required', "");
    //document.getElementById(str_obs).setAttribute('required', "");
}

function remRequired(id){
    var str_cumple = 'cumple'+id;
    var str_rechazo = 'rechazo'+id;
    //var str_obs = 'obs'+id;
    document.getElementById(str_rechazo).removeAttribute('required');
    //document.getElementById(str_rechazo).setAttribute('selected', true);
}

function putRequiredAll() {
    var collection = document.getElementsByName('motivo_rechazo_id[]');
    for (var i = 0; i < collection.length; i++) {
        collection[i].setAttribute('required', "");
    }
}

function remRequiredAll(){
    var collection = document.getElementsByName('motivo_rechazo_id[]');
    for (var i = 0; i < collection.length; i++) {
        collection[i].removeAttribute('required');
        //collection[i].setAttribute('selected', true);
    }
}

function selSinMotivo(id) {
    var rechazo = 'rechazo'+id;
    //$("#"+rechazo+" option[value='']").attr("selected", true);
    $("#"+rechazo) .val('') .trigger('change');
    //$("#"+rechazo) .first() .trigger('change');
 }
