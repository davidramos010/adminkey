
/**
 * Captura las propiedades del tipo de llave
 */
function fnTipoLlaveSelected() {
    let idTipoSelect = $('#llave-id_tipo').val();
    let url = strUrlFindAttributes;
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        data: {
            "numIdTipoLlave": idTipoSelect,
        },
        success: function (data) {
            if (data.propietario == 1) {
                $('#divFormPropietario').show(300, '');
            } else {
                $('#divFormPropietario').hide(300, '');
                $('#id_propietario').val(null);
            }

            if (data.comunidad == 1) {
                $('#divFormComunidad').show(300, '');
            } else {
                $('#divFormComunidad').hide(300, '');
                $('#llave-id_comunidad').val(null);
            }
        }
    });
}

/**
 * Consultar detalles de movimientos
 * @param id
 */
function getInfoLlaveCard(id) {

    let url = '../llave/ajax-find-status';
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
 * Crear la copia de una llave
 * @param id
 */
function addCopiKeys(id) {

    let url = strUrlAddCopiKey;
    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "numIdLlave": id,
        },
        success: function (data) {
            if (data.error == false || data.error == 'false') {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

/**
 * Buscar el codigo/nomenclatura de la comunidad
 */
function findCodeLlave() {
    let url = strUrlFindCode;
    let tipo = $('#llave-id_tipo').val();
    let comunidad = $('#llave-id_comunidad').val();
    let propietario = $('#id_propietario').val();

    $.ajax({
        url: url,
        dataType: 'JSON',
        type: 'POST',
        data: {
            "tipo": tipo,
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
function printDiv() {
    var divToPrint = document.getElementById('showTableBarcode');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    setTimeout(function () {
        newWin.close();
    }, 10);

}

/**
 * Impresión del modal con los movimientos
 */
function printDivHistorialMovimientos() {
    var divToPrint = document.getElementById('modal-default');
    var newWin = window.open('', 'Print-Window');
    newWin.document.open();
    newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
    newWin.document.close();
    setTimeout(function () {
        newWin.close();
    }, 10);

}

/**
 * Exportar documento
 * @returns {*}
 */
function fnExcelReport(strName) {
    var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange;
    var j = 0;
    tab = document.getElementById(strName); // id of table

    for (j = 0; j < tab.rows.length; j++) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text = tab_text + "</table>";
    tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html", "replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();
        sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
    } else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));

    return (sa);
}

/**
 * Registrar nota en la llave
 * @param idllave
 */
function fnSetNotaLlave() {
    let url = strUrlCreateNotas;
    let strNotaLlave = $('#form-nota-nota').val().trim();
    let idllave = $('#form-nota-llave').val();


    setTimeout(function () {
        if (strNotaLlave.length === 0) {
            toastr.error('Atención el campo nota es incorrecto o falta por llenar. <br /> ' +
                '<span class="small">Sí no detectas el error, mira que no haya espacios en blanco delante o detrás del campo erróneo. </span> ');
            return false;
        } else {
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    "numIdLlave": idllave,
                    "strNota": strNotaLlave
                },
                success: function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.error != '' && data.error != null) {
                        toastr.error(data.error);
                    } else {
                        toastr.success(data.ok_sms);
                        let strButton = '<button type="button" class="btn btn-danger btn-xs" onclick="fnDelNotaLlave(' + data.id + ') "><svg class="svg-inline--fa fa-trash-alt fa-w-14" aria-hidden="true" data-prefix="fas" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm416 56v324c0 26.5-21.5 48-48 48H80c-26.5 0-48-21.5-48-48V140c0-6.6 5.4-12 12-12h360c6.6 0 12 5.4 12 12zm-272 68c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208z"></path></svg></button>';
                        $('#tblNotasList').prepend('<tr id="tableNotaRow_' + data.id + '" ><td>' + data.nota + '</td><td>' + data.fecha + '</td><td>' + data.usuario + '</td><td>' + strButton + '</td></tr>');
                        $('#btn_cancelar_modal_notas').click();
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
 *  Eliminar nota de la llave
 * @param idllave
 */
function fnDelNotaLlave(idllave) {

    var opcion = confirm("Esta seguro que desea eliminar la nota?");
    if (opcion != true) {
        return true;
    }

    $.ajax({
        url: strUrlDeleteNotas,
        type: 'POST',
        data: {
            "numIdLlave": idllave,
        },
        success: function (data) {
            if (data == false || data == 'false') {
                toastr.error('El registro no se pudo eliminar. comuniqie al administrador');
            } else {
                toastr.success('La nota se ha eliminado');
                $('#tableNotaRow_' + idllave).closest("tr").remove();
            }
        }
    });
}

/**
 * registrar comunidad en el formulario del modal
 */
function fnSetComunidad() {

    let url = strUrlCreateComunidad;
    var form = $('#form-comunidad');
    var formData = form.serialize();

    setTimeout(function () {
        if ($('[aria-invalid="true"]').length) {
            toastr.error('Atención hay algún campo incorrecto o por llenar. <br /> ' +
                '<span class="small">Sí no detectas el error, mira que no haya espacios en blanco delante o detrás del campo erróneo. </span> ');
            return false;
        } else {
            $.ajax({
                url: url,
                dataType: 'JSON',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.error != '' && data.error != null) {
                        toastr.error(data.error);
                    } else {
                        toastr.success(data.ok_sms);
                        $('#llave-id_comunidad').append($('<option>', {
                            value: data.ok,
                            text: data.name,
                            selected: true
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
function fnSetPropietario() {
    let url = strUrlCreatePropietarios;
    var form = $('#form-propietario');
    var formData = form.serialize();

    setTimeout(function () {
        if ($('[aria-invalid="true"]').length) {
            toastr.error('Atención hay algún campo incorrecto o por llenar. <br /> ' +
                '<span class="small">Sí no detectas el error, mira que no haya espacios en blanco delante o detrás del campo erróneo. </span> ');
            return false;
        } else {
            $.ajax({
                url: url,
                dataType: 'JSON',
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data.error != '' && data.error != null) {
                        toastr.error(data.error);
                    } else {
                        toastr.success(data.ok_sms);
                        $('#id_propietario').append($('<option>', {
                            value: data.ok,
                            text: data.name,
                            selected: true
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

