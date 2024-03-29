<?php

use app\models\User;
use app\models\util;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $model_info app\models\UserInfo */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerJsFile('@web/js/usuarios.js');
$this->registerJsFile('@web/js/tools.js');
$strDisplayPass = (isset($model->perfiluser) && $model->perfiluser->id_perfil==1 && !$model->isNewRecord) ? 'inline':'none';
$strDisplayAuth = (isset($model->perfiluser) && $model->perfiluser->id_perfil>=2 && !$model->isNewRecord)  ? 'inline':'none';
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
            <?= $form->field($model, 'id')->hiddenInput(['id' => 'id'])->label(false); ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <?= $form->field($model_info, 'tipo_documento')->dropDownList(util::arrTipoDocumentos, ['class' => 'form-control', 'prompt' => 'Seleccione Uno'])->label('Tipo Doc.'); ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model_info, 'documento')->textInput(['maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'onblur' => '(function ( $event ) { validateDocumento() })();' ])->label('Documento Identidad') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model_info, 'nombres')->textInput(['id' => 'nombres', 'maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'])->label('*Nombres') ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model_info, 'apellidos')->textInput(['id' => 'apellidos', 'maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase', 'onblur' => '(function ( $event ) { getNameUserGenerate() })();'])->label('*Apellidos') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model_info, 'telefono')->textInput(['id' => 'telefono', 'inputmode' => 'text', 'maxlength' => 10, 'class' => 'form-control', 'autocomplete' => 'off'])->label('Teléfono') ?>
                    </div>
                    <div class="col-md-9">
                        <?= $form->field($model_info, 'email')->textInput(['id' => 'email', 'maxlength' => 254, 'class' => 'form-control', 'autocomplete' => 'off'])->label('Email') ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= $form->field($model_info, 'direccion')->textInput(['maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'style' => 'text-transform: uppercase'])->label('Dirección') ?>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'username')->textInput(['id' => 'username', 'maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'style' => ' text-transform: uppercase', 'readonly' => !$model->isNewRecord])->label('*Username') ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'idPerfil')->dropDownList(User::getPerfilesDropdownList(), ['id' => 'idPerfil', 'class' => 'form-control', 'prompt' => 'Seleccione Perfil', 'value' => (isset($model->perfiluser)) ? $model->perfiluser->id_perfil : null, 'onchange' => '(function ( $event ) { fnDisplayDivInput() })();'])->label('Perfil'); ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model_info, 'estado')->widget(SwitchInput::class, ['pluginOptions' => ['size' => 'small', 'onText' => 'Activo', 'offText' => 'Inactivo']])->label('Estado'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" id="div_input_password_new" style="display: <?= $strDisplayPass ?>">
                        <?= $form->field($model, 'password_new')->passwordInput(['id' => 'password_new', 'maxlength' => true, 'class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => Yii::t('app','Minimo 6 caracteres.')])->label('Password') ?>
                    </div>
                    <div class="col-md-4" id="div_input_authKey_new" style="display:  <?= $strDisplayAuth ?>">
                        <?= $form->field($model, 'authKey_new')->passwordInput(['id' => 'authKey_new', 'maxlength' => true, 'class' => 'form-control', 'value' => '', 'autocomplete' => 'off', 'placeholder' => Yii::t('app','Minimo 6 números.'), 'onkeypress' => '(function ( $event ) { validateNumber() })();', 'onblur' => '(function ( $event ) { valideteKey() })();' ])->label('AuthKey') ?>
                    </div>
                </div>
                <h6 class="text-muted" ><?= Yii::t('app','titulo_usuario_relacion_comercial') ?></h6>
                <div class="row">
                    <div class="col-md-12">
                        <?=  $form->field($model_info, 'id_comercial')->widget(Select2::class, [
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'size' => Select2::SMALL,
                                'data' => !empty($model_info->id_comercial) ? [$model_info->id_comercial=>$model_info->comercial->nombre] : [],
                                'options' => [
                                    'data-js-req-cont' => 'generic',
                                    'id' => 'id_comercial'
                                ],
                                'pluginOptions' => [
                                    'minimumInputLength' => 4,
                                    'language' => [
                                        'inputTooShort' => new JsExpression("() => 'Escríbe 4 caracteres mínimo. Puedes buscar por empresa.'"),
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
                        )->label('Empresa/Proveedor'); ?>
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
