<?php

use app\models\Llave;
use app\models\LlaveSearch;
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
                    'options' => [
                        'data-pjax' => 1
                    ],
                ]); ?>
                <div class="card">
                    <div class="card-body login-card-body">
                        <div class="row">
                            <div class="col-2">
                                <?= $form->field($model, 'codigo')->label('Cod Llave') ?>
                            </div>
                            <div class="col-5">
                                <?= $form->field($model, 'descripcion')->label('Descripción') ?>
                            </div>
                            <div class="col-5">
                                <?= $form->field($model, 'comercial')->dropDownList(Llave::getComunidadesDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'data-js-find-nomenclatura' => 'comunidad'])->label(Yii::t('app', 'Empresa').'/'.Yii::t('app', 'Proveedor')); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <?= $form->field($model, 'nomenclatura')->textInput(['maxlength' => true])->label('Cod Cliente') ?>
                            </div>
                            <div class="col-5">
                                <?= $form->field($model, 'id_comunidad')->dropDownList(Llave::getComunidadesDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'data-js-find-nomenclatura' => 'comunidad'])->label(Yii::t('app', 'Comunidad')); ?>
                            </div>
                            <div class="col-5">
                                <?= $form->field($model, 'id_propietario')->dropDownList(Llave::getPropietariosDropdownList(), ['id' => 'id_propietario', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'data-js-find-nomenclatura' => 'propietario'])->label(Yii::t('app', 'Propietario')); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="btn-toolbar kv-grid-toolbar toolbar-container float-right btn-sm" >
                                    <div class="btn-group" >
                                        <?= Html::button('<i class="fas fa-search"></i> '.Yii::t('app', 'Buscar'), ['class' => 'btn bg-orange btn-sm', 'title'=>Yii::t('app', 'Buscar'),'id' => 'find-keys-button', 'name' => 'find-keys-button', 'onclick' => '(function ( $event ) { findManualKeys() })();']) ?>
                                        <?= Html::button('<i class="fas fa-undo"></i> '.Yii::t('app', 'Limpiar'), ['id' => 'btn_cancelar_modal_comunidad', 'title'=>Yii::t('app', 'Reiniciar Formulario'), 'class' => 'btn btn-default btn-sm', 'onclick' => 'this.form.reset()']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 300px;">
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

