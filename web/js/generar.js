var listKeyCheck = [];

function selectChk(idLlave){
    let bolInserRow = true;
    let infoLlave = [];
    listKeyCheck.forEach(function(key, index, object) {
        if(parseInt(key) === parseInt(idLlave)){
            bolInserRow = false;
        }
    });

    if(bolInserRow==true){
        listKeyCheck.push(idLlave);
        infoLlave = fnFindLlave(idLlave);
    }
}

/**
 * Eliminar fila del resgitro
 * @param id
 */
function delKey(idLlave)
{
    listKeyCheck.forEach(function(key, index, object) {
        //console.log(idLlave+';'+key+';'+index);
        if(parseInt(key) === parseInt(idLlave)){
            object.splice(index, 1);
            $('#tr_'+idLlave).remove();
            $('#'+idLlave).click();
        }
    });

}

/**
 * Envio de formulario
 */
function fnFindLlave(idLlave)
{
    let url = '/index.php?r=contratos/ajax-find-llave';
    let infoLlave = [];

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "id": idLlave
        },
        success: function (data) {
            infoLlave = data;
            console.log(infoLlave);
            $('#tblKeyCheck tbody') // select table tbody
                .prepend('<tr id="tr_'+idLlave+'" />') // prepend table row
                .children('tr:first') // select row we just created
                .append('<td>'+infoLlave['codigo']+'</td>\n' +
                    '<td>'+infoLlave['descripcion']+'</td>\n' +
                    '<td>' +
                    '   <button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="delKey('+idLlave+')"><i class="fas fa-times-circle"></i></button> ' +
                    '   <button type="button" class="btn btn-outline-info btn-block btn-sm" data-toggle="modal" data-target="#modal-default" onclick="getInfoLlaveCard('+idLlave+')"><i class="fas fa-info-circle"></i></button> ' +
                    '</td>') // append four table cells to the row we created
        }
    });
    return true;
}

/**
 * Identificador de la operacion que se esta ejecutando E/S
 * @param strOperacion
 */
function fnSetOperacion(strOperacion){
    $('#id_operacion').val(strOperacion);
}

function getInfoLlaveCard(infoLlaveId){

    //console.log('=================');
    //console.log(infoLlaveId);
    let url = '/index.php?r=contratos/ajax-find-llave';
    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "id": infoLlaveId
        },
        success: function (data) {
            infoLlave = data;
            let alarma = infoLlave['alarma'];
            let estado =  infoLlave['llaveLastStatus'];
            let htmlTipo =  infoLlave['id_tipo'];

            if (parseInt(alarma)==1){
                alarma = 'SI - Codigo:'+infoLlave['codigo_alarma'];
            }else{
                alarma = 'NO';
            }

            if (estado=='S'){
                estado = '<span class="float-none badge bg-danger">Prestada</span>';
            }else{
                estado = '<span class="float-none badge bg-success">Almacenada</span>';
            }


            switch (parseInt(htmlTipo)){
                case 1:
                    htmlTipo = 'bg-success';
                    break;
                case 2:
                    htmlTipo = 'bg-info';
                    break;
                case 3:
                    htmlTipo = 'bg-primary';
                    break;
                default:
                    htmlTipo = 'bg-muted';
            }
            htmlTipo = '<span class="float-none badge '+htmlTipo+'">'+infoLlave['tipo']+'</span>';
            // ------------------------------------------------------
            $('#ll_propietario').html(infoLlave['nombre_propietario']);
            $('#ll_cliente').html(infoLlave['comunidad']);
            $('#ll_tipo').html(htmlTipo);
            $('#ll_codigo').html(infoLlave['codigo']);
            $('#ll_descripcion').html(infoLlave['descripcion']);
            $('#ll_observacion').html(infoLlave['observacion']);
            $('#ll_alarma').html(alarma);
            $('#ll_ubicacion').html(infoLlave['ubicacion']);
            $('#ll_estado').html(estado);

        }
    });
    return true;
}

function sendForm(){
    let listLlave = null;
    listLlave = JSON.stringify(listKeyCheck);
    console.log(listLlave);
    $('#parametros').val(listLlave);
    //alert('enviar');
    $('#generar-form').submit();


}
