
/**
 *  Al seleccionar un CP se llenan los campos localidad y provincia
 * @param params
 */
function popularLocalidadProvincia(params) {
    if (params && params.data) {
        //$("#altasin-dir_poblac").val(params.data.localidad);
        $("#propietarios-poblacion").val(params.data.poblacio+ ', '+params.data.provincia);
    }
}

/**
 * Filtra los codigos postales y lo devuelve con la construcción esperada por el select2
 * @param data
 * @returns {{results: *}}
 */

function procesarResultadosLlave(data) {
    //console.log(data);
    return {
        results: data.map(d => {
            return {id: d.id, codigo: d.codigo};
        })
    }
}