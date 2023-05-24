
/**
 *  Al seleccionar un CP se llenan los campos localidad y provincia
 * @param params
 */
function fnLocalidadProvincia(params) {
    if (params && params.data) {
        $("#comunidad-poblacion").val(params.data.poblacio+ ', '+params.data.provincia);
    }
}

/**
 * Filtra los codigos postales y lo devuelve con la construcción esperada por el select2
 * @param data
 * @returns {{results: *}}
 */

function procesarResultadosCodigoPostal(data) {
    return {
        results: data.map(d => {
            return {id: d.cp, text: d.cp, poblacio: d.poblacio, provincia: d.provincia};
        })
    }
}