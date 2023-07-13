<?php

use app\models\Llave;
use app\models\LlaveNotas;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $form yii\widgets\ActiveForm */
/* @var $llaveNota  LlaveNotas */
/* @var $model  Llave */


?>
<!-- form start -->
<!-- info modal -->
<div class="modal fade" id="modal-addnota" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><?= Yii::t('app', 'Adicionar Notas') ?></h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <?php $form = ActiveForm::begin(['id' => 'form-nota', 'enableClientValidation' => true, 'enableAjaxValidation' => false]); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <?= $form->field($llaveNota, 'nota')->textArea(['id' => 'form-nota-nota','class' => 'form-control'])->label(false) ?>
                                    <?= $form->field($model, 'id')->hiddenInput(['id'=>'form-nota-llave'])->label(false); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($llaveNota, 'id_user')->textInput(['class' => 'form-control','disabled'=>true])->label(Yii::t('app', 'Usuario')) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($llaveNota, 'created')->textInput(['id'=>'form-nota-created', 'class' => 'form-control','disabled'=>true])->label(Yii::t('app', 'Fecha')) ?>
                                </div>
                            </div>
                            <?= Html::button(Yii::t('app', 'Guardar Cliente'), ['class' => 'btn btn-success ', 'data-js-set-nota' => true,]); ?>
                            <?= Html::button(Yii::t('app', 'Cancelar'), ['id' => 'btn_cancelar_modal_notas', 'class' => 'btn btn-default ', 'data-dismiss' => 'modal']); ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- form end -->

