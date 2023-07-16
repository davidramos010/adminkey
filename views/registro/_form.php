<?php

use app\components\Tools;
use app\models\Registro;
use app\models\util;
use inquid\signature\SignatureWidget;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */
/* @var $form yii\widgets\ActiveForm */
/* @var $action string */

$url_registro_create = Url::toRoute(['registro/create']);
$url_registro_list = Url::toRoute(['registro/index']);
$ajax_register_motion = Url::toRoute(['registro/ajax-register-motion']);
$ajax_update_motion = Url::toRoute(['registro/ajax-update-motion']);
$ajax_delete_motion = Url::toRoute(['registro/ajax-delete-motion']);
$ajax_find_keys = Url::toRoute(['registro/ajax-find-keys-register']);
$ajax_find_manual = Url::toRoute(['../llave/ajax-find-manual']);
$ajax_find_comercial = Url::toRoute(['registro/ajax-find-comercial']);

$ajax_add_key = Url::toRoute(['registro/ajax-add-key']);
$url_add_firma = Url::toRoute(['registro/add-firma']);

$strAddNota = isset($action) && $action == 'update' ? "<br/>" . Yii::t('app', 'Solo puede Editar / Eliminar registros creados con su usuario.') : '';
$strAddNota .= !empty($strAddNota) ? "<br/>" . Yii::t('app', '<label class="exampleInputBorder">Importante:</label> Validar el último estado de las llaves relacionadas.') : '';

$strAddBotonRegistrar = isset($action) && $action == 'update' ? '' : Html::button(Yii::t('app', 'Registrar Movimiento'), ['id' => 'btn_registrar', 'class' => 'btn btn-success', 'onclick' => '(function ( $event ) { sendForm() })();']);
$strAddBotonCancelar = Html::a(Yii::t('app', Yii::t('app', 'Cancelar')), ['create'], ['class' => 'btn btn-default ']);
$strAddBotonEditar = isset($action) && $action == 'update' ? Html::button(Yii::t('app', 'Editar Movimiento'), ['id' => 'btn_editar', 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { sendUpdateForm() })();']) : '';
$strAddBotonEliminar = isset($action) && $action == 'update' ? Html::button(Yii::t('app', 'Eliminar Movimiento'), ['id' => 'btn_eliminar', 'class' => 'btn btn-danger', 'onclick' => '(function ( $event ) { sendDeleteForm() })();']) : '';

?>

<div class="registro-form">

    <!-- form start -->
    <?= $this->render('find') ?>
    <!-- form end -->

    <div id="div_msm" style="display:none" class="callout callout-success">
        <h5><i class="fas fa-info-circle"></i>OK</h5>
        <?= Yii::t('app', 'Registro almacenado correctamente.'); ?>
    </div>

    <div class="col-12">
        <div class="card collapsed-card callout callout-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info"></i> <?= Yii::t('app', 'Importante') . ' !!'; ?></h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <?=
                Html::ul([
                    Yii::t('app', 'Este registro estara asociado al usuario en sesion') . ' <label class="exampleInputBorder"> ' . Yii::$app->user->identity->name . '</label>',
                    Yii::t('app', 'Las llaves se irán registrando según su último estado de disponibilidad.'),
                    Yii::t('app', 'Los campos Propietario y Empresa/Proveedor deben ser seleccionados aun que no son obligatorio.'),
                    Yii::t('app', 'El campo \'Nombre de quien retira la llave\': Es obligatorio.'),
                    //$strAddNota
                ], ['encode' => false]);
                ?>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= Yii::t('app', 'Registrar') ?></h3>
            </div>
            <!-- /.card-header -->
            <?php $form = ActiveForm::begin(['id' => 'form-registro', 'enableClientValidation' => true, 'enableAjaxValidation' => false]); ?>
            <!-- form start -->
            <div class="card-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-5">
                            <?= $form->field($model, 'id_propietario')->widget(Select2::class, [
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'size' => Select2::SMALL,
                                    'data' => !empty($model->id_comercial) ? [$model->id_comercial => $model->comerciales->nombre] : [],
                                    'options' => [
                                        'data-js-req-cont' => 'generic',
                                        'id' => 'id_propietario'
                                    ],
                                    'pluginOptions' => [
                                        'minimumInputLength' => 3,
                                        'language' => [
                                            'inputTooShort' => new JsExpression("() => 'Escríbe 3 caracteres mínimo. Puedes buscar por nombre.'"),
                                            'errorLoading' => new JsExpression("() => 'Buscando...'"),
                                        ],
                                        'ajax' => [
                                            'url' => Url::to(['registro/find-propietarios']),
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
                            )->label(Yii::t('app', 'Propietario')); ?>
                        </div>
                        <div class="col-md-5">
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
                            )->label(Yii::t('app', 'Empresa/Proveedor')); ?>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label" for="id_comercial_adicionar">&nbsp;</label><br/>
                            <?= !empty($model->id_comercial) ? Html::button('<i class="fas fa-info-circle"></i> ' . Yii::t('app', 'Add_Contact'), ['class' => 'btn-sm btn-primary ', 'title' => Yii::t('app', 'Add_Contact'), 'onclick' => 'setCopyDataContacto()']) : '' ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <?= $form->field($model, 'nombre_responsable')->widget(Select2::class, [
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'size' => Select2::SMALL,
                                    'data' => [],
                                    'options' => [
                                        'data-js-req-cont' => 'generic',
                                        'id' => 'nombre_responsable'
                                    ],
                                    'pluginOptions' => [
                                        'tags' => true,
                                        'minimumInputLength' => 4,
                                        'language' => [
                                            'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por nombre.'"),
                                            'errorLoading' => new JsExpression("() => 'Buscando...'"),
                                        ],
                                        'ajax' => [
                                            'url' => Url::to(['registro/find-responsables']),
                                            'dataType' => 'json',
                                            'processResults' => new JsExpression('(data) => procesarResultadosResponsable(data)'),
                                            'data' => new JsExpression('(params) => { return {q:params.term} }')
                                        ],
                                        'cache' => true,
                                        'templateResult' => new JsExpression('(params) => params.loading ? "Buscando..." : params.responsable'),
                                        'templateSelection' => new JsExpression('function (data) {  
                                                                                                if(data.nombre==="" || data.nombre === undefined || data.nombre === null){
                                                                                                    return data.text;
                                                                                                } else {
                                                                                                    return data.nombre;
                                                                                                } }'),
                                    ],
                                    'pluginEvents' => ['select2:select' => new JsExpression('({params}) => fnSelectionResponsable(params.data)')]
                                ]
                            )->label(Yii::t('app', 'Nombre de quien retira la llave')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($model, 'tipo_documento')->dropDownList(util::arrTipoDocumentos, ['class' => 'form-control', 'prompt' => 'Seleccione Uno'])->label(Yii::t('app', 'Tipo') . ' Doc.'); ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($model, 'documento')->textInput(['maxlength' => true, 'class' => 'form-control'])->label(Yii::t('app', 'Documento Identidad')) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, 'class' => 'form-control'])->label(Yii::t('app', 'Telefono')) ?>
                        </div>
                    </div>

                </div>
                <hr class="mt-2 mb-3"/>
                <!-- .ini table -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5">
                                <?= Yii::t('app', 'Ingresar Referencia') ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-2">
                                <?= Html::textInput('id_llave', '', ['id' => 'id_llave', 'class' => 'form-control']); ?>
                                <?= Html::hiddenInput('id_operacion', 'S', ['id' => 'id_operacion', 'class' => 'form-control']); ?>
                            </div>
                            <div class="col-md-3">
                                <?= Html::button(Yii::t('app', 'Adicionar'),
                                    ['class' => 'btn btn-primary btn-sm', 'onclick' => '(function ( $event ) { addKey() })();']); ?>
                            </div>
                            <div class="col-md-7">
                                <?= Html::button(Yii::t('app', 'Adicionar Manualmente'),
                                    ['data-toggle' => 'modal', 'data-target' => '#modal-llave-manual', 'class' => 'btn btn-success float-right btn-sm']); ?>
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
                                                    class="fas fa-angle-double-up text-success"></i>
                                            <?= Yii::t('app', 'Entrega/Salida de Llave') ?>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-entrada-tab" data-toggle="pill"
                                           href="#custom-tabs-entrada" role="tab" aria-controls="custom-tabs-entrada"
                                           aria-selected="true" onclick="fnSetOperacion('E')"><i
                                                    class="fas fa-angle-double-down text-danger"></i>
                                            <?= Yii::t('app', 'Devolución de LLave') ?>
                                        </a>
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
                                                <th style="width: 20%"><?= Yii::t('app', 'Codigo') ?></th>
                                                <th style="width: 40%"><?= Yii::t('app', 'Descripción') ?></th>
                                                <th style="width: 35%"><?= Yii::t('app', 'Cliente') ?></th>
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
                                                <th style="width: 20%"><?= Yii::t('app', 'Codigo') ?></th>
                                                <th style="width: 40%"><?= Yii::t('app', 'Descripción') ?></th>
                                                <th style="width: 35%"><?= Yii::t('app', 'Cliente') ?></th>
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
                <hr class="mt-2 mb-3"/>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2">
                            <?= $form->field($model,
                                'fecha_registro')->widget(DateTimePicker::class,
                                [
                                    'options' => [
                                        'autocomplete' => 'off',
                                    ],
                                    'pluginOptions' => [
                                        'format' => 'dd-mm-yyyy hh:ii',
                                        'daysOfWeekDisabled' => [0, 6],
                                        'autoclose' => true,
                                        'startDate' => date("d-m-Y", strtotime(date("d-m-Y") . "- 30 days")),
                                    ],
                                ])->label('Fecha Registro'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'observacion')->textArea(['id' => 'txt_observacion', 'class' => 'form-control', 'style' => 'width:100%'])->label(Yii::t('app', 'Observaciones')) ?>
                        </div>
                    </div>

                    <div class="card text-center">
                        <div class="card-header">
                            <?= Yii::t('app', 'Firma de Aceptación') ?>
                        </div>
                        <div class="card-body">
                            <?= Html::button(Yii::t('app', 'Limpiar'), ['id' => 'btn_limpiar', 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnLimpiarCuadroFirma() })();']); ?>
                        </div>
                        <div class="card-footer text-muted">
                            <?= SignatureWidget::widget(['clear' => true, 'url' => $url_add_firma, 'save_server' => true]); ?>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 15px">
                    <?= $form->field($model, 'id')->hiddenInput(['id' => 'id_registro'])->label(false); ?>
                    <?= $strAddBotonRegistrar . ' ' . $strAddBotonEditar . ' ' . $strAddBotonEliminar . ' ' . $strAddBotonCancelar ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php


$this->registerJs("
        var fieldsChanged = false;
        $('#formFindKeys').change(function() {
          findManualKeys();
        });
");

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
         var wrapper = document.getElementById("signature-pad");
         '
);

$this->registerCss(".signature-pad--actions{ display:none; } ");
$strAction = isset($action) && !empty($action) ? $action : '';
if (!empty($model->id)) {
    $this->registerJs(
        "$('document').ready(function(){ 
             fnLoadRegistro($model->id,'$strAction');
         });"
    );
}

$this->registerJs(
    <<<JS
    const strUrl = '$url_registro_create';
    const strUrlList = '$url_registro_list';
    const strAjaxRegisterMotion = '$ajax_register_motion';
    const strAjaxUpdateMotion = '$ajax_update_motion';
    const strAjaxDeleteMotion = '$ajax_delete_motion';
    const strAjaxAddKey = '$ajax_add_key';
    const strAjaxFindKeys = '$ajax_find_keys';
    const strAjaxFindManual = '$ajax_find_manual';
    const strAjaxFindComercial = '$ajax_find_comercial';
    
    
JS
    , $this::POS_HEAD);
?>
