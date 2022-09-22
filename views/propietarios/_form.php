<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Propietarios */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerJsFile('@web/js/propietarios.js');

?>

<div class="propietarios-form">

        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Propietario</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">

                <div class="row card-header text-muted border-bottom-0"> <i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Datos Propietario</h3> </div>
                <div class="row">
                    <div class="col-md-12 " >
                        <div class="line"></div>
                        <?= $form->field($model, 'nombre_propietario')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Nombre Propietario') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 " >
                        <?= $form->field($model, 'tipo_documento_propietario')->dropDownList( $model::arrTipoDocumentos , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label('Tipo Doc.'); ?>
                    </div>
                    <div class="col-md-8 "  >
                        <?= $form->field($model, 'documento_propietario')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Documento Propietario') ?>
                    </div>
                </div>

                <div class="row card-header text-muted border-bottom-0"> <i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Datos Representante</h3> </div>
                <div class="row">
                    <div class="col-md-12 " >
                        <div class="line"></div>
                        <?= $form->field($model, 'nombre_representante')->textInput(['maxlength' => true,'class'=>'form-control','style'=>'text-transform: uppercase'])->label('Nombre Representante') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 " >
                        <?= $form->field($model, 'tipo_documento_representante')->dropDownList( $model::arrTipoDocumentos , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label('Tipo Doc.'); ?>
                    </div>
                    <div class="col-md-8 "  >
                        <?= $form->field($model, 'documento_representante')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Documento Representante') ?>
                    </div>
                </div>
                <div class="row card-header text-muted border-bottom-0"><i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Info. Contacto</h3> </div>
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
                        <?= $form->field($model, 'poblacion')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Población') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 "  >
                        <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Dirección') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 " >
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Teléfono') ?>
                    </div>
                    <div class="col-md-6 "  >
                        <?= $form->field($model, 'movil')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Móvil') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 "  >
                        <?= $form->field($model, 'email')->textInput (['maxlength' => true,'class'=>'form-control'])->label('Correo Electronico') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 "  >
                        <?= $form->field($model, 'observaciones')->textArea(['id' => 'observaciones', 'class' => 'form-control', 'style' => 'width:100%'])->label('Notas/Observaciones') ?>
                    </div>
                </div>

                <div  style="padding-top: 15px" >
                    <?= Html::submitButton(Yii::t('app', 'Guardar Propietario'), ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>




</div>
