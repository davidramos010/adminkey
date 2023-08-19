<?php

use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $modal boolean */

$this->registerJsFile('@web/js/comunidad.js');

?>
<div class="comunidad-form col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Cliente</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php $form = ActiveForm::begin(['id' => 'form-comunidad', 'enableClientValidation' => true, 'enableAjaxValidation' => false]); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Nombre / Razón Social') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'id_tipo_documento')->dropDownList( $model->arrTipoDocumentos , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label('Tipo Doc.'); ?>
                        </div>
                        <div class="col-md-9">
                            <?= $form->field($model, 'documento')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Documento Identidad') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 " >
                            <?=  $form->field($model, 'cod_postal')->widget(Select2::class, [
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'size' => Select2::SMALL,
                                    'data' => [$model->cod_postal => $model->cod_postal],
                                    'options' => [
                                        'data-js-req-cont' => 'generic'
                                    ],
                                    'pluginOptions' => [
                                        'minimumInputLength' => 4,
                                        'language' => [
                                            'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por población.'"),
                                            'errorLoading' => new JsExpression("() => 'Buscando...'"),
                                        ],
                                        'ajax' => [
                                            'url' => Url::to(['propietarios/codigos-postales']),
                                            'dataType' => 'json',
                                            'processResults' => new JsExpression('(data) => procesarResultadosCodigoPostal(data)'),
                                            'data' => new JsExpression('(params) => { return {q:params.term} }')
                                        ],
                                        'templateResult' => new JsExpression('(params) => params.loading ? "Buscando..." : params.id + " - " + params.poblacio'),
                                        'templateSelection' => new JsExpression('(cp) => cp.text'),
                                    ],
                                    'pluginEvents' => ['select2:select' => new JsExpression('({params}) => popularLocalidadProvincia(params)')]
                                ]
                            )  ?>
                        </div>
                        <div class="col-md-8 "  >
                            <?= $form->field($model, 'poblacion')->textInput(['id'=>'comunidad-poblacion','maxlength' => true,'class'=>'form-control','readonly' => true])->label('Población') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Dirección') ?>
                    </div>
                    <div class="row">
                        <div class="col-md-6 " >
                            <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Teléfono') ?>
                        </div>
                        <div class="col-md-6 " >
                            <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Móvil') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Email') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'contacto')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Persona Responsable/Contacto') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'nomenclatura')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Nomenclatura/Codigo') ?>
                    </div>
                    <div class="row">
                        <div class="col-md-2 " >
                            <?= $form->field($model, 'estado')->widget(SwitchInput::class, ['pluginOptions'=>['id'=>'estado','size'=>'small','onText'=>'Activo','offText'=>'Inactivo']])->label('Estado') ; ?>
                        </div>
                    </div>
                    <div  style="padding-top: 15px" >
                        <?= isset($modal) ? '' : Html::submitButton(Yii::t('app', 'Guardar Cliente'), ['class' => 'btn btn-success']) ?>
                        <?= isset($modal) ? '' : Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                        <?= isset($modal) ? Html::button(Yii::t('app', 'Guardar Cliente'), ['class' => 'btn btn-success ', 'data-js-set-comunidad' => true,]) : ''; ?>
                        <?= isset($modal) ? Html::button(Yii::t('app', 'Cancelar'), ['id'=>'btn_cancelar_modal_comunidad', 'class' => 'btn btn-default ', 'data-dismiss' => 'modal']) : ''; ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    <!-- /.card -->
</div>


