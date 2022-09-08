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

<div class="user-form col-md-9">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <?php $form = ActiveForm::begin(['id' => 'formUser']); ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'id')->hiddenInput(['id'=>'id'])->label(false); ?>
                        <?= $form->field($model, 'username')->textInput(['id'=>'username', 'maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'width:40%','readonly'=>!$model->isNewRecord])->label('*Username') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model_info, 'nombres')->textInput(['id'=>'nombres','maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'text-transform: uppercase'])->label('*Nombres') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model_info, 'apellidos')->textInput(['id'=>'apellidos','maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'text-transform: uppercase'])->label('*Apellidos') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model_info, 'telefono')->textInput(['id'=>'telefono','inputmode'=>'text' ,'maxlength' => 10,'class'=>'form-control','autocomplete'=>'off'])->label('Teléfono') ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model_info, 'email')->textInput(['id'=>'email','maxlength' => true,'class'=>'form-control','autocomplete'=>'off'])->label('Email') ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'text-transform: uppercase'])->label('Dirección') ?>
                </div>
                <div class="row">
                    <div class="col-md-3">

                        <?= $form->field($model, 'password_new')->passwordInput(['id'=>'password_new','maxlength' => true,'class'=>'form-control','value' =>'','autocomplete'=>'off'])->label('Password') ?>
                    </div>
                    <div class="col-md-3">

                        <?= $form->field($model, 'authKey_new')->passwordInput(['id'=>'authKey_new','maxlength' => true,'class'=>'form-control','value' =>'','autocomplete'=>'off'])->label('AuthKey') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'idPerfil')->dropDownList(User::getPerfilesDropdownList() , ['id'=>'idPerfil','class'=>'form-control','prompt' => 'Seleccione Perfil','value'=> (isset($model->perfiluser))?$model->perfiluser->id_perfil:null ])->label('Perfil'); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model_info, 'codigo')->textInput(['maxlength' => true,'class'=>'form-control','autocomplete'=>'off','style'=>'text-transform: uppercase'])->label('Cod. Interno') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model_info, 'estado')->widget(SwitchInput::class, ['pluginOptions'=>['size'=>'small','onText'=>'Activo','offText'=>'Inactivo']])->label('Estado') ; ?>
                    </div>
                </div>
                <div style="padding-top: 15px" >
                    <?= $form->field($model, 'password')->hiddenInput(['id'=>'password'])->label(false); ?>
                    <?= $form->field($model, 'authKey')->hiddenInput(['id'=>'authKey'])->label(false); ?>
                    <?= Html::button(Yii::t('yii', 'Gurdar Usuario'), [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnSubmit() })();' ]); ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

</div>
