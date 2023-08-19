<?php


namespace app\components;


use app\components\robinson\Robinson;
use app\modules\campanas\controllers\CampanasController;
use PhpOffice\PhpSpreadsheet\Calculation\Exception;
use Yii;

/**
 * Class ValidadorCsv
 * Utilizado para validar contenidos de un CSV
 * @date 2019-11-25
 * @author Josep Vidal
 * @package app\components
 */
class ValidadorCsv
{
    /**
     * Hay dos tipos de errores, de usuario, es decir de CSV y de desarollador
     * @var array 
     */
    public $errores = [self::ERROR_USER => [], self::ERROR_DEV => []];

    /**
     * Las cabeceras esperadas del CSV a validar con sus reglas de validacion
     * @var array 
     */
    public $cabeceras = [];

    /**
     * Las cabeceras reales del fichero una vez cargado
     * @var array
     */
    public $cabecerasFichero = [];

    /**
     * Fichero csv abierto
     * @var false|resource
     */
    private $ficheroAbierto;

    /**
     * Numero de fila
     * @var int
     */
    private $fila = 1;
    /**
     * Tipo de campana cargada por CSV (Captacion / Concertacion)
     * Puede ser nulo para validar CSV distintos
     * @var string
     */
    protected $tipo;
    /**
     * Ruta al fichero CSV
     * @var string
     */
    protected $ruta;

    /**
     * Se almacen las hashtables para campos donde se requiere checkear si el value existe en la tabla
     * Ejemplo el id de tarifa, ir a la tabla de tarifas a ver si la tarifa facilitada es valida
     * Se guarda como [ '$table-$field' => [...] ]
     */
    private $hashTables = [];
    /**
     * Utilizado para validar que un registro para una columna sea unico en el CSV
     * @var array
     */
    private $uniqueTable = [];

    /**
     * Tipos de mensajes de error
     */
    const ERROR_USER = 'usuario';
    const ERROR_DEV = 'developer';
    /**
     * Tipos de normas y constantes de normas
     * La norma RULE_CAN_BE_NULL és OBLIGATORIA en todas las cabeceras
     */
    // Puede estar vacio o no
    const RULE_CAN_BE_NULL = 'mandatory_rule_can_be_null';
    // Maxima largaria del contenido
    const RULE_LENGTH = 'rule_length';
    // Minima largaria del contenido
    const RULE_MIN_LENGTH = 'rule_min_length';
    // Limita por tipo de dato
    const RULE_TYPE = 'rule_type';
    // Limita los decimales a X
    const RULE_MAX_DECIMALS = 'rule_max_decimals';
    // Prohibe el caracter especificado
    const RULE_FORBID = 'rule_forbid';
    // Se le tiene que pasar un array, limita el contenido a los valores pasados en el array
    const RULE_LIMITED_TO = 'rule_limited_to';
    // Comprueba que el valor de la row no este repetido en toda la columna
    const RULE_UNIQUE = 'rule_unique';
    // Comprueba si el telfono facilitado esta en robinson
    const RULE_ROBINSON = 'rule_robinson';
    /*
     * Norma que aplica OTRAS normas si el contenido cumple con los valores pasados
     * Ejemplo, imaginemos que tenemos una columna X que puede ser 1-0, y en función de eso otra columna Y puede ser NULL o no
     * Se tiene que pasar un array con los siguientes valores: [ 'cabecera' => 'ejemplo_cabecera', 'values' => ['ejemplo_valor1'], 'rules' => [ self::RULE_CAN_BE_NULL => false ] ]
     */
    const RULE_THAT_DEPENDS_ON = 'rule_that_depends_on';
    /**
     * Valida si el valor de este campo existe en la tabla especificada mediante un tabla hasheada,
     * Ejemplo queremos saber si la tarifa con id 5 proporcionada en el CSV existe en nuesta base de datos, generamos un hastable de la tabla tarifa del campo referenciado
     * Se ha de pasar un array con los siguientes campos [ 'table' => 'ejemplo', 'field' => 'ejemplo_id' };
     */
    const RULE_VALUE_EXIST_IN_DB = 'rule_value_exist_in_db';
    // Tipos de contenido utilizado en el validador de tipo
    const RULE_TYPE_INT = 'type_int';
    const RULE_TYPE_FLOAT = 'type_float';
    const RULE_TYPE_STRING = 'type_string';
    const RULE_TYPE_DATE = 'type_date (d/m/Y)';
    const RULE_TYPE_EMAIL = 'type_email';
    /**
     * Que el valor sea mayor que que 0 por ejemploe
     * self::RULE_BIGGER_THAN => 0
     */
    CONST RULE_BIGGER_THAN = 'rule_bigger_than';

    /**
     * @param $ruta
     * @param bool $tipo
     * @throws Exception
     */
    public function __construct($ruta, $tipo = false)
    {
        $this->tipo = $tipo;
        $this->ruta = $ruta;

        $this->ficheroAbierto = fopen($this->ruta, 'r');

        if (!$this->ficheroAbierto) {
            throw new Exception('No ha sido posible abrir el fichero', 500);
        }
    }

    /**
     * Cerramos el puntero al fichero
     */
    public function finalizarValidacion()
    {
        fclose($this->ficheroAbierto);
    }

    /**
     * Es posible pasar unas cabeceras, sino, funciona sobre el TIPO inicializado en el CSV
     *
     * @info Para ver o extender el formato a pasar como cabeceras mirar las funciones de getCabecerasCaptacion
     * @param array $cabeceras
     */
    public function validarCabeceras($cabeceras = [])
    {
        $this->cabeceras = !count($cabeceras) ? $this->getCabecerasSegunTipo() : $cabeceras;


        while (($data = fgetcsv($this->ficheroAbierto, 1000, ";")) !== false) {

            $this->cabecerasFichero = array_map('trim', $data);

            foreach ($this->cabeceras as $nombre => $cabecera) {
                if (!in_array($nombre, $this->cabecerasFichero)) {
                    $this->setError("La cabecera $nombre no pudo ser encontrada en el fichero CSV facilitado.",
                        self::ERROR_USER);
                }
            }
            $this->fila++;
            break;
        }
    }

    /**
     * Validamos el contenido según las cabeceras especificadas en la funcion de validarCabeceras
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function validarContenido()
    {
        if (count($this->cabecerasFichero)) {
            if (count($this->getErrors(self::ERROR_USER))) {
                $this->setError("No puedes validar el contenido si tienes errores de validación en las cabeceras!",
                    self::ERROR_USER);
            } else {

                while (($data = fgetcsv($this->ficheroAbierto, 10000, ";")) !== false) {
                    if (count($data) !== count($this->cabecerasFichero)) {
                        $this->setError("El numero de cabeceras no coincide con el numero de columnas. ¿Quizás tengas algun ; en el fichero?",
                            self::ERROR_USER);
                    }

                    $campos = array_map('trim', $data);
                    foreach ($campos as $i => $campo) {
                        $this->checkRules($this->cabecerasFichero[$i], utf8_encode($campo), $this->cabecerasFichero,
                            $campos);
                    }
                    $this->fila++;
                }
                $this->fila = 1;
            }
        } else {
            throw new Exception('No puedes validar el contenido sin haber validado antes las cabeceras ( $this->validarCabeceras() ) !',
                400);
        }
    }


    /**
     * Funcion encargada de validar el contenido de cada campo
     * Se ha de passar el string equivalente a la cabecera, y el contenido del campo
     *
     * En cabeceras y contenidos tienes disponibles todas las cabeceras y todos los contenidos de la row
     *
     * Es possible pasarle unas rules para hacer la funcion recursiva y poder llamarse desde si misma con un subarray de rules que dependen de otras rules
     *
     * @param $cabecera
     * @param $contenido
     * @param $cabeceras
     * @param $contenidos
     * @param bool $rules = false
     * @throws Exception
     * @throws \yii\base\Exception
     * @throws \Exception
     */
    private function checkRules($cabecera, $contenido, $cabeceras, $contenidos, $rules = false)
    {
        if (!$rules) {
            // Este if se ejecuta siempre y cuando no sea llamado de forma recursiva desde dentro de la misma funcion
            $rules = isset($this->cabeceras[$cabecera]) ? $this->cabeceras[$cabecera] : [];

            if (!$rules) {
                $this->setError("No hay normas de validación para la columna $cabecera.", self::ERROR_DEV);
                return;
            }

            if ($rules && !isset($rules[self::RULE_CAN_BE_NULL])) {
                throw new \yii\base\Exception(500,
                    'La norma de validación self::RULE_CAN_BE_NULL es obligatoria en todas las columnas de la cabecera.');
            }
        }

        /**
         * En este if magico y ubercomplicado para saltarnos la validación si un elemento puede ser null:
         * 1 -> Comprobamos que el contenido este vacio y pueda ser null
         * 2 -> Comprobamos que la cabecera tenga subnormas sino, ya podemos hacer break
         * 3 -> Si tiene subnormas comprobamos que en estas subnormas tenga la de poder ser null y SI SI puede ser null no validamos nada mas
         */
        if (empty($contenido) && $rules[self::RULE_CAN_BE_NULL] === true && !isset($rules[self::RULE_THAT_DEPENDS_ON])) {
            return;
        }

        foreach ($rules as $nombre => $norma) {

            switch ($nombre) {

                case self::RULE_CAN_BE_NULL:
                    if ($norma === false) {
                        if (empty($contenido) && $contenido !== "0") {
                            $this->setValidationError($cabecera, "No puede estar vacío.");
                        }
                    }
                    break;

                case self::RULE_LENGTH:
                    $tamano = strlen($contenido);
                    if ($tamano > $norma) {
                        $this->setValidationError($cabecera,
                            "Tiene un tamaño más grande de lo permitido ($tamano) (Límite $norma carácteres).");
                    }
                    break;

                case self::RULE_MIN_LENGTH:
                    $tamano = strlen($contenido);
                    if ($tamano < $norma) {
                        $this->setValidationError($cabecera,
                            "Tiene un tamaño más pequeño de lo permitido ($tamano) (Mínimo $norma carácteres).");
                    }
                    break;

                case self::RULE_BIGGER_THAN:
                    if ((float)$contenido <= $norma) {
                        $this->setValidationError($cabecera,
                            "Tiene que tener un valor mayor de $norma valor especificado $contenido.");
                    }
                    break;

                case self::RULE_TYPE:
                    switch ($norma) {
                        case self::RULE_TYPE_INT:
                            if (!ctype_digit($contenido)) {
                                $this->setValidationError($cabecera, "No és un numero entero.");
                            }
                            break;

                        case self::RULE_TYPE_FLOAT:
                            if (!is_numeric($contenido)) {
                                $this->setValidationError($cabecera, "No és un numero.");
                            }
                            break;

                        case self::RULE_TYPE_STRING:
                            if (!is_string($contenido)) {
                                $this->setValidationError($cabecera, "No és texto.");
                            }
                            break;

                        case self::RULE_TYPE_DATE:
                            $date = \DateTime::createFromFormat('d/m/Y', $contenido);
                            if (!$date) {
                                $this->setValidationError($cabecera, "La fecha facilitada no és valida.");
                                break;
                            }
                            if ($date->format('d/m/Y') !== $contenido) {
                                $this->setValidationError($cabecera,
                                    "La fecha facilitada no tiene el formato correcto d/m/Y, ejemplo: 03/02/1994.");
                            }
                            break;

                        case self::RULE_TYPE_EMAIL:
                            if (!filter_var($contenido, FILTER_VALIDATE_EMAIL)) {
                                $this->setValidationError($cabecera,
                                    "El email facilitado no tiene el formato correcto!");
                            }
                            break;
                    }
                    break;

                case self::RULE_ROBINSON:
                    try {
                        if (Robinson::estaTelefonoEnRobinson($contenido)) {
                            $this->setValidationError($cabecera,
                                "El telefono ($contenido) esta en la lista Robinson!");
                        }
                    } catch (\Exception $e) {
                        // El telefono no tiene 9 caracteres
                    }
                    break;

                case self::RULE_FORBID:
                    if (strpos($contenido, $norma) !== false) {
                        $this->setValidationError($cabecera, "El carácter: $norma no esta permitido en este campo.");
                    }
                    break;

                case self::RULE_UNIQUE:
                    if (!isset($this->uniqueTable[$cabecera])) {
                        $this->uniqueTable[$cabecera] = [];
                    }
                    if (isset($this->uniqueTable[$cabecera][$contenido])) {
                        $this->setValidationError($cabecera,
                            "Valor repetido, en este campo todos los valores deben ser únicos.");
                    }
                    $this->uniqueTable[$cabecera][$contenido] = $contenido;
                    break;

                case self::RULE_MAX_DECIMALS:
                    if (strlen(substr(strrchr($contenido, "."), 1)) > $norma) {
                        $this->setValidationError($cabecera, "Supera el máximo de decimales permitidos: $norma.");
                    }
                    break;

                case self::RULE_LIMITED_TO:
                    if (in_array($contenido, $norma) === false) {
                        $this->setValidationError($cabecera,
                            "El valor especificado no coincide con ningúno de los admitidos: " . (join(',', $norma)));
                    }
                    break;

                case self::RULE_THAT_DEPENDS_ON:
                    if (!isset($norma['cabecera']) || !isset($norma['values']) || !isset($norma['rules'])) {
                        $this->setError('Los campos facilitados no coinciden con los que requiere el validador: (cabecera, valores i rules).',
                            self::ERROR_DEV);
                        break;
                    }

                    $indice = array_search($norma['cabecera'], $cabeceras);
                    if ($indice === false) {
                        $this->setError('La cabecera facilitada en la validación de la cabecera no coincide con ningúna del fichero.',
                            self::ERROR_DEV);
                        break;
                    }

                    foreach ($norma['values'] as $valor) {
                        if ($valor === $contenidos[$indice]) {
                            $this->checkRules($cabecera, $contenido, $cabeceras, $contenidos, $norma['rules']);
                        }
                    }
                    break;

                case self::RULE_VALUE_EXIST_IN_DB:

                    if (!isset($norma['table']) || !isset($norma['field'])) {
                        $this->setError('Los campos facilitados no coinciden con los que requiere el validador: (table, field).',
                            self::ERROR_DEV);
                        break;
                    }

                    $dbTable = $norma['table'];
                    $dbField = $norma['field'];

                    if (!isset($this->hashTables["$dbTable-$dbField"])) {
                        $this->generadorHashRelationalTable($dbTable, $dbField);
                    }

                    if (isset($this->hashTables["$dbTable-$dbField"])) {
                        if (!isset($this->hashTables["$dbTable-$dbField"][$contenido])) {
                            $this->setValidationError($cabecera,
                                "No existe ningun registro en la base de datos para este identificador facilitado.");
                        }
                    }
                    break;
            }
        }
    }

    /**
     * Devuelve un array asociativo del contenido SIN las cabeceras, por rows, y cabecera => valor
     * Permite especificar unas cabeceras.
     * @ATENCION Se recomienda ejecutar el validador de cabeceras y de contenido antes si se requieren los datos para cosultas INSERT/DELETE/UPDATE
     *
     * @ejemplo:
     * [
     *      0 =>
     *      [
     *          'cabecera1' => 'prueba1',
     *          'cabecera2' => 'abuerp1,
     *      ],
     *      1 =>
     *      [
     *          'cabecera1' => 'prueba2',
     *          'cabecera2' => 'abuerp2,
     *      ],
     *      ...
     * ]
     *
     * @return array
     */
    public function getRows()
    {
        $arrayFinal = [];
        // reseteamos la posición del puntero
        rewind($this->ficheroAbierto);

        while (($data = fgetcsv($this->ficheroAbierto, 1000, ";")) !== false) {

            if ($this->fila === 1) {
                $this->cabecerasFichero = array_map('trim', $data);
            } else {
                $arrayTemporal = [];
                foreach ($data as $key => $value) {
                    $valueEncoded = utf8_encode(trim($value));
                    $arrayTemporal[$this->cabecerasFichero[trim($key)]] = $valueEncoded === '' ? null : $valueEncoded;
                }

                array_push($arrayFinal, $arrayTemporal);
            }

            $this->fila++;
        }

        return $arrayFinal;
    }

    /**
     * Devuelve las cabeceras segun el tipo incializado en el objeto
     *
     * @return array
     */
    private function getCabecerasSegunTipo()
    {
        switch ($this->tipo) {
            case CampanasController::TIPO_CAPTACION:
                return $this->getCabecerasCaptacion();
            case CampanasController::TIPO_CONCERTACION:
                return $this->getCabecerasConcertacion();
            default:
                return [];
        }
    }

    /**
     * Devuelven las cabeceras respectivas a su tipo
     * El tipo puede ser int, string o float
     *
     * @return array
     */
    private function getCabecerasCaptacion()
    {
        return [
            'char_tlf' => [
                self::RULE_LENGTH => 15,
                self::RULE_CAN_BE_NULL => false,
                self::RULE_TYPE => self::RULE_TYPE_INT,
                self::RULE_UNIQUE => true,
            ],
            'char_tlf2' => [
                self::RULE_LENGTH => 15,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_INT,
                self::RULE_UNIQUE => true,
            ],
            'str_direccion' => [
                self::RULE_LENGTH => 255,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'char_cp' => [
                self::RULE_LENGTH => 5,
                self::RULE_MIN_LENGTH => 5,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_INT
            ],
            'str_poblacion' => [
                self::RULE_LENGTH => 255,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'str_provincia' => [
                self::RULE_LENGTH => 255,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'char_cif' => [
                self::RULE_LENGTH => 9,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'char_cups' => [
                self::RULE_LENGTH => 20,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'str_nombre' => [
                self::RULE_LENGTH => 257,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'str_mail' => [
                self::RULE_LENGTH => 255,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'str_comercializadora' => [
                self::RULE_LENGTH => 3,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'str_tarifa' => [
                self::RULE_LENGTH => 10,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_VALUE_EXIST_IN_DB => ['table' => 'tarifa', 'field' => 'ID']
            ],
            'comentario' => [
                self::RULE_LENGTH => 200,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING
            ],
            'idioma' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_LIMITED_TO => ['C', 'E'],

            ]
        ];
    }

    private function getCabecerasConcertacion()
    {
        return [
            'cups' => [
                self::RULE_LENGTH => 20,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => true,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP', 'SOP'],
                    'rules' => [self::RULE_CAN_BE_NULL => false]
                ]
            ],
            'titular' => [
                self::RULE_LENGTH => 250,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => false
            ],
            'telefono' => [
                self::RULE_LENGTH => 10,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => false,
            ],
            'direccion_completa_ps' => [
                self::RULE_LENGTH => 250,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => false
            ],
            'localidad_suministro' => [
                self::RULE_LENGTH => 50,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => false
            ],
            'cp_suministro' => [
                self::RULE_LENGTH => 5,
                self::RULE_MIN_LENGTH => 5,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => false
            ],
            'provincia_suministro' => [
                self::RULE_LENGTH => 250,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => true,
            ],
            'tarifa' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_VALUE_EXIST_IN_DB => ['table' => 'tarifa', 'field' => 'ID'],
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP', 'AUT'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 5,
                        self::RULE_TYPE => self::RULE_TYPE_INT,
                    ]
                ]
            ],
            'Pot_Contr_P1' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'Pot_Contr_P2' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'Pot_Contr_P3' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'P1' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'P2' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'P3' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 10,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                    ]
                ]
            ],
            'ahorro_con_impuestos' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_THAT_DEPENDS_ON => [
                    'cabecera' => 'tipo_campana',
                    'values' => ['OPP'],
                    'rules' => [
                        self::RULE_CAN_BE_NULL => false,
                        self::RULE_LENGTH => 20,
                        self::RULE_TYPE => self::RULE_TYPE_FLOAT,
                        self::RULE_FORBID => ',',
                        self::RULE_MAX_DECIMALS => 2,
                    ]
                ]
            ],
            'char_cif' => [
                self::RULE_LENGTH => 10,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_CAN_BE_NULL => true
            ],
            'dat_ultimo_cambio' => [
                self::RULE_LENGTH => 12,
                self::RULE_TYPE => self::RULE_TYPE_DATE,
                self::RULE_CAN_BE_NULL => true
            ],
            'int_consumo' => [
                self::RULE_LENGTH => 12,
                self::RULE_TYPE => self::RULE_TYPE_INT,
                self::RULE_CAN_BE_NULL => true
            ],
            'multisuministro' => [
                self::RULE_LENGTH => 1,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_LIMITED_TO => ['S', 'N'],
                self::RULE_CAN_BE_NULL => false,
            ],
            'tipo_campana' => [
                self::RULE_LENGTH => 3,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_LIMITED_TO => ['OPP', 'SOP', 'AUT'],
                self::RULE_CAN_BE_NULL => false,
            ],
            'mail' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_EMAIL,
                self::RULE_LENGTH => 255,
            ],
            'comentario' => [
                self::RULE_CAN_BE_NULL => true,
                self::RULE_TYPE => self::RULE_TYPE_STRING,
                self::RULE_LENGTH => 255,
            ]
        ];
    }

    /**
     * Helper de la RULE self::RULE_VALUE_EXIST_IN_DB devuelve la tabla hasheada para la facil comprobacion de si existe o no el contenido pasado en el csv
     * Se deben pasar el nombre de la tabla y la columna
     * Ataca a la BD de intranet
     * Setea la propiedad $hashTables
     * @param $dbTable
     * @param $dbField
     * @throws Exception
     */
    private function generadorHashRelationalTable($dbTable, $dbField)
    {
        try {

            $rows = Yii::$app->db->createCommand("SELECT $dbField  FROM $dbTable")->queryAll();

            if (!count($rows)) {

                $this->setError('No hay ningún registro disponible para la tabla y campo facilitado, no se puede construïr la hashtable',
                    self::ERROR_DEV);

            } else {

                $tempArray = [];
                foreach ($rows as $row) {
                    $tempArray[$row[$dbField]] = $row[$dbField];
                }

                $this->hashTables["$dbTable-$dbField"] = $tempArray;
            }

        } catch (\Exception $e) {
            throw new Exception('No ha sido possible generar la hashtable', 500);
        }
    }

    /**
     * Setea los mensajes de error de las validaciones
     * @param $cabecera
     * @param $mensaje
     */
    private function setValidationError($cabecera, $mensaje)
    {
        $this->setError("<b>[ fila: $this->fila ] [ $cabecera ]</b> $mensaje",
            self::ERROR_USER);
    }

    /**
     * Encargado de meter los errores en el array interno
     * Se admiten dos tipos, 'user' y 'developer', los errores de tipo user seran los visibles en las vistas, los otros quedan para el debuging
     * @param $mensaje
     * @param $tipo
     */
    private function setError($mensaje = '', $tipo = self::ERROR_USER)
    {
        array_push($this->errores[$tipo], $mensaje);
    }

    /**
     * Devuelve los errores del validador, si no se especifica $tipo devuelve todos
     * @param $tipo
     * @return array|mixed
     */
    public function getErrors($tipo)
    {
        return $tipo ? $this->errores[$tipo] : $this->errores;
    }
}