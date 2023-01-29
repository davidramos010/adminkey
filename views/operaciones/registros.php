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
                    <?= $form->field($model, 'csv_file',['options' => ['class' => 'file-uploader']])->widget(FileInput::class, [
                        'language' => 'es',
                        'options' => [
                            'id' => 'csv_file',
                            'multiple'=>false
                        ],
                        'pluginOptions' => array_merge(
                            [
                                'showBrowse' => true,
                                'showCaption' => true,
                                'showRemove' => true,
                                'showUpload' => true,
                                'showPreview' => true,
                                'initialCaption'=>"Seleccione archivo para subir de movimientos",
                                'uploadUrl' => Url::to(['operaciones/ajax-load']),
                                'uploadExtraData' => [
                                    'movimientos' => true,
                                ],
                            ])
                    ])->label('Plantilla de carga masiva - Movimientos')   ?>
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