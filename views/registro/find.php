<?php

use app\models\Comerciales;
use app\models\Llave;
use app\models\LlaveSearch;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('@web/js/registro.js');
$model = new LlaveSearch();
?>
<!-- form start -->
<!-- info modal -->
<div class="modal fade" id="modal-llave-manual" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><?= Yii::t('app', 'Selección manual de llaves') ?></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'formFindKeys',
                    'method' => 'post',
                    'class' => 'form-horizontal',
                    'options' => [
                        'data-pjax' => 1,
                        'class' => 'form-horizontal'
                    ],
                ]); ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'id_comunidad')->widget(Select2::class, [
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'size' => Select2::SMALL,
                                        'data' => !empty($model->id_comunidad) ? [$model->id_comunidad => $model->comunidad->nombre] : [],
                                        'options' => [
                                            'id' => 'llavesearch-id_comunidad'
                                        ],
                                        'pluginOptions' => [
                                            'minimumInputLength' => 4,
                                            'language' => [
                                                'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por comunidad.'"),
                                                'errorLoading' => new JsExpression("() => 'Buscando...'"),
                                            ],
                                            'ajax' => [
                                                'url' => Url::to(['registro/find-comunidad']),
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
                                )->label(Yii::t('app', 'Comunidad')); ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'id_propietario')->widget(Select2::class, [
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'size' => Select2::SMALL,
                                        'data' => !empty($model->id_comunidad) ? [$model->id_comunidad => $model->comunidad->nombre] : [],
                                        'options' => [
                                            'id' => 'llavesearch-id_propietario'
                                        ],
                                        'pluginOptions' => [
                                            'minimumInputLength' => 4,
                                            'language' => [
                                                'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por Propietario.'"),
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
                            <div class="col-md-4">
                                <?= $form->field($model, 'comercial')->widget(Select2::class, [
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'size' => Select2::SMALL,
                                        'data' => !empty($model->id_comercial) ? [$model->id_comercial => $model->comerciales->nombre] : [],
                                        'options' => [
                                            'data-js-find-nomenclatura' => 'comercial',
                                            'id' => 'llavesearch-comercial'
                                        ],
                                        'pluginOptions' => [
                                            'minimumInputLength' => 4,
                                            'language' => [
                                                'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por comercial.'"),
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
                                )->label(Yii::t('app', 'Empresa').'/'.Yii::t('app', 'Proveedor')); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">
                                <?= $form->field($model, 'codigo')->label('Cod Llave') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'descripcion')->label('Descripción') ?>

                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'responsable')->textInput(['maxlength' => true])->label('Responsable') ?>
                            </div>
                            <div class="col-md-2">
                                <?= $form->field($model, 'llaveLastStatus')->dropDownList(['S'=>Yii::t('app', 'Solo Prestadas'),'E'=>Yii::t('app', 'Solo Almacenadas')], ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'data-js-find-nomenclatura' => 'comunidad'])->label(Yii::t('app', 'Estado')); ?>
                            </div>
                            <div class="col-md-3" style="padding-top: 3%">
                                <div class="btn-toolbar kv-grid-toolbar toolbar-container float-right btn-sm" >
                                    <div class="btn-group" >
                                        <?= Html::button('<i class="fas fa-search"></i> '.Yii::t('app', 'Buscar'), ['class' => 'btn bg-orange btn-sm', 'title'=>Yii::t('app', 'Buscar'),'id' => 'find-keys-button', 'name' => 'find-keys-button', 'onclick' => '(function ( $event ) { findManualKeys() })();']) ?>
                                        <?= Html::button('<i class="fas fa-undo"></i> '.Yii::t('app', 'Limpiar'), ['id' => 'btn_cancelar_modal_comunidad', 'title'=>Yii::t('app', 'Reiniciar Formulario'), 'class' => 'btn btn-default btn-sm', 'onclick' => '(function ( $event ) { findManualKeysReset() })();']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 500px;">
                                <table class="table table-head-fixed text-nowrap" id="dataTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 3%"></th>
                                        <th style="width: 7%"><?= Yii::t('app','Cod')?></th>
                                        <th style="width: 20%"><?= Yii::t('app','Comunidad').'/'.Yii::t('app','Propietario')?></th>
                                        <th style="width: 20%"><?= Yii::t('app','Descripción')?></th>
                                        <th style="width: 10%"><?= Yii::t('app','Tipo')?></th>
                                        <th style="width: 10%"><?= Yii::t('app','Estado')?></th>
                                        <th style="width: 15%"><?= Yii::t('app','Empresa').'/'.Yii::t('app','Proveedor')?></th>
                                        <th style="width: 15%"><?= Yii::t('app','Responsable')?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="modal-llaves-contenido-table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary float-right" data-dismiss="modal"><?= Yii::t('app','Cerrar')?></button>
            </div>
        </div>
    </div>
</div>
<!-- form end -->

