<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Propietarios */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Propietarios'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a(Yii::t('app', 'Volver'), ['index'], ['class' => 'btn btn-default ']) ?>
                        <?php /*Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) */?>
                    </p>
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="card-body">

                        <div class="row card-header text-muted border-bottom-0"> <i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Datos Propietario</h3> </div>
                        <div class="row">
                            <div class="col-md-12 " >
                                <div class="line"></div>
                                <?= $form->field($model, 'nombre_propietario')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Nombre Propietario') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 " >
                                <?= $form->field($model, 'tipo_documento_propietario')->textInput(['value'=>(!empty($model->tipo_documento_propietario))?$model->arrTipoDocumentos[ $model->tipo_documento_propietario ]:'', 'maxlength' => true,'class'=>'form-control','readonly' => true])->label('Documento Propietario') ?>
                            </div>
                            <div class="col-md-8 "  >
                                <?= $form->field($model, 'documento_propietario')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Documento Propietario') ?>
                            </div>
                        </div>

                        <div class="row card-header text-muted border-bottom-0"> <i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Datos Representante</h3> </div>
                        <div class="row">
                            <div class="col-md-12 " >
                                <div class="line"></div>
                                <?= $form->field($model, 'nombre_representante')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Nombre Representante') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 " >
                                <?= $form->field($model, 'tipo_documento_representante')->textInput(['value'=>(!empty($model->tipo_documento_representante))?$model->arrTipoDocumentos[$model->tipo_documento_representante]:'', 'maxlength' => true,'class'=>'form-control','readonly' => true])->label('Documento Propietario') ?>
                            </div>
                            <div class="col-md-8 "  >
                                <?= $form->field($model, 'documento_representante')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Documento Representante') ?>
                            </div>
                        </div>
                        <div class="row card-header text-muted border-bottom-0"><i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp; <h3 class="card-title">Info. Contacto</h3> </div>
                        <div class="row">
                            <div class="col-md-4 " >
                                <?= $form->field($model, 'cod_postal')->textInput(['maxlength' => true, 'class' => 'form-control','readonly' => true])->label('Cod Postal') ?>
                            </div>
                            <div class="col-md-8 "  >
                                <?= $form->field($model, 'poblacion')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Población') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 "  >
                                <?= $form->field($model, 'direccion')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Dirección') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 " >
                                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Teléfono') ?>
                            </div>
                            <div class="col-md-6 "  >
                                <?= $form->field($model, 'movil')->textInput(['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Movil') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 "  >
                                <?= $form->field($model, 'email')->textInput (['maxlength' => true,'class'=>'form-control','readonly' => true])->label('Correo Electronico') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 "  >
                                <?= $form->field($model, 'observaciones')->textArea(['id' => 'observaciones', 'class' => 'form-control', 'style' => 'width:100%', 'readonly' => true])->label('Notas/Observaciones') ?>
                            </div>
                        </div>

                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>