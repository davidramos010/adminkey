<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comerciales-form">

    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Comerciales</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?php $form = ActiveForm::begin(); ?>
            <div class="card-body">
                <div class="form-group">
                    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Nombre') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Teléfono') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'contacto')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Persona/Contacto') ?>
                </div>
                <div  style="padding-top: 15px" >
                    <?= Html::submitButton('Guardar Empresa', ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>

</div>
