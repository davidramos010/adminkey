<?php

use app\models\User;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $model_info app\models\UserInfo */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerJsFile('@web/js/usuarios.js');

?>

<div class="user-form">

    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php $form = ActiveForm::begin(['id' => 'formUser']); ?>
            <div class="card-body">

                <div class="form-group">
                    <?= $form->field($model, 'id')->hiddenInput(['id'=>'id'])->label(false); ?>
                    <?= $form->field($model, 'username')->textInput(['id'=>'username', 'maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'width:40%'])->label('*Username') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'nombres')->textInput(['id'=>'nombres','maxlength' => true,'class'=>'form-control','autocomplete'=>'off'])->label('*Nombres') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'apellidos')->textInput(['id'=>'apellidos','maxlength' => true,'class'=>'form-control','autocomplete'=>'off'])->label('*Apellidos') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'telefono')->textInput(['id'=>'telefono','inputmode'=>'text' ,'maxlength' => 10,'class'=>'form-control','autocomplete'=>'off','style'=>'width:40%'])->label('Teléfono') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'email')->textInput(['id'=>'email','maxlength' => true,'class'=>'form-control','autocomplete'=>'off'])->label('Email') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control','autocomplete'=>'off'])->label('Dirección') ?>
                </div>
                <div class="form-group">
                    <?php
                    // Usage with ActiveForm and model
                    echo  $form->field($model_info, 'estado')->widget(SwitchInput::class, ['pluginOptions'=>['size'=>'small','onText'=>'Activo','offText'=>'Inactivo']])->label('Estado') ;
                    ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'password')->hiddenInput(['id'=>'password'])->label(false); ?>
                    <?= $form->field($model, 'password_new')->passwordInput(['id'=>'password_new','maxlength' => true,'class'=>'form-control','value' =>'','autocomplete'=>'off'])->label('Password') ?>
                </div>
                <div class="form-group">
                    <?= $form->field($model, 'authKey')->hiddenInput(['id'=>'authKey'])->label(false); ?>
                    <?= $form->field($model, 'authKey_new')->passwordInput(['id'=>'authKey_new','maxlength' => true,'class'=>'form-control','value' =>'','autocomplete'=>'off'])->label('AuthKey') ?>
                </div>

                <div class="form-group">
                    <?= $form->field($model, 'idPerfil')->dropDownList(User::getPerfilesDropdownList() , ['id'=>'idPerfil','class'=>'form-control','prompt' => 'Seleccione Perfil', 'value' => (isset($model->perfiluser) && !empty($model->perfiluser))?$model->perfiluser->id_perfil:null ])->label('*Perfil'); ?>
                </div>

                <div style="padding-top: 15px" >
                    <?= Html::button(Yii::t('yii', 'Save'), [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnSubmit() })();' ]); ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>