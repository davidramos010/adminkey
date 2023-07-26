<?php

use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="contratos-form">

    <div class="row">
        <div class="col-md-10">
            <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                <?php
                echo Html::ul([
                    Yii::t('app', 'El nombre del documento debe ser Unico'),
                    Yii::t('app', 'Una vez se cumpla la fecha de finalización el contrato, no estará disponible para impresión.'),
                    Yii::t('app', 'Los documentos deben ser extension doc/docx.')
                ], ['encode' => false]);
                ?>
            </div>
        </div>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'contrato-form', 'options' => ['enctype' => 'multipart/form-data']
    ]); ?>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 " >
                <?= $form->field($model, 'nombre')->textInput(['id'=>'nombre', 'maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Nombre') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 " >
                <?= $form->field($model, 'descripcion')->textInput(['id'=>'descripcion', 'maxlength' => true,'class'=>'form-control'])->label('Descripción') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 " >
                <?= $form->field($model,'fecha_ini')->widget(DatePicker::class,
                    [
                        'options' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'Fecha inicio validez contrato'
                        ],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'startDate' => Date('Y-m-d'),
                        ],
                    ])->label('Fecha inicio'); ?>
            </div>
            <div class="col-md-3 " >
                <?= $form->field($model,'fecha_fin')->widget(DatePicker::class,
                    [
                        'options' => [
                            'autocomplete' => 'off',
                            'placeholder' => 'Fecha fin validez contrato',
                        ],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'startDate' => Date('Y-m-d'),
                        ],
                    ])->label('Fecha fin'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9 " >
                <?= $form->field($model, 'documento',['options' => ['class' => 'file-uploader']])->widget(FileInput::class, [
                    'language' => 'es',
                    'options' => [
                        'id' => 'documento',
                        'multiple'=>false
                    ],
                    'pluginOptions' => array_merge(
                        [
                            'showBrowse' => true,
                            'showCaption' => true,
                            'showRemove' => false,
                            'showUpload' => false,
                            'showPreview' => false,
                            'initialCaption'=>"Seleccione la plantilla de contrato",
                            'allowedFileTypes' => ['office','.doc', '.docx'],
                            'msgInvalidFileType' => 'El tipo de archivo de: {name} no es correcto. Sólo se admiten archivos del tipo "doc, docx".'

                        ])
                ])->label('Plantilla de contrato')   ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 " >
                <?= $form->field($model, 'estado')->widget(SwitchInput::class, ['pluginOptions'=>['size'=>'small','onText'=>'Activo','offText'=>'Inactivo']])->label('Estado') ; ?>
            </div>
        </div>
        <div  style="padding-top: 15px" >
            <?= Html::submitButton('Guardar Contrato', ['class' => 'btn btn-success ']) ?>
            <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs('
        var fieldsChanged = false;
        $(document).on("change", "#contrato-form :input", function(){
            fieldsChanged = true;
        });
        $(window).on("beforeunload", function(){
            if(fieldsChanged)
               return "Tiene cambios sin guardar, ¿está seguro de que desea salir de esta página?";
        });
');
?>