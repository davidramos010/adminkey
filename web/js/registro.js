var listKeyEntrada = [];
var listKeySalida = [];

/**
 *
 */
function addKey()
{
    let url = '/index.php?r=registro/ajax-add-key';
    let code = $('#id_llave').val();
    let operacion = $('#id_operacion').val();
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
            console.log(data);
            //Validar que no exista
            var bolInserRow = true;

            if(data.llave==null){
                toastr.error('El codigo ingresado no es valido o esta deshabilitado.');
                return true;
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
                        '<td>'+data.comunidad.nombre+'</td>\n' +
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
 * Envio de formulario
 */
function sendForm()
{   //https://github.com/inquid/yii2-signature/blob/master/assets/app.js
    let url = '/index.php?r=registro/ajax-reg-mov';
    let observacion = $('#txt_observacion').val();
    let comercial = $('#id_comercial').val();
    let numIdRegistro = null;

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
            console.log('----------------'+data);
            //console.log('----------------'.data.registro_id);
            numIdRegistro = 9;//data.registro_id;
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
                fnGuardarCuadroFirma(numIdRegistro);
            }else {
                toastr.warning('El registro no incluyye firma digital.');
                setTimeout("location.reload(true);",600);
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
function fnGuardarCuadroFirma(numIdRegistro){
    var objButtonClear = $(".signature-pad--actions").find("[data-action='save-server']");
    objButtonClear.click();
    setTimeout("location.reload(true);",600);
}

/**
 * Genera pdf de registro
 */
function generatePdfRegistro(numIdRegistro){
    var win = window.open('/index.php?r=registro/report&id='+numIdRegistro, '_blank');
    if (win) {
        //Browser has allowed it to be opened
        win.focus();
    }
}
