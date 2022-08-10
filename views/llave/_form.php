<?php

use app\models\Llave;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="llave-form">

    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Llaves</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'id_comunidad')->dropDownList(Llave::getComunidadesDropdownList() , ['prompt' => 'Seleccione Uno' ])->label('Comunidad'); ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'id_tipo')->dropDownList(Llave::getTipoLlaveDropdownList() , ['prompt' => 'Seleccione Uno' ])->label('Tipo'); ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'copia')->textInput(['maxlength' => true,'class'=>'form-control', ''])->label('Numero de copias') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true,'class'=>'form-control'])->label('codigo') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true,'class'=>'form-control'])->label('descripcion') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true,'class'=>'form-control'])->label('observacion') ?>
                </div>
                <div  style="padding-top: 15px" >
                    <?= Html::submitButton('Guardar Comunidad', ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>


</div>
