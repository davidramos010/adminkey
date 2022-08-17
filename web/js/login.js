
function sendFormKey(){

    $( "#login-form" ).submit();
}

function fnVerAdmin(){
    $('#divLoginUser').hide();
    $('#divLoginAdmin').fadeIn('slow');

}


function fnVerUser(){
    $('#divLoginAdmin').hide();
    $('#divLoginUser').fadeIn('slow');
}