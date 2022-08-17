
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

function printDiv()
{
    var divToPrint=document.getElementById('showTableBarcode');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.close();},10);

}