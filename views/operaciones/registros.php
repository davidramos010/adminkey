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
$this->title = 'Registros Movimientos';
$this->registerJsFile('@web/js/registro.js');
$descargarPlantilla = Html::a('Plantilla CSV <i class="glyphicon glyphicon-file"></i>', Url::to(['operaciones/descargar-plantilla-movimientos']), [
    'title' => 'Descargar plantilla de ejemplo',
    'download'  => 'REGISTROS_MOVIMIENTOS.csv',
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
                        'Ingreso masivo de movientos.'
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
                $form = ActiveForm::begin(['action' => ['movimientos'], 'id' => 'registration-form', 'enableClientValidation' => true, 'options' => ['enctype' => 'multipart/form-data']]);
                ?>
                <div>
                    <?=
                    FileInput::widget([
                        'name' => 'csv_file',
                        'id' => 'csv_file',
                        'options' => ['accept' => 'zip',],
                        'pluginOptions' => [
                            'uploadUrl' => Url::to(['operaciones/ajax-load-registros']),
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
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
        </div>
    </div>
</div>
</div>