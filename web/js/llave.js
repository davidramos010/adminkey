/**
 * Captura las propiedades del tipo de llave
 */
function fnTipoLlaveSelected(){
    let idTipoSelect = $('#llave-id_tipo').val();
    let url = '/index.php?r=llave/ajax-find-attributes';
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        data: {
            "numIdTipoLlave": idTipoSelect,
        },
        success: function (data) {
            if(data.propietario == 1){
                $('#divFormPropietario').show(300,'');
            }else{
                $('#divFormPropietario').hide(300,'');
                $('#id_propietario').val(null);
            }

            if(data.comunidad == 1){
                $('#divFormComunidad').show(300,'');
            }else{
                $('#divFormComunidad').hide(300,'');
                $('#llave-id_comunidad').val(null);
            }
        }
    });
}

/**
 * Consultar detalles de movimientos
 * @param id
 */
function getInfoLlaveCard(id){

    let url = '/index.php?r=llave/ajax-find-status';
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            "numIdLlave": id,
        },
        success: function (data) {
            $('#modal-email-contenido-table').html(data);
        }
    });
}

/**
 * Buscar el codigo/nomenclatura de la comunidad
 */
function findCodeLlave()
{
    let url = '/index.php?r=llave/ajax-find-code';
    let comunidad = $('#llave-id_comunidad').val();
    let propietario = $('#id_propietario').val();

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "comunidad": comunidad,
            "propietario": propietario,
        },
        success: function (data) {
            $('#llave-codigo').val(data.id);
            $('#llave-nomenclatura').val(data.nomenclatura);
        }
    });
}

/**
 * Impresion de etiqueta
 */
function printDiv()
{
    var divToPrint=document.getElementById('showTableBarcode');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.close();},10);

}

/**
 * Impresión del modal con los movimientos
 */
function printDivHistorialMovimientos()
{
    var divToPrint=document.getElementById('modal-default');
    var newWin=window.open('','Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    newWin.document.close();
    setTimeout(function(){newWin.close();},10);

}

/**
 * Exportar documento
 * @returns {*}
 */
function fnExcelReport(strName)
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById(strName); // id of table

    for(j = 0 ; j < tab.rows.length ; j++)
    {
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();
        sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

    return (sa);
}

/**
 * registrar comunidad en el formulario del modal
 */
function fnSetComunidad(){

    let url = '/index.php?r=comunidad%2Fajax-create';
    var form = $('#form-comunidad');
    var formData = form.serialize();

    setTimeout(function () {
        if ($('[aria-invalid="true"]').length) {
            toastr.error('Atención hay algún campo incorrecto o por llenar. <br /> ' +
                '<span class="small">Sí no detectas el error, mira que no haya espacios en blanco delante o detrás del campo erróneo. </span> ');
            return false;
        }else{
            $.ajax({
                url: url,
                dataType: 'JSON',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if(data.error != '' && data.error!=null) {
                        toastr.error(data.error);
                    }else {
                        toastr.success(data.ok_sms);
                        $('#llave-id_comunidad').append($('<option>', {
                            value: data.ok,
                            text: data.name,
                            selected : true
                        }));
                        $('#llave-codigo').val(data.ok);
                        $('#llave-nomenclatura').val(data.nomenclatura);
                        $('#btn_cancelar_modal_comunidad').click();
                    }
                },
                error: function () {
                    toastr.error("Something went wrong");
                }
            });
        }
    }, 350);
}

/**
 * registrar Propietario en el formulario del modal
 */
function fnSetPropietario(){
    let url = '/index.php?r=propietarios%2Fajax-create';
    var form = $('#form-propietario');
    var formData = form.serialize();

    setTimeout(function () {
        if ($('[aria-invalid="true"]').length) {
            toastr.error('Atención hay algún campo incorrecto o por llenar. <br /> ' +
                '<span class="small">Sí no detectas el error, mira que no haya espacios en blanco delante o detrás del campo erróneo. </span> ');
            return false;
        }else{
            $.ajax({
                url: url,
                dataType: 'JSON',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if(data.error != '' && data.error!=null) {
                        toastr.error(data.error);
                    }else {
                        toastr.success(data.ok_sms);
                        $('#id_propietario').append($('<option>', {
                            value: data.ok,
                            text: data.name,
                            selected : true
                        }));
                        $('#id_propietario').val(data.ok);
                        $('#btn_cancelar_modal_propietarios').click();
                    }
                },
                error: function () {
                    toastr.error("Something went wrong. Propietarios");
                }
            });
        }
    }, 350);
}

