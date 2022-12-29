<?php

use app\models\Llave;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */
/* @var $form yii\widgets\ActiveForm */
/* @var $view bool */

?>
<div class="llave-form">
    <div class="row">
        <div class="col-md-12">
            <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                <?php
                echo Html::ul([
                    'El código se genera automáticamente al momento de seleccionar la comunidad.',
                    'El sistema creará tantas llaves como copias existan.',
                ], ['encode' => false]);
                ?>
            </div>
        </div>
    </div>

    <!-- form start -->
    <?= $this->render('info_comunidad') ?>
    <?= $this->render('info_propietario') ?>
    <!-- form end -->

    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Llaves</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 ">
                    <?= $form->field($model, 'id_tipo')->dropDownList(Llave::getTipoLlaveDropdownList(), ['onchange' => 'fnTipoLlaveSelected()', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view])->label('Tipo'); ?>
                </div>
            </div>
            <div class="row" id="divFormComunidad" style="display: none">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'id_comunidad')->dropDownList(Llave::getComunidadesDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view, 'data-js-find-nomenclatura'=>'comunidad'])->label('Comunidad'); ?>
                </div>
                <div class="col-md-1 ">
                    <div class="form-group field-id_comunidad_modal">
                        <label class="control-label" for="id_comunidad_modal">Adicionar</label><br/>
                        <?= Html::a('<i class="fas fa-info-circle"></i>', ['comunidad/create-modal'], ['class' => 'btn btn-success', 'id' => 'btn-modal-comunidad', 'title' => Yii::t('app', 'Nueva Comunidad')]); ?>
                    </div>
                </div>
            </div>
            <div class="row" id="divFormPropietario" style="display: none">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'id_propietario')->dropDownList(Llave::getPropietariosDropdownList(), ['id' => 'id_propietario', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view, 'data-js-find-nomenclatura'=>'propietario'])->label('Propietario'); ?>
                </div>
                <div class="col-md-1" style="vertical-align: bottom">
                    <div class="form-group field-id_propietario_modal">
                        <label class="control-label" for="id_propietario_modal">Adicionar</label><br/>
                        <?= Html::a('<i class="fas fa-info-circle"></i>', ['propietarios/create-modal'], ['class' => 'btn btn-success', 'id' => 'btn-modal-propietario', 'title' => Yii::t('app', 'Nuevo Propietario')]); ?>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6 ">
                    <?= $form->field($model, 'id_llave_ubicacion')->dropDownList(Llave::getUbicacionDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view])->label('Ubicación Almacenamiento'); ?>
                </div>
            </div>
            <div class="row">
                <?php if (!$view): ?>
                    <div class="col-md-1 ">
                        <?= $form->field($model, 'nomenclatura')->textInput(['id' => 'llave-nomenclatura', 'maxlength' => true, 'class' => 'form-control', 'readonly' => true])->label('_') ?>
                    </div>
                <?php endif; ?>
                <div class="col-md-3 ">
                    <?= $form->field($model, 'codigo')->textInput(['id' => 'llave-codigo', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view])->label('Código') ?>
                </div>
                <div class="col-md-2 ">
                    <?php if (!$view): ?>
                        <?= $form->field($model, 'alarma')->widget(SwitchInput::class, ['id' => 'alarma', 'pluginOptions' => ['id' => 'alarma', 'size' => 'small', 'onText' => 'SI', 'offText' => 'NO'], 'pluginEvents' => ["switchChange.bootstrapSwitch" => "function(item) { if($(item.currentTarget).is(':checked')){ $('#codigo_alarma').val('').prop('readonly', false); }else{ $('#codigo_alarma').val('').prop('readonly', true);} }"]])->label('Alarma'); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'alarma')->textInput(['id' => 'alarma', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view, 'value' => ($model->alarma) ? 'SI' : 'NO'])->label('Alarma') ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 ">
                    <?= $form->field($model, 'codigo_alarma')->textInput(['id' => 'codigo_alarma', 'maxlength' => true, 'class' => 'form-control', 'readonly' => true])->label('Código Alarma') ?>
                </div>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, 'class' => 'form-control', 'readonly' => $view])->label('Descripción') ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'observacion')->textArea(['class' => 'form-control', 'readonly' => $view])->label('Observaciones') ?>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'copia')->textInput(['type' => 'number', 'maxlength' => 2, 'class' => 'form-control', 'readonly' => !$model->isNewRecord])->label('Número de copias') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 ">
                    <?php if (!$view): ?>
                        <?= $form->field($model, 'facturable')->widget(SwitchInput::class, ['id' => 'facturable', 'pluginOptions' => ['id' => 'facturable', 'size' => 'small', 'onText' => 'SI', 'offText' => 'NO']])->label('Facturable'); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'facturable')->textInput(['id' => 'facturable', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view, 'value' => ($model->facturable) ? 'SI' : 'NO'])->label('Facturable') ?>
                    <?php endif; ?>

                </div>
            </div>
            <?php if (!$view): ?>
                <div style="padding-top: 15px">
                    <?= Html::submitButton('Guardar Llave', ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php


$this->registerJs(
    '$(document).on("click", "[data-js-find-nomenclatura]", function (e) {
            findCodeLlave();
        });'
);

$this->registerJs(
    '$("#codigo_alarma").on("change", function() {
            activeCodAlarma();
        });'
);
$this->registerJs(
'$("#btn-modal-comunidad").click(function(e){
       e.preventDefault();      
       $("#modal-comunidad").modal("show")
                  .find(".modal-content")
                  .load($(this).attr("href"));  
       }); '
);
$this->registerJs(
    '$("#btn-modal-propietario").click(function(e){
       e.preventDefault();      
       $("#modal-propietario").modal("show")
                  .find(".modal-content")
                  .load($(this).attr("href"));  
       }); '
);
?>
