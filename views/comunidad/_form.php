<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comunidad-form">

    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Comunidad</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?php $form = ActiveForm::begin(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Nombre') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Dirección') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'telefono1')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Telefono 1') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'telefono2')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Telefono 2') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'contacto')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Persona/Contacto') ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($model, 'nomenclatura')->textInput(['maxlength' => true,'class'=>'form-control'])->label('Nomenclatura/Codigo') ?>
                    </div>
                    <div  style="padding-top: 15px" >
                        <?= Html::submitButton('Guardar Comunidad', ['class' => 'btn btn-success ']) ?>
                        <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
    <!-- /.card -->

</div>


