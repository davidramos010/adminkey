<?php

use app\models\Registro;
use app\models\util;
use diggindata\signaturepad\SignaturePadWidget;
use inquid\signature\SignatureWidget;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registro-form">

    <div id="div_msm" style="display:none" class="callout callout-success">
        <h5><i class="fas fa-info-circle"></i> OK</h5>
        Registro almacenado correctamente.
    </div>

    <div id="div_info" class="callout callout-info" >
        <h5><i class="fas fa-info"></i> Note:</h5>
        Este registro estara asociado al usuario en sesion <label class="exampleInputBorder">( <?= Yii::$app->user->identity->name ?> ) </label>.<br/>
        Las llaves se irán registrando según su último estado de disponibilidad.
    </div>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registrar</h3>
            </div>
            <!-- /.card-header -->
            <?php $form = ActiveForm::begin(['id' => 'form-registro', 'enableClientValidation' => true, 'enableAjaxValidation' => false]); ?>
                <!-- form start -->
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10">
                                <?= $form->field($model, 'id_comercial')->widget(Select2::class, [
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'size' => Select2::SMALL,
                                        'data' => !empty($model->id_comercial) ? [$model->id_comercial => $model->comerciales->nombre] : [],
                                        'options' => [
                                            'data-js-req-cont' => 'generic',
                                            'id' => 'id_comercial'
                                        ],
                                        'pluginOptions' => [
                                            'minimumInputLength' => 4,
                                            'language' => [
                                                'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por empresa.'"),
                                                'errorLoading' => new JsExpression("() => 'Buscando...'"),
                                            ],
                                            'ajax' => [
                                                'url' => Url::to(['registro/find-comercial']),
                                                'dataType' => 'json',
                                                'processResults' => new JsExpression('(data) => procesarResultadosComercial(data)'),
                                                'data' => new JsExpression('(params) => { return {q:params.term} }')
                                            ],
                                            'templateResult' => new JsExpression('(params) => params.loading ? "Buscando..." : params.id + " - " + params.nombre'),
                                            'templateSelection' => new JsExpression('function (data) { 
                                                                                                if(data.nombre==="" || data.nombre === undefined || data.nombre === null){
                                                                                                    return data.text;
                                                                                                } else {
                                                                                                    return data.nombre;
                                                                                                } }'),
                                        ],
                                    ]
                                )->label('Empresa/Proveedor'); ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label" for="id_comercial_adicionar">&nbsp;</label><br/>
                                <?= !empty($model->id_comercial) ? Html::button('<i class="fas fa-info-circle"></i> '.Yii::t('app', 'Add_Contact'), ['class' => 'btn-sm btn-primary ', 'title' => Yii::t('app', 'Add_Contact'), 'onclick' => 'setCopyDataContacto()']) : '' ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <?= $form->field($model, 'tipo_documento')->dropDownList( util::arrTipoDocumentos , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label('Tipo Doc.'); ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($model, 'documento')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Documento Identidad') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Teléfono') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'nombre_responsable')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Nombre Responsable') ?>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-3" />
                    <!-- .ini table -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-5">
                                    Ingresar Referencia
                                </div>
                            </div>
                        </div>
                        <div class="card-header" >
                                <div class="row" >
                                    <div class="col-md-2">
                                        <?= Html::textInput('id_llave', '', ['id' => 'id_llave', 'class' => 'form-control']); ?>
                                        <?= Html::hiddenInput('id_operacion', 'S', ['id' => 'id_operacion', 'class' => 'form-control']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::button('Adicionar', ['class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { addKey() })();']); ?>
                                    </div>
                                    <div class="col-md-4" >
                                        <?= Html::button('Adicionar Manualmente', ['class' => 'btn btn-success', 'onclick' => 'addManual()']); ?>
                                    </div>
                                </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <!-- /.card -->
                            <div class="card card-primary card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-salida-tab" data-toggle="pill"
                                               href="#custom-tabs-salida" role="tab" aria-controls="custom-tabs-salida"
                                               aria-selected="false" onclick="fnSetOperacion('S')"><i
                                                        class="fas fa-angle-double-up text-success"></i> Entrega/Salida de Llave </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-entrada-tab" data-toggle="pill"
                                               href="#custom-tabs-entrada" role="tab" aria-controls="custom-tabs-entrada"
                                               aria-selected="true" onclick="fnSetOperacion('E')"><i
                                                        class="fas fa-angle-double-down text-danger"></i> Devolución de LLave </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">

                                        <div class="tab-pane fade show active" id="custom-tabs-salida" role="tabpanel"
                                             aria-labelledby="custom-tabs-salida-tab">
                                            <table id="tblKeySalida" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%">Código</th>
                                                    <th style="width: 40%">Descripción</th>
                                                    <th style="width: 35%">Cliente</th>
                                                    <th style="width: 5%"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Cuerpo -->
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="5"><?= Yii::t('app', 'Registro de Salida de llaves de la empresa.') ?></th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-entrada" role="tabpanel"
                                             aria-labelledby="custom-tabs-entrada-tab">
                                            <table id="tblKeyEntrada" class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th style="width: 20%">Código</th>
                                                    <th style="width: 40%">Descripción</th>
                                                    <th style="width: 35%">Cliente</th>
                                                    <th style="width: 5%"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <!-- Cuerpo -->
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="5"><?= Yii::t('app', 'Registrar Devolución de llaves a la empresa.') ?></th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- .fin table -->
                    <hr class="mt-2 mb-3" />
                    <div class="form-group">
                        <?= $form->field($model, 'observacion')->textArea(['id' => 'txt_observacion', 'class' => 'form-control', 'style' => 'width:100%'])->label('Observaciones') ?>

                        <div class="card text-center">
                            <div class="card-header">
                                <?= Yii::t('app', 'Firma de Aceptación') ?>
                            </div>
                            <div class="card-body">
                                <?= Html::button(Yii::t('app', 'Limpiar'), ['id' => 'btn_limpiar', 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnLimpiarCuadroFirma() })();']); ?>
                                <?php //echo Html::button(Yii::t('app', 'Guardar'), ['id' => 'btn_guardar', 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnGuardarCuadroFirma() })();']); ?>
                            </div>
                            <div class="card-footer text-muted">
                                <?= SignatureWidget::widget(['clear' => true, 'url' => '../registro/add-firma', 'save_server' => true]); ?>
                            </div>
                        </div>
                    </div>
                    <div style="padding-top: 15px">
                        <?= Html::button('Registrar Movimiento', ['id' => 'btn_registrar', 'class' => 'btn btn-success', 'onclick' => '(function ( $event ) { sendForm() })();']); ?>
                        <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php

$this->registerJs(
    '$(document).on("click", "[data-js-set-contacto]", function (e) {
            findCodeLlave();
        });'
);

$this->registerJs(
    '$("document").ready(function(){ 
             $("#id_llave").keypress(function(event) {
                if (event.keyCode === 13) {
                    addKey();
                }
            });
         });
         var wrapper = document.getElementById("signature-pad");'
);

$this->registerCss(".signature-pad--actions{ display:none; } ");
 ?>
