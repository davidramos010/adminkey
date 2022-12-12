<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */
/* @var $notificacion string */

use kartik\password\PasswordInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile("@web/css/site.css", []);
$this->registerCssFile("@web/css/login.css", []);
?>


<div class="login-page" style="height: 90vh !important;">

    <div class="login-box">
        <div class="login-logo">
            <b>Admin</b>KEYS
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Iniciar sesion.</p>
                <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['autocomplete' => 'off']]) ?>
                <?= $form->field($model, 'perfil')->hiddenInput(['value' => 1])->label(false); //Por defecto user simple ?>
                <div class="h-100 d-flex align-items-center justify-content-center">
                    <div class="row">
                        <div id="divLoginUser" class="small-box bg-success" >
                            <div class="inner" >
                                <h5>Acceso Basico</h5>
                                <div class="btn-group-vertical" role="group" style="margin: 1%;">
                                    <div class="btn-group text-center mb-2 form-group">
                                        <?=
                                            $form->field($model, 'authkey')->widget(PasswordInput::class, [
                                                'id' => 'authkey',
                                                'name' => 'authkey',
                                                'language' => 'es',
                                                'pluginOptions' => ['value'=>'','showMeter' => false],
                                                'options' => ['class' => 'form-group has-feedback', 'id' => 'authkey', 'autocomplete' => 'off', 'placeholder' => $model->getAttributeLabel('Codigo Acceso'),'template' => '{beginWrapper}{input}{error}{endWrapper}','wrapperOptions' => ['class' => 'input-group mb-5']]
                                            ])->label( false);
                                       ?>
                                    </div>
                                    <div class="btn-group ">
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(1)">1</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(2)">2</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(3)">3</button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(4);">4</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(5);">5</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(6);">6</button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(7);">7</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(8);">8</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(9);">9</button>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin('-');"><</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin(0);">0</button>
                                        <button type="button" class="btn btn-light" onclick="fnAddNumerLogin('*');">..</button>
                                    </div>
                                </div>
                                <p class="d-flex align-items-center justify-content-center;" style="padding-top: 10px">
                                    <?= Html::submitButton('Iniciar', ['value' => 1,'name' => 'authkey','class' => 'btn btn-light btn-block']) ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer" onclick="fnVerAdmin()">Administrador <i class="fas fa-arrow-circle-right"></i></a>
                        </div>

                        <div id="divLoginAdmin" class="small-box bg-gray-dark" style="display: none;">
                            <div class="inner">
                                <h5>Acceso Administrador</h5>
                                <?= $form->field($model,'username', [
                                    'options' => ['class' => 'form-group has-feedback','autocomplete' => 'off'],
                                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                                    ->label(false)
                                    ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

                                <?= $form->field($model, 'password', [
                                    'options' => ['class' => 'form-group has-feedback','autocomplete' => 'off'],
                                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                                    ->label(false)
                                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                                <p class="d-flex align-items-center justify-content-center">
                                    <?= Html::submitButton('Iniciar', ['value' => 1,'name' => 'admin','class' => 'btn btn-light btn-block']) ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" onclick="fnVerUser()" class="small-box-footer">Usuario <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
    <!-- /.login-card-body -->
    <div class=" h-100 d-flex align-items-center justify-content-center" >
        <?= Html::img('@web/img/empresa.jpg', ['width' => 320, 'alt' => 'Empresa']); ?><br>
    </div>
<?php

if(!empty($notificacion)){
    $this->registerJs(
        " toastr.error('".trim($notificacion)."'); "
    );
}

$this->registerJsFile('@web/js/login.js');

?>