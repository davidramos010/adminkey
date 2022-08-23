
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
