var listKeyEntrada = [];
var listKeySalida = [];
var dataURL;

/**
 * adicion de llave
 */
function addKey()
{
    let code = $('#id_llave').val();
    let operacion = $('#id_operacion').val();
    addKeyForm(code,operacion,false);
}

/**
 * Funcionalidad de adiciond de llaves al listado
 */
function addKeyForm(code,operacion,modal)
{
    let url = strAjaxAddKey;
    var strTable = 'tblKeyEntrada';
    if(operacion=='E'){
        strTable = 'tblKeyEntrada';
        strDiv = '<div class="alert alert-danger alert-dismissible"><i class="fas fa-plus"></i>';
    }else{
        strTable = 'tblKeySalida';
        strDiv = '<div class="alert alert-success alert-dismissible"><i class="fas fa-plus"></i>';
    }

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'GET',
        data: {
            "code": code
        },

        success: function (data) {
            //console.log(data);
            //Validar que no exista
            var bolInserRow = true;

            if(data.llave==null){
                toastr.error('El codigo ingresado no es valido o esta deshabilitado.');
                return true;
            }

            if(!!data.error){
                toastr.error(data.error);
                return true;
            }

            if(modal==true){
                toastr.success('Llave '+code+' Seleccionada.');
                $('#tr_'+code).hide(500,'');
            }

            if(operacion=='E'){
                listKeyEntrada.forEach(function(key, index, object) {
                    if(parseInt(key) === parseInt(data.llave.id)){
                        bolInserRow = false;
                    }
                });
                if(data.estado=='E'){
                    toastr.warning('La llave tiene una entrada activa, no se puede volver a ingresar.');
                    bolInserRow = false;
                }
            }else{
                listKeySalida.forEach(function(key, index, object) {
                    if(parseInt(key) === parseInt(data.llave.id)){
                        bolInserRow = false;
                    }
                });
                if(data.estado=='S'){
                    toastr.warning('La llave tiene una salida activa, no se pueden registrar mas salidas.');
                    bolInserRow = false;
                }
            }

            if(bolInserRow==true){
                $('#'+strTable+' tbody') // select table tbody
                    .prepend('<tr id="tr_'+data.llave.id+'" />') // prepend table row
                    .children('tr:first') // select row we just created
                    .append('<td>'+strDiv+' '+data.llave.codigo+'</div></td>\n' +
                        '<td>'+data.llave.descripcion+'</td>\n' +
                        '<td>'+data.cliente+'</td>\n' +
                        '<td><button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="delKey('+data.llave.id+')"><i class="fas fa-times-circle"></i></button> </td>') // append four table cells to the row we created


                if(operacion=='E'){
                    listKeyEntrada.push(data.llave.id);
                }else{
                    listKeySalida.push(data.llave.id);
                }
            }
            $('#id_llave').val('');
        }
    });
}


/**
 * Funcionalidad de adiciond de llaves al listado, esta se ejecuta cargando toda la info del movimiento
 */
function fnLoadRegistro(numIdRegistro,strAction)
{
    let url = strAjaxFindKeys;
    var strTable = 'tblKeyEntrada';
    var bolInserRow = true;

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'GET',
        data: {
            "numIdRegistro": numIdRegistro
        },
        success: function (data) {
            data.all.forEach(function(data) {
                bolInserRow = true;
                if (data.status != data.llaveLastStatus) {
                    let strEstado = (data.llaveLastStatus == 'E') ? 'Entrada' : 'Salida';
                    toastr.warning('La llave ' + data.codigo + ' tiene una ' + strEstado + ' activa, valide el estado actual.');
                    bolInserRow = false;
                    return;
                }

                if(strAction=='update'){
                    operacion = data.status ;
                }else{
                    //Validar operacion - inversa
                    operacion = data.status == 'E' ? 'S' : 'E';
                }

                if (operacion == 'E') { // Entrada-retorno
                    strTable = 'tblKeyEntrada';
                    $('#custom-tabs-entrada-tab').click();
                    strDiv = '<div class="alert alert-danger alert-dismissible"><i class="fas fa-plus"></i>';
                } else { // prestamo - entrega de llave a externos
                    strTable = 'tblKeySalida';
                    strDiv = '<div class="alert alert-success alert-dismissible"><i class="fas fa-plus"></i>';
                }

                if (operacion == 'E') {
                    listKeyEntrada.forEach(function (key, index, object) {
                        if (parseInt(key) === parseInt(data.llave_id)) {
                            bolInserRow = false;
                        }
                    });
                } else {
                    listKeySalida.forEach(function (key, index, object) {
                        if (parseInt(key) === parseInt(data.llave_id)) {
                            bolInserRow = false;
                        }
                    });
                }

                if(bolInserRow==true){
                    $('#'+strTable+' tbody') // select table tbody
                        .prepend('<tr id="tr_'+data.llave_id+'" />') // prepend table row
                        .children('tr:first') // select row we just created
                        .append('<td>'+strDiv+' '+data.codigo+'</div></td>\n' +
                            '<td>'+data.descripcion_llave+'</td>\n' +
                            '<td>'+data.cliente+'</td>\n' +
                            '<td><button type="button" class="btn btn-outline-danger btn-block btn-sm" onclick="delKey('+data.llave_id+')"><i class="fas fa-times-circle"></i></button> </td>') // append four table cells to the row we created

                    if(operacion=='E'){
                        listKeyEntrada.push(data.llave_id);
                    }else{
                        listKeySalida.push(data.llave_id);
                    }
                }
            });
        }
    });
}

/**
 * Eliminar fila del resgitro
 * @param id
 */
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

/**
 * Envio de formulario de registro
 */
function sendForm()
{
    let url = strAjaxRegisterMotion;
    var form = $('#form-registro');
    var formData = form.serialize();

    // validar que exista una llave de entrada o salida
    if (listKeySalida.length == 0 && listKeyEntrada.length == 0) {
        toastr.warning('Validar las llaves relacionadas.');
        $('#id_llave').focus();
        return true;
    }

    if ($('#id_propietario').val()==null && $('#id_comercial').val()==null) {
        toastr.warning('Debe seleccionar un Propietario o Empresa/Proveedor.');
        $('#id_comercial').focus();
        return true;
    }

    //validar que el campo responsable este lleno
    let  strNombreResponsable =  $('#nombre_responsable').val().trim();
    if (strNombreResponsable.length == 0) {
        toastr.warning('El campo responsable no puedes estar vacio.');
        $('#nombre_responsable').focus();
        return true;
    }


    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: formData+"&listKeyEntrada="+JSON.parse(JSON.stringify(listKeyEntrada))+"&listKeySalida="+JSON.parse(JSON.stringify(listKeySalida)),
        success: function (data) {
            listKeyEntrada.forEach(function(key, index, object) {
             object.splice(index, 1);
             $('#tr_'+index).remove();
            });
            listKeySalida.forEach(function(key, index, object) {
                object.splice(index, 1);
                $('#tr_'+index).remove();
            });

            toastr.success('Registro almacenado correctamente.');
            $('#div_msm').show("slow");
            $('#div_info').hide();

            $('#txt_observacion').val('');
            $('#id_llave').attr('readonly', true);
            $('#btn_registrar').attr('readonly', true);

            if (!signaturePad.isEmpty()) {
                fnGuardarCuadroFirma();
            }else {
                //toastr.warning('El registro no incluye firma digital.');
                setTimeout("window.location = strUrl;",600);
            }
        }
    });
}

/**
 * Identificador de la operacion que se esta ejecutando E/S
 * @param strOperacion
 */
function fnSetOperacion(strOperacion){
    $('#id_operacion').val(strOperacion);
}

/**
 * Limpiar cuadro de firma
 */
function fnLimpiarCuadroFirma(){
    var objButtonClear = $(".signature-pad--actions").find("[data-action='clear']");
    objButtonClear.click();
}

/**
 * guardar firma
 */
function fnGuardarCuadroFirma(){
    var objButtonClear = $(".signature-pad--actions").find("[data-action='save-server']");
    objButtonClear.click();
    setTimeout("window.location = strUrl",600);

}

/**
 * Genera pdf de registro
 */
function generatePdfRegistro(numIdRegistro){
    var code64 = getBase64ImageCode();
    var win = window.open('/registro/print-register?id='+numIdRegistro+'&code='+code64, '_blank');
    if (win) {
        //Browser has allowed it to be opened
        win.focus();
    }
}

/**
 * Filtra los resultados y lo devuelve con la construcción esperada por el select2
 * @param data
 * @returns {{results: *}}
 */
function procesarResultadosComercial(data) {
    return {
        results: data.map(d => {
            return {id: d.id, nombre: d.nombre };
        })
    }
}

/**
 * Filtra los resultados y lo devuelve con la construcción esperada por el select2
 * @param data
 * @returns {{results: *}}
 */
function procesarResultadosResponsable(data) {

    return {
        results: data.map(d => {
            let strNombre = d.nombre_responsable+' ';
            if(d.documento.length){
                strNombre += ';  [Id.: '+d.documento+']';
            }
            if(d.telefono.length){
                strNombre += '- [tel: '+d.telefono+']';
            }

            return {id: d.nombre_responsable, nombre: d.nombre_responsable, tipo_documento: d.tipo_documento, documento: d.documento, telefono: d.telefono, responsable:strNombre  };
        })
    }
}

/**
 * setear campos en formulario del responsable.
 * Función ejecutada en el autocompletar del responsable
 * @param data
 */
function fnSelectionResponsable(data){
    //Limpiar campos
    $('#registro-tipo_documento').val(0);
    $('#registro-documento').val('');
    $('#registro-telefono').val('');
    // asignar datos
    if(data.selected == true){
        if(typeof data.tipo_documento !== 'undefined' && data.tipo_documento!= null && data.tipo_documento.length>=1){
            $('#registro-tipo_documento').val(parseInt(data.tipo_documento));
        }
        if(typeof data.documento !== 'undefined' && data.documento!= null && data.documento.length>1){
            $('#registro-documento').val(data.documento);
        }
        if(typeof data.telefono !== 'undefined' && data.telefono!= null && data.telefono.length>1){
            $('#registro-telefono').val(data.telefono);
        }
    }
}

/**
 * Copia los datos de contacto del comercial en el formulario
 * @param nombreContacto
 * @param numTelefono
 * @param idTipoDocumento
 * @param numDocumento
 * @returns {boolean}
 */
function setCopyDataContacto(){

    let numIdResponsable = $('#id_comercial').val();
    let url = strAjaxFindComercial;//'../registro/ajax-find-comercial';
    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "numIdResponsable": numIdResponsable
        },
        success: function (data) {
            data = data[0];
            $('#registro-nombre_responsable').val(data.contacto);
            $('#registro-telefono').val(data.telefono);
            $('#registro-tipo_documento').val(data.id_tipo_documento);
            $('#registro-documento').val(data.documento);
        }
    });

    return true;
}

/**
 * Tratamos la respuesta en caso de error al subir el fichero
 * @param mensaje
 */
function tratarRespuestaError(mensaje) {
    $('[data-js-resultado-carga-fichero-error]').html(mensaje.error ? mensaje.error : 'Se ha generado un error, contacta con un administrador.');
}

/**
 * Tratmaos la respuesta de exito al subir el fichero
 * @param mensaje
 */
function tratarRespuestaExito(mensaje) {
    const dom_fileinput = $('#fichero-facturas-rectificativas');
    const dom_aviso = $('[data-js-resultado-carga-fichero-aviso]');
    const dom_exito = $('[data-js-resultado-carga-fichero-exito]');
    const dom_error = $('[data-js-resultado-carga-fichero-error]');

    dom_aviso.html('');
    dom_error.hide();

    dom_exito.html(mensaje.respuesta);
    if (mensaje.avisos.length) {
        dom_aviso.html(mensaje.avisos.join('</br>'));
    }

    dom_fileinput.fileinput('disable');
    $('.btn-validar-fichero').attr('disabled', true);
    $('.btn-seleccionar-fichero').attr('disabled', true);
}

/**
 * Adicionar llaves manualmente al registros de movimientos
 */
function findManualKeys() {
    let url = strAjaxFindManual;//'../llave/ajax-find-manual';
    let formFindKays = $('#formFindKeys').serialize();
    let dom_resultado = $('#modal-llaves-contenido-table');

    dom_resultado.html('<i class="fa fa-spinner fa-spin"></i> Search...');
    dom_resultado.prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        data: formFindKays,
    })
        .always(() => $(this).html('Final...'))
        .done((res) => {
            dom_resultado.html(res);
        })
        .fail((res) => {
            toastr.warning('Algo no ha funcionado bien!');
            dom_resultado.html(res ? res.responseText : 'Algo no ha funcionado bien!')
        });

}

/**
 * Reset form de consulta manual
 */
function findManualKeysReset() {
    $('#formFindKeys').trigger("reset");
    $('#llavesearch-id_comunidad').val('').trigger("change");
    $('#llavesearch-id_propietario').val('').trigger("change");
    $('#llavesearch-comercial').val('').trigger("change");
}


function getBase64Image() {
    /*var element = $("#showBarcode"); // global variable
    var getCanvas; // global variable
    html2canvas(element, {
        onrendered: function (canvas) {
            $("#copyDiv").append(canvas);
            getCanvas = canvas;
            dataURL = canvas.toDataURL("image/png");
        }
    });*/
}

function getBase64ImageCode(){
    /*return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");*/
    return '';
}

/**
 * Eliminar registro
 */
function sendDeleteForm(){

    let numIdRegistro = $('#id_registro').val();

    $.ajax({
        url: strAjaxDeleteMotion,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "numIdRegistro": numIdRegistro,
        },
        success: function (data) {
            if(data.result == 'OK'){
                toastr.success('Registro eliminado correctamente.');
                setTimeout("window.location = strUrlList;",600);
            }else{
                if(data.mensaje != ''){
                    toastr.error(data.mensaje);
                }
                toastr.error('El registro no se puede eliminar, comunique al administrador.');
                return false;
            }
        }
    });
}

/**
 * sendUpdateForm
 */
function sendUpdateForm()
{
    let url = strAjaxUpdateMotion;
    var form = $('#form-registro');
    var formData = form.serialize();
    let numIdRegistro = $('#id_registro').val();

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: formData+"&numIdRegistro="+numIdRegistro+"&listKeyEntrada="+JSON.parse(JSON.stringify(listKeyEntrada))+"&listKeySalida="+JSON.parse(JSON.stringify(listKeySalida)),
        success: function (data) {
            listKeyEntrada.forEach(function(key, index, object) {
                object.splice(index, 1);
                $('#tr_'+index).remove();
            });
            listKeySalida.forEach(function(key, index, object) {
                object.splice(index, 1);
                $('#tr_'+index).remove();
            });

            toastr.success('Registro Actualizado correctamente.');
            $('#div_msm').show("slow");
            $('#div_info').hide();

            $('#txt_observacion').val('');
            $('#id_llave').attr('readonly', true);
            $('#btn_registrar').attr('readonly', true);

            if (!signaturePad.isEmpty()) {
                fnGuardarCuadroFirma();
            }else {
                setTimeout("window.location = strUrl;",600);
            }
        }
    });
}
