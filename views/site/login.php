<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\LoginForm */
/* @var $notificacion string */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile("@web/css/site.css", []);
?>
<div class="login-page" style="height: 90vh !important;">

    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>Admin</b>KEYS</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Iniciar sesion.</p>

                <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>
                <?= $form->field($model, 'perfil')->hiddenInput()->label(false); ?>

                <div class="h-100 d-flex align-items-center justify-content-center">
                    <div class="row">
                        <div id="divLoginUser" class="small-box bg-success" >
                            <div class="inner" >
                                <?= $form->field($model, 'authkey', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-5']
                                ])
                                    ->label(false)
                                    ->passwordInput(['placeholder' => $model->getAttributeLabel('Codigo Acceso')]) ?>
                                <p class="d-flex align-items-center justify-content-center">
                                    <?= Html::submitButton('Iniciar', ['class' => 'btn btn-light btn-block']) ?>
                                </p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer" onclick="fnVerAdmin()">Administrador <i class="fas fa-arrow-circle-right"></i></a>
                        </div>

                        <div id="divLoginAdmin" class="small-box bg-cyan" style="display: none;">
                            <div class="inner">
                                <h5>Acceso Administrador</h5>
                                <?= $form->field($model,'username', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-5']
                                ])
                                    ->label(false)
                                    ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

                                <?= $form->field($model, 'password', [
                                    'options' => ['class' => 'form-group has-feedback'],
                                    'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                                    'template' => '{beginWrapper}{input}{error}{endWrapper}',
                                    'wrapperOptions' => ['class' => 'input-group mb-3']
                                ])
                                    ->label(false)
                                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                                <p class="d-flex align-items-center justify-content-center">
                                    <?= Html::submitButton('Iniciar', ['class' => 'btn btn-light btn-block']) ?>
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
        " toastr.error('".$notificacion."'); "
    );
}

$this->registerJsFile('@web/js/login.js');

?>