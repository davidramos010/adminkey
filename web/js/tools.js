/**
 * Validación de tipo de documento
 * Returns: 1 = NIF ok, 2 = CIF ok, 3 = NIE ok, -1 = NIF bad, -2 = CIF bad, -3 = NIE bad, 0 = ??? bad
 * @param cif
 * @returns {number}
 */
function validate_doc( cif ) {

    num = new Array();
    cif = cif.toUpperCase();
    for (i = 0; i < 9; i ++) {
        num[i] = cif.substr(i, 1);
    }
    //si no tiene un formato valido devuelve error
    if (!cif.match('((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)')) {
        return 0;
    }
    //comprobacion de NIFs estandar
    if (cif.match('(^[0-9]{8}[A-Z]{1}$)')){
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr(cif.substr(0, 8) % 23, 1)){
            return 1;
        } else {
            return -1;
        }
    }
    //algoritmo para comprobacion de codigos tipo CIF
    suma = num[2] + num[4] + num[6];
    for (i = 1; i < 8; i += 2) {
        suma += toString((2 * num[i])).substr(0,1) + toString((2 * num[i])).substr(1,1);
    }
    n = 10 - suma.substr( suma.length - 1, 1);
    //comprobacion de NIFs especiales (se calculan como CIFs)
    if (cif.match('^[KLM]{1}')) {
        if (num[8] == String.fromCharCode(64 + n)){
            return 1;
        } else {
            return -1;
        }
    }
    //comprobacion de CIFs
    if (cif.match('^[ABCDEFGHJNPQRSUVW]{1}')) {
        if (num[8] == String.fromCharCode(64 + n) || num[8] == n.substr(n.length - 1, 1)) {
            return 2;
        } else {
            return -2;
        }
    }
    //comprobacion de NIEs
    //T
    if (cif.match('^[T]{1}')) {
        if (num[8] == cif.match('^[T]{1}[A-Z0-9]{8}$')) {
            return 3;
        } else {
            return -3;
        }
    }
    //XYZ
    if (cif.match('^[XYZ]{1}')) {
        tmpstr = cif.replace('X', '0');
        tmpstr = tmpstr.replace('Y', '1');
        tmpstr = tmpstr.replace('Z', '2');
        if (num[8] == 'TRWAGMYFPDXBNJZSQVHLCKE'.substr( tmpstr.substr(0, 8) % 23, 1)) {
            return 3;
        } else {
            return -3;
        }
    }
    //si todavia no se ha verificado devuelve error
    return 0;

}