/**
 * Habilita/Muestra campos en pantalla
 */
function fnDisplayDivInput(){
    let numTipoUser = $('#idPerfil').val();

    $('#password_new').val('');
    $('#authKey_new').val('');

    if(numTipoUser==1){
        $('#div_input_authKey_new').hide();
        $('#div_input_password_new').show(300,'');
    }

    if(numTipoUser==2){
        $('#div_input_password_new').hide();
        $('#div_input_authKey_new').show(300,'');
    }
}

/**
 * Generar el nombre de usuario
 */
function getNameUserGenerate(){
    let strFName = $('#nombres').val();
    let strSName = $('#apellidos').val();
    let strUserName = '';
    if(strFName!='' && strSName!=''){
        strUserName = strFName.slice(0,1)+''+strSName;
    }
    $('#username').val(strUserName);
}

function validateDocumento(){
 let numTipoDoc = $('#userinfo-tipo_documento').val();
 let strDocumento = $('#userinfo-documento').val();
 let bolReturn = false;
    if(numTipoDoc<=3){
        let numValidate = validate_doc( strDocumento );
        if (parseInt(numValidate)>0){
            bolReturn = false;
        }else{
            bolReturn = true;
            $('#userinfo-documento').val('');
            toastr.error('El formato del documento no es valido.');
        }
    }
    return bolReturn;
}

/**
 * validar que el codigo no exista
 */
function valideteKey()
{
    let url = '../ajax-validate-key';
    let authKey = $('#authKey_new').val();
    bolError = false;

    if($('#authKey_new').val()!='' && $('#authKey_new').val().length<6 ) {
        toastr.error('El AuthKey bebe tener mas de 6 caracteres.');
        $('#authKey_new').focus();
        bolError=true;
    }

    if(bolError==false){
        $.ajax({
            url: url,
            dataType: 'JSON',
            type: 'POST',
            data: {
                "authKey": authKey,
            },
            success: function (data) {
                if(data.authkey=='true' || data.authkey==true){
                    toastr.error('El authKey ya esta en uso por otro usuario, este registro debe ser unico.');
                    $('#authKey_new').focus();
                    $('#authKey_new').val('');
                }
            }
        });
    }

}


/**
 * Filtra los codigos postales y lo devuelve con la construcción esperada por el select2
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
 * Envio de formulario
 * @returns {boolean}
 */
function fnSubmit()
{
    var bolError = false;
    //Validar nombreusuario
    if($('#username').val()==''){
        toastr.error('El username no puede estar vacio.');
        $('#username').focus();
        bolError=true;
    }

    if($('#nombres').val()==''){
        toastr.error('El nombre no puede estar vacio.');
        $('#nombres').focus();
        bolError=true;
    }
    if($('#apellidos').val()==''){
        toastr.error('El apellido no puede estar vacio.');
        $('#apellidos').focus();
        bolError=true;
    }

    if($('#email').val()!='' ){
        if($("#email").val().indexOf('@', 0) == -1 || $("#email").val().indexOf('.', 0) == -1) {
            toastr.error('El email no es valido.');
            $('#email').focus();
            bolError=true;
        }
    }

    if($('#id').val()=='' && $("#authKey_new").val()=='' && $("#idPerfil").val()==2){
        toastr.error('El authKey no es valido.');
        $('#authKey_new').focus();
        bolError=true;
    }

    if($('#id').val()=='' && $("#password_new").val()=='' && $("#idPerfil").val()==1){
        toastr.error('El password no es valido.');
        $('#password_new').focus();
        bolError=true;
    }

    if($('#id').val()==''){
        $('#password').val($('#password_new').val());
        $('#authKey').val($('#authKey_new').val());
    }

    if($('#idPerfil').val()==''){
        toastr.error('El Perfil no puede estar vacio.');
        $('#idPerfil').focus();
        bolError=true;
    }

    if(bolError==true){
        return false;
    }

    $('#formUser').submit();
}