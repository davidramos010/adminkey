
function findCode()
{
    let url = '/index.php?r=llave/ajax-find-code';
    let comunidad = $('#llave-id_comunidad').val();

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "comunidad": comunidad,
        },

        success: function (data) {
            $('#llave-codigo').val(data);
        }
    });
}

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

    if($('#id').val()=='' && $("#authKey_new").val()==''){
        toastr.error('El authKey no es valido.');
        $('#authKey_new').focus();
        bolError=true;
    }

    if($('#id').val()=='' && $("#password_new").val()==''){
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