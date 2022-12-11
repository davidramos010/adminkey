
function fnVerAdmin(){
    $('#divLoginUser').hide();
    $('#divLoginAdmin').fadeIn('slow');
    $('#loginform-authkey').val('');
    $('#loginform-perfil').val(1);
}

function fnVerUser(){
    $('#divLoginAdmin').hide();
    $('#divLoginUser').fadeIn('slow');
    $('#loginform-username').val('');
    $('#loginform-password').val('');
    $('#loginform-perfil').val(2);
}

function fnAddNumerLogin(number){
    var strCadena = $('#authkey').val();
    if( $.isNumeric( number ) ){
        $('#authkey').val( strCadena+number);
        return true;
    }

    if( number == '-' ){
        $('#authkey').val( strCadena.slice(0, -1) );
        return true;
    }

    if( number == '*' ){
        $('#authkey').val('');
        return true;
    }

}