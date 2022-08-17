var listKeyEntrada = [];
var listKeySalida = [];

function addKey()
{
    let url = '/index.php?r=registro/ajax-add-key';
    let code = $('#id_llave').val();
    let operacion = $('#id_operacion').val();
    var strTable = 'tblKeyEntrada';
    if(operacion=='E'){
        strTable = 'tblKeyEntrada';
        strDiv = '<div class="alert alert-success alert-dismissible"><i class="fas fa-plus"></i>';
    }else{
        strTable = 'tblKeySalida';
        strDiv = '<div class="alert alert-danger alert-dismissible"><i class="fas fa-plus"></i>';
    }

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'GET',
        data: {
            "code": code
        },

        success: function (data) {
            console.log(data);
            //Validar que no exista
            var bolInserRow = true;

            if(operacion=='E'){
                listKeyEntrada.forEach(function(key, index, object) {
                    if(parseInt(key) === parseInt(data.llave.id)){
                        bolInserRow = false;
                    }
                });
                if(data.estado=='Entrada'){
                    alert('La llave tiene una entrada activa, no se puede volver a ingresar.');
                    bolInserRow = false;
                }
            }else{
                listKeySalida.forEach(function(key, index, object) {
                    if(parseInt(key) === parseInt(data.llave.id)){
                        bolInserRow = false;
                    }
                });
                if(data.estado=='Salida'){
                    alert('La llave tiene una salida activa, no se pueden registrar mas salidas.');
                    bolInserRow = false;
                }
            }

            if(bolInserRow==true){
                $('#'+strTable+' tbody') // select table tbody
                    .prepend('<tr id="tr_'+data.llave.id+'" />') // prepend table row
                    .children('tr:first') // select row we just created
                    .append('<td>'+strDiv+' '+data.llave.codigo+'</div></td>\n' +
                        '<td>'+data.llave.descripcion+'</td>\n' +
                        '<td>'+data.comunidad.nombre+'</td>\n' +
                        '<td><button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="delKey('+data.llave.id+')"><i class="fas fa-times-circle"></i></button> </td>') // append four table cells to the row we created


                if(operacion=='E'){
                    listKeyEntrada.push(data.llave.id);
                }else{
                    listKeySalida.push(data.llave.id);
                }

                $('#id_llave').val('');
            }
        }
    });
}

function delKey(id)
{
    let operacion = $('#id_operacion').val();
    if(operacion=='E'){
        listKeyEntrada.forEach(function(key, index, object) {
            if(parseInt(key) === id){
                object.splice(index, 1);
                $('#tr_'+id).remove();
            }
        });
    }else{
        listKeySalida.forEach(function(key, index, object) {
            if(parseInt(key) === id){
                object.splice(index, 1);
                $('#tr_'+id).remove();
            }
        });
    }


}

function sendForm()
{
    let url = '/index.php?r=registro/ajax-reg-mov';
    let observacion = $('#txt_observacion').val();
    let comercial = $('#id_comercial').val();

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "observacion": observacion,
            "comercial":comercial,
            "listKeyEntrada": listKeyEntrada,
            "listKeySalida": listKeySalida,
        },

        success: function (data) {
            listKeyEntrada.forEach(function(key, index, object) {
             object.splice(index, 1);
             $('#tr_'+index).remove();
            });
            listKeySalida.forEach(function(key, index, object) {
                object.splice(index, 1);
                $('#tr_'+index).remove();
            });

            $('#div_msm').show("slow");
            $('#div_info').hide();

            $('#txt_observacion').val('');
            $('#id_llave').attr('readonly', true);
            $('#btn_registrar').attr('readonly', true);

            setTimeout("location.reload(true);",500);
        }
    });
}

function fnSetOperacion(strOperacion){
    $('#id_operacion').val(strOperacion);
}
