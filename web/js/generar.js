var listKeyCheck = [];

/**
 * Esta funcion se ejecuta cada vez que se preiona el boton de adicionar llave a contrato
 * @param idLlave
 */
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
 * Eliminar fila del registro
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
 * Buscar informacion basica de una llave especifica y agregarla en la tabla de llaves seleccionadas
 * @param idLlave
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
            //console.log(infoLlave);
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
 * Buscar informacion basica de una llave especifica
 * Cargar informacion en ventana modal
 * @param infoLlaveId
 */
function getInfoLlaveCard(infoLlaveId){

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

/**
 * Envio de formulario
 */
function sendForm(){
    let listLlaves = null;
    let idContrato = null;
    let strMensaje = '';

    listLlaves = JSON.stringify(listKeyCheck);
    idContrato = $('#contratoslog-id_contrato').val();

    if(idContrato==''){
        strMensaje += 'El formato de contrato no puede estar vacio.\n ';
        $('#contratoslog-id_contrato').focus();
    }

    if(listKeyCheck.length==0){
        strMensaje += 'Debe seleccionar por lo menos una llave para agregar al contrato.\n ';
        $('#llavesearch-llavelaststatus').focus();
    }

    if(strMensaje!=''){
        toastr.error('Atención:\n'+strMensaje);
        return false;
    }

    $('#parametros').val(listLlaves);
    $('#generar-form').submit();
    return true;
}

/**
 *
 */
function fnReloadSeleccionLlaves(){
    let parametros = $('#parametros').val();
    let arrParam = parametros.split(',');
    if(parametros!='' && arrParam.length>0){
        arrParam.forEach(function(key, index, object) {
            //console.log(key);
            selectChk(parseInt(key));
        });
    }
}