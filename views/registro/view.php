<?php

use app\models\Registro;
use app\models\util;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */
/* @var $arrInfoStatusE array */
/* @var $arrInfoStatusS array */

$this->registerJsFile('@web/js/registro.js');
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$arrColumns = [[
    'attribute' => 'id',
    'label' => 'Codigo',
    'headerOptions' => ['style' => 'width: 10%'],
    'format' => 'raw',
    'value' => function ($model) {
        return (isset($model->llave->codigo)) ? strtoupper($model->llave->codigo) : '';
    }],
    [
        'attribute' => 'id',
        'label' => 'Descripción',
        'headerOptions' => ['style' => 'width: 40%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->descripcion)) ? strtoupper($model->llave->descripcion) : '';
        }
    ],
    [
        'attribute' => 'id',
        'label' => 'Cliente',
        'headerOptions' => ['style' => 'width: 25%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->comunidad->nombre)) ? strtoupper($model->llave->comunidad->nombre) : '';
        }
    ],
    [
        'attribute' => 'id',
        'label' => 'Propietario',
        'headerOptions' => ['style' => 'width: 25%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->propietarios)) ? strtoupper($model->llave->propietarios->nombre) : '';
        }
    ]];

$bolVisibleGridSalida = $arrInfoStatusS->getTotalCount()>0 ? 'inline' : 'none';
$bolVisibleGridEntrada = $arrInfoStatusE->getTotalCount()>0 ? 'inline' : 'none';

?>
<div class="registro-view">
    <div class="ribbon_wrap">
        <!-- general form elements -->
        <div class="card card-primary" style="display:<?= $bolVisibleGridSalida ?>">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-angle-double-up text-danger"></i> Registros de Salida</h3>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $arrInfoStatusS,
                    'columns' => $arrColumns
                ]) ?>
            </div>
        </div>
        <div class="card card-primary" style="display:<?= $bolVisibleGridEntrada ?>">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-angle-double-down text-success"></i> Registros de Entrada</h3>
            </div>
            <div class="card-body">
                <?= GridView::widget([
                    'dataProvider' => $arrInfoStatusE,
                    'columns' => $arrColumns
                ]) ?>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-tag text-white"></i> Responsable</h3>
            </div>
            <div class="card-body">
                <div class="form-group">

                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-3 ">
                            <?= $form->field($model, 'entrada')->textInput(['id'=>'fecha_registro','maxlength' => true,'class'=>'form-control','readonly' => true, 'value'=> util::getDateTimeFormatedSqlToUser($model->getFechaRegistro()) ])->label('Fecha Registro') ?>
                        </div>
                        <div class="col-md-9 ">
                            <?= $form->field($model, 'id_comercial')->dropDownList(Registro::getComercialesDropdownList(), ['id' => 'id_comercial', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'disabled' => true])->label('Empresa'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 ">
                            <?= $form->field($model, 'tipo_documento')->dropDownList(util::arrTipoDocumentos, ['id' => 'tipo_documento_reponsable', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'disabled' => true])->label('Tipo Documento'); ?>
                        </div>
                        <div class="col-md-2 ">
                            <?= $form->field($model, 'documento')->textInput(['id'=>'documento_reponsable','maxlength' => true,'class'=>'form-control','readonly' => true])->label('Documento') ?>
                        </div>
                        <div class="col-md-6 ">
                            <?= $form->field($model, 'nombre_responsable')->textInput(['id'=>'nombre_responsable','maxlength' => true,'class'=>'form-control','readonly' => true, 'value'=> trim(strtoupper( $model->nombre_responsable )) ])->label('Nombre Responsable') ?>
                        </div>
                        <div class="col-md-3 ">
                            <?= $form->field($model, 'telefono')->textInput(['id' => 'telefono_responsable', 'class' => 'form-control', 'style' => 'width:100%', 'readonly' => true])->label('Teléfono') ?>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <?= $form->field($model, 'observacion')->textArea(['id' => 'txt_observacion', 'class' => 'form-control', 'style' => 'width:100%', 'readonly' => true])->label('Observaciones') ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="form-group">
                    <div class="card text-center">
                        <div class="card-header">
                            <?= Yii::t('app', 'Firma de Aceptación') ?>
                        </div>
                        <div class="card-footer text-muted">

                            <?php if(!empty($model->firma_soporte)):?>
                                <?= Html::img('@web/firmas/'.$model->firma_soporte) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 15px">
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                    <?= Html::button('<i class="fas fa-download"></i> Imprimir', ['id' => 'btn_registrar', 'class' => 'btn btn-primary float-left', 'onclick' => '(function ( $event ) { generatePdfRegistro( ' . $model->id . ' ) })();', 'style' => 'margin-right: 5px;']); ?>
                </div>
            </div>
        </div>
    </div>
</div>
