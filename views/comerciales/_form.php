<?php

use app\models\User;
use app\models\util;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var app\models\Comerciales $model */
/** @var yii\bootstrap4\ActiveForm $form */


$this->registerJsFile('@web/js/comerciales.js');

?>
<br>
<div class="comerciales-form row">
    <div class="col-md-8">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Yii::t('app','Proveedor') ?></h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label(Yii::t('app', 'Nombre / Razón Social') ) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'id_tipo_documento')->dropDownList( util::arrTipoDocumentos , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label(Yii::t('app', 'Tipo Doc.')); ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model, 'documento')->textInput(['maxlength' => true,'class'=>'form-control'])->label(Yii::t('app', 'Documento Identidad')) ?>
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
                        )->label(Yii::t('app', 'Cód. Postal'))  ?>
                    </div>
                    <div class="col-md-8 "  >
                        <?= $form->field($model, 'poblacion')->textInput(['id'=>'comunidad-poblacion','maxlength' => true,'class'=>'form-control','readonly' => true])->label(Yii::t('app', 'Población')) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label(Yii::t('app', 'Dirección')) ?>
                </div>
                <div class="row">
                    <div class="col-md-4 " >
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'class'=>'form-control'])->label(Yii::t('app', 'Teléfono')) ?>
                    </div>
                    <div class="col-md-8 " >
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control'])->label(Yii::t('app', 'Email')) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label(Yii::t('app', 'Nombre Persona / Contacto')) ?>
                </div>
                <div class="row">
                    <div class="col-md-12 "  >
                        <?= $form->field($model, 'observacion')->textArea(['id' => 'observaciones', 'class' => 'form-control', 'style' => 'width:100%'])->label(Yii::t('app', 'Notas').' / '.Yii::t('app', 'Observaciones')) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'estado')->widget(SwitchInput::class, ['pluginOptions'=>['size'=>'small','onText'=>'Activo','offText'=>'Inactivo']])->label(Yii::t('app', 'Estado')) ; ?>
                </div>
                <div  style="padding-top: 15px" >
                    <?= Html::submitButton(Yii::t('app', 'Guardar').' '.Yii::t('app', 'Proveedor'), ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
