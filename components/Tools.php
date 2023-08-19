<?php
/**
 * Created by PhpStorm.
 */

namespace app\components;


use app\models\app\CodigoNotas;
use app\models\app\Empresa;
use factorenergia\adminlte\widgets\Box;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;
use kartik\widgets\Select2;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Tools
{
    public static $type_association = [
        'img' => 'image',
        'xls' => 'office',
        'ppt' => 'office',
        'doc' => 'office',
        'txt' => 'text',
        'pdf' => 'pdf',
        'html' => 'html',
        'mov' => 'video',
        'zip' => 'zip',
        'mp3' => 'mp3'
    ];

    public static function selectCompany()
    {
        $companies = Yii::$app->session->get('intranet_companies_user');

        return Html::beginForm('', 'post', [
                'id' => 'selectCompany',
                'style' => 'width:250px;'
            ])
            . Select2::widget([
                'name' => 'Company',
                'hideSearch' => false,
                'data' => ArrayHelper::map($companies, 'clave', 'descripcion'),
                'value' => Yii::$app->session->get('intranet_company'),
                'options' => [
                    'onchange' => '$(\'#selectCompany\').submit();',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'containerCssClass' => 'colorCompany',
                ],
            ])
            . Html::endForm();
    }

    /**
     * Barra lateral con el listado de compañias
     * Se cargan de la sesion del usuario
     * @return string
     */
    public static function sideSelectCompany()
    {
        $user_company = Yii::$app->session->get('intranet_company');
        $user_companies = Yii::$app->session->get('intranet_companies_user');

        $html = '';
        foreach ($user_companies as $u_company) {
            $html .= Html::a(Html::img(Url::base() . "/images/favicon" . $u_company->empresa->clave . ".png",
                ['style' => 'width:40px;']),
                Url::to(['/site/cambiar-de-empresa']),
                [
                    'class' => $u_company->empresa->clave === $user_company ? 'selected_cp' : 'non-selected',
                    'style' => 'text-align:center',
                    'data' => [
                        'method' => 'post',

                        'params' => [
                            'clave' => $u_company->empresa->clave,
                            'id' => $u_company->empresa->id,
                            'prev_url' => '/' . Yii::$app->view->context->route . '?' . http_build_query(Yii::$app->view->context->actionParams)
                        ]
                    ]
                ]);
        }

        return $html;
    }

    public static function liItemsCompany()
    {
        $company = Empresa::find()->all();

        $html = '';
        foreach ($company as $c) {
            $html .= '<li>'
                . Html::beginForm('', 'post', [
                    'class' => 'hidden-md hidden-lg'
                ])
                . Html::submitButton(
                    $c->descripcion,
                    [
                        'class' => 'btn btn-link',
                        'value' => $c->clave,
                        'name' => 'Company'
                    ]
                )
                . Html::endForm()
                . '</li>';
        }

        return $html;
    }

    public static function getMoneyFormatedUserToSql($money)
    {
        if (!empty($money)) {
            $money_float = str_replace('€', '', $money);
            $moneyFormat = str_replace(',', '.', str_replace('.', '', $money_float));
        } else {
            $moneyFormat = 0;
        }
        return $moneyFormat;
    }

    public static function getNumberFormatedUserToSql($number)
    {
        if (!empty($number)) {
            $numberFormat = str_replace(',', '.', str_replace('.', '', $number));
        } else {
            $numberFormat = 0;
        }
        return $numberFormat;
    }

    public static function getNumberFormatedSqlToNumber($number)
    {
        if (!empty($number)) {
            $numberFormat = str_replace('.', ',', $number);
        } else {
            $numberFormat = 0;
        }
        return $numberFormat;
    }

    public static function roundDecimal($decimal, $suffix = 2)
    {
        $suffix = $suffix + 1;
        $decimal_suffix = (substr($decimal, 0, strrpos($decimal, '.') + $suffix));
        $param = (substr($decimal, strrpos($decimal, '.') + $suffix, 1));

        return ($param > 0) ? $decimal_suffix + 0.01 : $decimal_suffix;

    }

    public static function getDateFormatedUserToSql($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('d/m/Y', $date);
            $dateFormated = $fecha->format('Y-m-d');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateFormatedSqlToUser($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('Y-m-d', $date);
            $dateFormated = $fecha->format('d/m/Y');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateTimeFormatedSqlToUser($date,$strFormat = '/')
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $dateFormated = ($strFormat == '/') ? $fecha->format('d/m/Y H:i:s') : $fecha->format('d-m-Y H:i:s');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateTimeShortFormatedSqlToUser($date,$strFormat = '/')
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $dateFormated = ($strFormat == '/') ? $fecha->format('d/m/Y H:i') : $fecha->format('d-m-Y H:i');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getDateTimeFormatedUserToSql($date)
    {
        if (!empty($date)) {
            $fecha = \DateTime::createFromFormat('d-m-Y H:i', $date);
            $dateFormated = $fecha->format('Y-m-d H:i:s');
        } else {
            $dateFormated = $date;
        }
        return $dateFormated;
    }

    public static function getJsonFormatedListToUser(
        $json,
        $colNotShow = [],
        $mensaje_vacio = 'No hay resultados',
        $column = 2,
        $widthValue = 'col-xs-4',
        $display = true
    ) {
        $detail = [];
        $row = 0;
        $num_col = 1;
        $columns = [];
        $pos = 0;
        $total = 0;

        if (!empty($json)) {
            $arrJson = json_decode($json);

            foreach ($arrJson as $key => $value) {
                if (!in_array($key, $colNotShow)) {
                    $arrayPos = "fila$row";

                    $columns['columns'][$num_col] = [
                        'attribute' => '',
                        'label' => $key,
                        'value' => $value,
                        'valueColOptions' => ['class' => $widthValue],
                        'displayOnly' => $display,
                    ];

                    if ($num_col == $column) {
                        $num_col = 0;
                        if (!isset($detail[$arrayPos])) {
                            $detail[$arrayPos] = [];
                        }
                        $detail[$arrayPos] = $columns;
                        $columns = [];
                        $row++;
                    }

                    $num_col++;
                    $pos++;
                }
                $total++;
            }

            if ($pos % $column == 1 && $total == count((array)$arrJson)) {
                $columns['columns'][1]['valueColOptions']['class'] = 'col-xs-10';
                $detail['fila' . $row] = $columns;
            }
        } else {
            $detail = [
                'fila0' => [
                    'attribute' => '',
                    'label' => '',
                    'value' => $mensaje_vacio,
                    'format' => 'raw'
                ]
            ];
        }
        return $detail;
    }

    /**
     * @param $icon
     * @param $title
     * @param $link
     * @param int $to
     * @param bool $count
     * @param bool $fa SI LO SETEAMOS COMO FALSE, PODEMOS PASAR EMOJIS en el CAMPO $icon
     * @return string
     */
    public static function menuBoxes($icon, $title, $link, $to = 0, $count = true, $fa = true)
    {
        if ($count) {
            $content = Html::tag('h3',
                Html::tag('span',
                    (!empty($to)) ? '' : '&nbsp;',
                    [
                        'class' => (!empty($to)) ? 'count-to' : 'count-too',
                        'data' => ['from' => '', 'to' => (!empty($to)) ? $to : '']
                    ]
                ) .
                Html::tag('small', $title), ['class' => 'dashboard-title']);
            $counterClass = ' counter';
        } else {
            $content = Html::tag('h3',
                Html::tag('span', (!empty($to)) ? $to : '&nbsp;') .
                Html::tag('small', $title), ['class' => 'dashboard-title']);
            $counterClass = ' counter';
        }

        $icon = $fa ? Icon::show($icon) : "<i class='fa'>$icon</i>";
        return Html::a(
            Html::tag('div',
                Html::tag('div',
                    $icon . $content
                    ,
                    ['class' => 'dashboard-item dashboard-item_bluepd animated flipInX' . $counterClass]
                ),
                [
                    'class' => 'col-xs-12 col-sm-6 col-md-3 col-lg-3'
                ]
            )
            , [$link]);
    }

    /**
     * Menu boxes mejorada, elimina el $count y $icono (Ahora es un emoji) de favawesome y añade la posibilidad de darle fondo
     * @param $emoji
     * @param $title
     * @param null $link Si es falso|null|vacio devuelve un <span> en lugar de un <a>
     * @param null $background
     * @param null $color
     * @return string
     */
    public static function menuBoxesV($emoji, $title, $link = null, $background = null, $color = null)
    {
        $content = Html::tag('h4',
            Html::tag('span', $title), ['class' => 'dashboard-title']);

        return Html::tag($link ? 'a' : 'span',
            Html::tag('div',
                Html::tag('div',
                    "<i class='fa'>$emoji</i>" . $content
                    ,
                    [
                        'class' => 'dashboard-item dashboard-item_bluepd',
                        'style' => ($background ? "background:$background;" : null) . ($color ? "color:$color" : null)
                    ]
                )
            )
            , $link ? [
                'href' => Url::toRoute([$link]),
                'class' => 'col-xs-12 col-sm-6 col-lg-4 vv-dashboard'
            ] : ['class' => 'col-xs-12 col-sm-6 col-lg-4  vv-dashboard']);
    }


    /**
     * @param string $previewUrl Ther preview url
     * @param string $previewType The
     * @param $fileName
     * @return array
     */
    public static function getPreviewConfigFileInput(
        $previewUrl,
        $previewType,
        $fileName,
        $editable = false,
        $new = false
    ) {
        $options = [
            'initialPreviewShowDelete' => false,
            'showRemove' => false,
            'showClose' => false,
            'fileActionSettings' => [
                'showDrag' => false,
                'showRemove' => false,
            ],
            'showCaption' => false,
            'showUpload' => false,
            'showBrowse' => $editable,
            'dropZoneEnabled' => $editable && !$new,
            'browseClass' => ' btn btn-primary btn-sm',
        ];

        $possiblePreview = in_array($previewType, ['pdf', 'img']);
        if (!empty($previewUrl) && !empty($previewType) && !empty($fileName)) {
            if (!empty($previewUrl) && $possiblePreview) {
                $options['initialPreview'] = $previewUrl;
                $options['initialPreviewAsData'] = true;
            } else {
                $options['initialPreview'] = self::iconAssociaton($previewType);
                $options['initialPreviewAsData'] = false;
            }

            if (!empty($previewType) || !empty($fileName)) {
                $initialPreviewConfig['key'] = 1;
                if (!empty($previewType) && $possiblePreview) {
                    $initialPreviewConfig['type'] = self::$type_association[$previewType];
                }
                $initialPreviewConfig['caption'] = $fileName;
                $initialPreviewConfig['downloadUrl'] = $previewUrl;
            }
            $options['initialPreviewConfig'][] = $initialPreviewConfig;
            $options['previewFileIconSettings'] = [
                'zip' => Icon::show('file-archive-o'),
                'xls' => Icon::show('file-o')
            ];
        }

        return $options;
    }

    public static function iconAssociaton($fyleType)
    {
        $iconMatrix = [
            'doc' => Icon::show('file-word-o',
                [
                    'class' => 'text-primary',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'xls' => Icon::show('file-excel-o',
                [
                    'class' => 'text-success',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'ppt' => Icon::show('file-powerpoint-o',
                [
                    'class' => 'text-danger',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'pdf' => Icon::show('file-pdf-o',
                [
                    'class' => 'text-danger',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'zip' => Icon::show('file-archive-o',
                [
                    'class' => 'text-muted',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'htm' => Icon::show('file-code-o',
                [
                    'class' => 'text-info',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'txt' => Icon::show('file-text-o',
                [
                    'class' => 'text-info',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'mov' => Icon::show('file-movie-o',
                [
                    'class' => 'text-warning',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'mp3' => Icon::show('file-audio-o',
                [
                    'class' => 'text-warning',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            // note for these file types below no extension determination logic
            // has been configured (the keys itself will be used as extensions)
            'jpg' => Icon::show('file-photo-o',
                [
                    'class' => 'text-danger',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'gif' => Icon::show('file-photo-o',
                [
                    'class' => 'text-muted',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'png' => Icon::show('file-photo-o',
                [
                    'class' => 'text-primary',
                    'style' => 'font-size:10em; margin:15px;'
                ]),
            'dir' => Icon::show('folder',
                [
                    'class' => 'text-primary',
                    'style' => 'font-size:10em; margin:15px;'
                ])
        ];
        return $iconMatrix[$fyleType];
    }

    public static function elementGridView($element)
    {
        return "<p class='rowsN-cut'>" . html_entity_decode($element) . "</p>";
    }

    public static function getCurrentVersion()
    {
        if (Yii::$app->params['environment'] == 'dev') {
            $path = Yii::getAlias('@app');
            `git rev-parse --abbrev-ref HEAD > $path/version`;
        }
        return 'V.' . file_get_contents(Yii::getAlias('@app/version'));
    }

    public static function semaphore($customOptions = [])
    {
        $options = [];
        $options['style'] = 'border-radius:50%; border: solid 1px #7c7c7c; width: 16px; height:16px; display: inline-block; vertical-align: text-top;';

        if (isset($customOptions['colorName'])) {
            $options['class'] = $customOptions['colorName'];
        }
        if (isset($customOptions['color'])) {
            $options['style'] .= ' background-color: ' . $customOptions['color'] . '; ';
        }
        if (isset($customOptions['tooltip'])) {
            $options['title'] = $customOptions['tooltip'];
            $options['data']['tooltip'] = true;
        }
        if (isset($customOptions['size'])) {
            $options['style'] .= ' width: ' . $customOptions['size'] . '; height:' . $customOptions['size'] . ';';
        } else {

            $options['style'] .= ' width: 16px; height:16px;';
        }
        return Html::tag('span', '', $options);
    }

    public static function legendStates($type, $title = 'Leyenda')
    {
        $states = CodigoNotas::getStatusSemaphoresWithInformation($type);

        Box::begin([
            'id' => 'leads_box',
            'header' => $title,
            'icon' => 'book',
            'type' => Box::TYPE_PRIMARY,
            'filled' => true,
            "collapsable" => true,
        ]);
        echo Html::tag('div', $states, ['class' => 'row']);
        Box::end();

    }

    public static function array_sort($array, $on, $order = SORT_ASC)
    {

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public static function validateContentType($request, $type = 'application/json')
    {
        if ($request->getContentType() == $type) {
            $control = 'correct_format';
        } else {
            $control = 'wrong_content_type';
        }
        return $control;
    }

    public static function validateServiceType($data)
    {
        if ($data['type'] == 'E' or $data['type'] == 'G') {
            $control = 'correct_format';
        } else {
            $control = 'wrong_content_type';
        }
        return $control;
    }

    public static function checkRequiredFields($data, $requiredFields)
    {
        $emptyRequired = [];
        if ($data !== null) {
            foreach ($requiredFields as $item) {
                if (empty($data[$item])) {
                    $emptyRequired[] = $item;
                }
            }
        }
        return $emptyRequired;
    }

    public static function setMessage($control, $emptyRequired = [], $message = '')
    {
        if (!empty($emptyRequired)) {
            if (count($emptyRequired) > 1) {
                $control = 'empty_fields';
            } else {
                $control = 'empty_field';
            }
        } elseif ($control == 'correct_format') {
            $control = 'correct';
        }

        switch ($control) {
            case 'correct':
                $response = [
                    'message' => ($message != '') ? $message : 'Llamada realizada correctamente.'
                ];
                break;
            case 'wrong_format':
                $response = [
                    'message' => 'Formato de datos incorrecto o datos no enviados.'
                ];
                break;
            case 'empty_fields':
                $response = [
                    'message' => 'Los campos ' . implode(',',
                            $emptyRequired) . ' son obligatorios y han llegado vacios.'
                ];
                break;
            case 'empty_field':
                $response = [
                    'message' => 'El campo ' . implode(',', $emptyRequired) . ' es obligatorio y ha llegado vacío.'
                ];
                break;
            case 'services_error':
                $response = [
                    'message' => 'Error interno en el servicio'
                ];
                break;
            case 'wrong_content_type':
                $response = [
                    'message' => 'No se está llamando al servicio del modo correcto.'
                ];
                break;
            case 'no_response_ws':
                $response = [
                    'message' => 'El webservice externo no responde.'
                ];
                break;
            default:
                $response = [
                    'message' => 'Error no controlado.'
                ];
                break;
        }
        return $response;


    }

    /**
     * @param $filename
     * @return mixed
     */
    public static function getFileType($filename)
    {
        $type = '';
        switch (pathinfo(strtolower($filename), PATHINFO_EXTENSION)) {
            case 'doc':
            case 'docx':
                $type = 'doc';
                break;
            case 'xls':
            case 'xlsx':
                $type = 'xls';
                break;
            case 'ppt':
            case 'pptx':
                $type = 'ppt';
                break;
            case 'zip':
            case 'rar':
            case 'tar':
            case 'gzip':
            case 'gz':
            case '7z':
                $type = 'zip';
                break;
            case 'htm':
            case 'html':
                $type = 'htm';
                break;
            case 'txt':
            case 'ini':
            case 'csv':
            case 'java':
            case 'php':
            case 'js':
            case 'css':
                $type = 'txt';
                break;
            case 'avi':
            case 'mpg':
            case 'mkv':
            case 'mov':
            case 'mp4':
            case '3gp':
            case 'webm':
            case 'wmv':
                $type = 'mov';
                break;
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
            case 'bmp':
            case 'tiff':
                $type = 'img';
                break;
            case 'mp3':
            case 'wav':
                $type = 'mp3';
                break;
            case 'pdf':
                $type = 'pdf';
                break;
            default:
                $type = 'default';
                break;
        }
        return $type;
    }

    /**
     * @param string $mime Mime type
     * @return mixed
     */
    public static function getFileTypeByMime($mime)
    {
        $explodedMime = explode('/', $mime);
        if ($explodedMime[0] == 'application') {
            $type = $explodedMime[1];
            if (strpos($type, 'office') !== false) {
                $type = 'office';
            }
        } else {
            $type = $explodedMime[0];
        }
        return $type;
    }

    public static function isCUPS($cups)
    {
        $format = preg_match('/^ES[0-9]{16}[A-Z]{2}$/i', $cups);

        if ($format) {
            $CUPS_16 = substr($cups, 2, 16);
            $control = substr($cups, 18, 2);
            $letters = [
                'T',
                'R',
                'W',
                'A',
                'G',
                'M',
                'Y',
                'F',
                'P',
                'D',
                'X',
                'B',
                'N',
                'J',
                'Z',
                'S',
                'Q',
                'V',
                'H',
                'L',
                'C',
                'K',
                'E'
            ];
            $fmod = fmod($CUPS_16, 529);
            $imod = intval($fmod);

            $quotient = $imod / 23;
            $remainder = $imod % 23;

            $dc1 = $letters[$quotient];
            $dc2 = $letters[$remainder];
            return ($control == $dc1 . $dc2);

        }
        return false;
    }

    public static function isNif($nif)
    {
        $formatch = preg_match('/^[0-9]{8}[A-Z]{1}$/i', $nif);
        return ($formatch) ? true : false;
    }

    public static function isDni($dni)
    {
        $formatch = preg_match('/^[XYZ]?([0-9]{7,8})([A-Z])$/i', $dni);
        return ($formatch) ? true : false;
    }

    public static function isCif($cif)
    {
        $formatch = preg_match('/([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/i', $cif);
        return ($formatch) ? true : false;
    }

    public static function isPassport($passport)
    {
        $formatch = preg_match('/^[A-Z0-9<]{9}[0-9]{1}[A-Z]{3}[0-9]{7}[A-Z]{1}[0-9]{7}[A-Z0-9<]{14}[0-9]{2}$/i',
            $passport);
        return ($formatch) ? true : false;
    }

    public static function isNie($nie)
    {
        $formatch = preg_match('/^[XYZ]\d{7,8}[A-Z]$/i', $nie);
        return ($formatch) ? true : false;
    }

    public static function isCCC($ccc)
    {
        $formatch = preg_match('/^[0-9]{20}$/', $ccc);
        return ($formatch) ? true : false;
    }

    public static function validateNifNieCif(string $value): bool {
        $value = strtoupper($value);

        return self::isNif($value) || self::isNie($value) || self::isCif($value);
    }

    public static function validateTypeDocument($document)
    {
        //Copyright ©2005-2011 David Vidal Serra. Bajo licencia GNU GPL.
        for ($i = 0; $i < 9; $i++) {
            $num[$i] = substr($document, $i, 1);
        }

        //si no tiene un formato valido devuelve error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $document)) {
            return false;
        }

        //comprobacion de NIFs estandar
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $document)) {
            return ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($document, 0, 8) % 23, 1));
        }

        //algoritmo para comprobacion de codigos tipo CIF
        $suma = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2) {
            $suma += substr((2 * $num[$i]), 0, 1) + substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($suma, strlen($suma) - 1, 1);

        //comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
        if (preg_match('/^[KLM]{1}/', $document)) {
            return ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE',
                    substr($document, 1, 8) % 23, 1));
        }

        //comprobacion de CIFs
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $document)) {
            return ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1));
        }

        //comprobacion de NIEs T
        if (preg_match('/^[T]{1}/', $document)) {
            return ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $document));
        }

        //comprobacion de NIEs XYZ
        if (preg_match('/^[XYZ]{1}/', $document)) {
            return ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE',
                    substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $document), 0, 8) % 23, 1));
        }

        //si todavia no se ha verificado devuelve error
        return false;
    }

    /**
     * Growls/Toast que se usan en la aplicación, se llaman de todos los loyouts
     * @return string
     * @throws \Exception
     */
    public static function getGrowsLayout()
    {
        return AlertBlock::widget([
            'useSessionFlash' => true,
            'type' => AlertBlock::TYPE_GROWL,
            'delay' => 500,
            'alertSettings' => [
                'error' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_DANGER,
                    'icon' => false,
                    'showSeparator' => true,
                ],
                'success' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_SUCCESS,
                    'icon' => 'fa fa-check-square-o fa-15x',
                    'showSeparator' => true,
                ],
                'info' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_INFO,
                    'icon' => 'fa fa-info-circle fa-15x',
                    'showSeparator' => true,
                ],
                'warning' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_WARNING,
                    'icon' => 'fa fa-exclamation-triangle fa-15x',
                    'showSeparator' => true,
                ],
                'infoEstadoErroresKO' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock errorAlert',
                        'style' => 'color: #a94442;background-color: #f2dede;border-color: #ebccd1;',
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'bottom',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_CUSTOM,
                    'showSeparator' => true,
                ],
                'infoEstadoErroresOK' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock successAlert',
                        'style' => 'color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;',
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'bottom',
                            'align' => 'right',
                        ],
                    ],
                    'type' => Growl::TYPE_CUSTOM,
                    'showSeparator' => true,
                ],
                'fixed_error' => [
                    'options' => [
                        'class' => 'col-xs-11 col-sm-3 growlAlertBlock'
                    ],
                    'pluginOptions' => [
                        'offset' => 80,
                        'placement' => [
                            'from' => 'top',
                            'align' => 'right',
                        ],
                        'showProgressbar' => false,
                        'timer' => false,
                    ],
                    'type' => Growl::TYPE_DANGER,
                    'icon' => false,
                    'showSeparator' => true,
                ],
            ]
        ]);
    }

    /**
     * Obtener el mime type de un fichero
     *
     * @param string $url
     * @return string
     */
    public static function getUrlMimeType($url) {
        $buffer = file_get_contents($url);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($buffer);

        return $mime;
    }

    /**
     * Obtener la extensión mediante el mime type del fichero
     *
     * @param string $mime
     * @return bool|mixed
     */
    public static function getExtensionFromMimeType($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) === TRUE ? $mime_map[$mime] : FALSE;
    }

    public static function createDirectorySequence(array $directories, string $root){
        foreach ($directories as $item){
            $root .= "/{$item}";

            if(!file_exists($root)){
                mkdir($root, 0777, true);
            }
        }

        return $root;
    }
}

