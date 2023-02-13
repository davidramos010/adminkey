<?php

use app\models\Llave;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->registerJsFile('@web/js/registro.js');
$model = new Llave();
?>
<!-- form start -->
<!-- info modal -->
<div class="modal fade" id="modal-llave-manual" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('app', 'Selección manual de llaves') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <?php $form = ActiveForm::begin([
                            'action' => ['index'],
                            'method' => 'get',
                            'options' => [
                                'data-pjax' => 1
                            ],
                        ]); ?>

                        <?= $form->field($model, 'id') ?>

                        <?= $form->field($model, 'id_comunidad') ?>

                        <?= $form->field($model, 'id_propietario') ?>

                        <?= $form->field($model, 'tipo') ?>

                        <?= $form->field($model, 'codigo') ?>

                        <?php // echo $form->field($model, 'observacion') ?>

                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>



                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap" id="dataTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 3%">X</th>
                                        <th style="width: 7%"><?= Yii::t('app','Codigo')?></th>
                                        <th style="width: 20%"><?= Yii::t('app','Comunidad')?></th>
                                        <th style="width: 20%"><?= Yii::t('app','Propietario')?></th>
                                        <th style="width: 10%"><?= Yii::t('app','Tipo')?></th>
                                        <th style="width: 10%"><?= Yii::t('app','Estado')?></th>
                                        <th style="width: 30%"><?= Yii::t('app','Descripción')?></th>
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
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app','Cerrar')?></button>
                <?= Html::button(Yii::t('app','Seleccionar'), [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnExcelReport("dataTable") })();' ]); ?>
            </div>
        </div>
    </div>
</div>
<!-- form end -->

