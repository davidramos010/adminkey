<?php

use app\models\Registro;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="registro-form">

    <div id="div_msm" style="display:none" class="callout callout-success">
        <h5><i class="fas fa-info-circle"></i> OK</h5>
        Registro almacenado correctamente.
    </div>

    <div id="div_info" class="callout callout-info" >
        <h5><i class="fas fa-info"></i> Note:</h5>
        Este registro estara asociado al usuario en sesion <label class="exampleInputBorder">( <?= Yii::$app->user->identity->username ?> ) </label>.<br/>
        Las llaves se irán registrando segun su último estado de disponibilidad.
    </div>
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Registrar</h3>
            </div>
            <!-- /.card-header -->

            <!-- .ini table -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">

                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <h3 class="card-title">
                        <div class="row">
                            <div class="col-md-5">
                                <?= Html::textInput('id_llave', '', ['id'=>'id_llave','class'=>'form-control']); ?>
                                <?= Html::hiddenInput('id_operacion', 'E', ['id'=>'id_operacion','class'=>'form-control']); ?>
                            </div>
                            <div class="col-md-7">
                                <?= Html::button('Adicionar', [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { addKey() })();' ]); ?>
                            </div>
                        </div>
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-entrada-tab" data-toggle="pill" href="#custom-tabs-entrada" role="tab" aria-controls="custom-tabs-entrada" aria-selected="true" onclick="fnSetOperacion('E')">Entrada</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-salida-tab" data-toggle="pill" href="#custom-tabs-salida" role="tab" aria-controls="custom-tabs-salida" aria-selected="false" onclick="fnSetOperacion('S')">Salida</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-entrada" role="tabpanel" aria-labelledby="custom-tabs-entrada-tab">
                            <table id="tblKeyEntrada" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Registro</th>
                                    <th style="width: 20%">Código</th>
                                    <th style="width: 30%">Descripción</th>
                                    <th style="width: 30%">Comunidad</th>
                                    <th style="width: 10%">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Cuerpo -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="5">Registro de Entrada de llaves</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-salida" role="tabpanel" aria-labelledby="custom-tabs-salida-tab">
                            <table id="tblKeySalida" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Registro</th>
                                    <th style="width: 20%">Código</th>
                                    <th style="width: 30%">Descripción</th>
                                    <th style="width: 30%">Comunidad</th>
                                    <th style="width: 10%">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Cuerpo -->
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="5">Registro de Salidda de llaves</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>


            <!-- form start -->
            <div class="card-body">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <?= $form->field($model, 'id_comercial')->dropDownList(Registro::getComercialesDropdownList(), ['id' => 'id_comercial','class' => 'form-control', 'prompt' => 'Seleccione Uno'])->label('Comerciales'); ?>
                        <?= $form->field($model, 'observacion')->textArea(['id' => 'txt_observacion', 'class' => 'form-control', 'style' => 'width:100%'])->label('Observaciones') ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <div style="padding-top: 15px">
                    <?= Html::button('Registrar Movimiento', ['id' => 'btn_registrar', 'class' => 'btn btn-success', 'onclick' => '(function ( $event ) { sendForm() })();']); ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(
    '$("document").ready(function(){ 
         $("#id_llave").keypress(function(event) {
            if (event.keyCode === 13) {
                addKey();
            }
        });
     });'
); ?>
