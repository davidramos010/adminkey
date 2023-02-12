<?php

use kartik\widgets\FileInput;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\models\util;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model \app\models\Csv */

$this->title = 'Llaves';
$this->registerJsFile('@web/js/registro.js');
$descargarPlantilla = Html::a('Descargar Plantilla CSV <i class="glyphicon glyphicon-file"></i>', Url::to(['operaciones/descargar-plantilla-llave']), [
    'title' => 'Descargar plantilla de ejemplo',
    'download'  => 'REGISTROS_LLAVE.csv',
    'class' => 'btn-sm btn-primary '
]);
?>
<div class="registro-index">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Ingreso masivo de llaves.',
                        'Para la carga masiva de llaves es necesario ingresar obligatoriamente un archivo con extesion CSV, como el que se ve en el ejemplo.'
                    ], ['encode' => false]);
                    echo Html::ul([
                        $descargarPlantilla,
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <!-- /.card-header -->
            <div class="card-body">
                <?php
                $form = ActiveForm::begin(['action' => ['llaves'], 'id' => 'registration-form', 'enableClientValidation' => true, 'options' => ['enctype' => 'multipart/form-data']]);
                 ?>
                <div>
                    <?= FileInput::widget([
                        'name' => 'csv_file',
                        'id' => 'csv_file',
                        'options' => ['accept' => 'zip',],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['operaciones/ajax-load-llaves']),
                            'showCaption' => false,
                            'showRemove' => false,
                            'showUpload' => true,
                            'showPreview' => false,
                            'showCancel' => false,
                            'browseClass' => 'btn btn-primary btn-block btn-seleccionar-fichero',
                            'browseIcon' => '<i class="glyphicon glyphicon-file"></i> ',
                            'browseLabel' => 'Seleccionar un fichero...',
                            'uploadClass' => 'btn btn-warning btn-block btn-validar-fichero',
                            'uploadIcon' => '⏫',
                            'uploadLabel' => 'Validar y cargar fichero...',
                        ],
                        'pluginEvents' => [
                            'fileuploaded' => new JsExpression('(event, data) => tratarRespuestaExito(data.response)'),
                            'fileuploaderror' => new JsExpression('(event, data) => tratarRespuestaError(data.response)'),
                        ]
                    ]);

                    echo Html::tag('div', '',
                        ['data-js-resultado-carga-fichero-error' => true, 'style' => 'text-align: center;padding-top: 15px;color:red']);
                    echo Html::tag('div', '',
                        ['data-js-resultado-carga-fichero-exito' => true, 'style' => 'text-align: center;padding-top: 15px;color:green']);
                    echo Html::tag('div', '',
                        ['data-js-resultado-carga-fichero-aviso' => true, 'style' => 'text-align: center;padding-top: 15px;color:red']);
                    ?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header border-0">
                            <h3 class="card-title">Descripción de columnas.</h3>
                        </div>
                        <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                            <?php

                            echo Html::ul([
                                ' CODIGO: Correponde a la nomenclatura usada por la comunidad, ejemplo C001. Puedes ser vacio si no tiene comunidad asignada.',
                                ' OFICINA: Lugar de almacenamiento fisico de la llave.',
                                ' TIPO: Comunidad / Particular.',
                                ' ACCESO: Descripción del acceso que da la llave.',
                                ' CANTIDAD: Cantidad de copias almacenadas de la misma llave.',
                                ' PROPIETARIO_CODIGO: Codigo del propietario de la llave, ejemplo P001. Puede ser vacio si no tiene propietario asignado',
                                ' PROPIETARIO: Nombre completo del propietario, si no tengo el codigo, busco por nombre o si es nuevo ingreso el nombre completo.',
                                ' ALARMA: SI / NO.',
                                ' CODIGO_ALARMA: Si tiene alarma sera obligatorio el codigo de acceso de la alarma.',
                                ' CONTRATO: SI / NO - Identificador de facturable.',
                                ' COMENTARIOS: Detalles de la llave.',
                                ' MOVIL: Movil / Telefono de contacto.',
                                ' DIRECCION: Dirección del inmueble, solo sera obligatorio en caso que la llave no cuente con Comunidad / Propietario existente',
                                ' CODIGO_POSTAL: Codigo postal del inmuebe, solo sera obligatorio en caso que la llave no cuente con Comunidad / Propietario existente.',

                            ], ['encode' => false]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</div>