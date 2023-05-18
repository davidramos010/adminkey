<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<?php

use app\models\Registro;
use app\models\util;
use barcode\barcode\BarcodeGenerator;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */
/* @var $arrInfoStatusE array */
/* @var $arrInfoStatusS array */
/* @var $bolActiveBotonProcess boolean */
/* @var $bolActiveBotonUpdate boolean */



$this->registerJsFile('@web/js/registro.js');
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$arrColumns = [[
    'attribute' => 'id',
    'label' => Yii::t('app', 'Codigo'),
    'headerOptions' => ['style' => 'width: 10%'],
    'format' => 'raw',
    'value' => function ($model) {
        return (isset($model->llave->codigo)) ? strtoupper($model->llave->codigo) : '';
    }],
    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'Descripción'),
        'headerOptions' => ['style' => 'width: 40%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->descripcion)) ? strtoupper($model->llave->descripcion) : '';
        }
    ],
    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'Cliente'),
        'headerOptions' => ['style' => 'width: 25%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->comunidad->nombre)) ? strtoupper($model->llave->comunidad->nombre) : '';
        }
    ],
    [
        'attribute' => 'id',
        'label' => Yii::t('app', 'Propietario'),
        'headerOptions' => ['style' => 'width: 25%'],
        'format' => 'raw',
        'value' => function ($model) {
            return (isset($model->llave->propietarios)) ? strtoupper($model->llave->propietarios->nombre) : '';
        }
    ]];

$bolVisibleGridSalida = $arrInfoStatusS->getTotalCount()>0 ? 'inline' : 'none';
$bolVisibleGridEntrada = $arrInfoStatusE->getTotalCount()>0 ? 'inline' : 'none';
$strHtmlActiveBotonProcess = empty($bolActiveBotonProcess) ? '' : Html::a('<i class="fas fa-key"></i> '.Yii::t('app', 'Ejecutar Devolución'), ['registro/create/'.$model->id], ['target'=>'_blank','id' => 'btn_process', 'class' => 'btn btn-info float-left','style' => 'margin-right: 5px;']);
$strHtmlActiveBotonUpdate = empty($bolActiveBotonUpdate) ? '' : Html::a('<i class="fas fa-key"></i> '.Yii::t('app', 'Editar / Eliminar'), ['registro/update/'.$model->id], ['target'=>'_blank','id' => 'btn_process', 'class' => 'btn btn-warning float-left','style' => 'margin-right: 5px;']);

$optionsArray = array(
    'elementId'=> 'showBarcode', /* div or canvas id*/
    'value'=> strtoupper(str_pad($model->codigo, 8, " ", STR_PAD_LEFT)),  /* value for EAN 13 be careful to set right values for each barcode type */
    'type'=>'code39',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
    'settings'=>array(
        'output'=>'css' /*css, bmp, canvas note- bmp and canvas incompatible wtih IE*/,
        /*if the output setting canvas*/
        'posX' => 10,
        'posY' => 20,
        /* */
        'bgColor'=>'1', /*background color*/
        'color' => '#000', /*"1" Bars color*/
        'barWidth' => 2,
        'barHeight' => 20,
        'fontSize' => 12,
        /*-----------below settings only for datamatrix--------------------*/
        'moduleSize' => 6,
        'addQuietZone' => 0, /*Quiet Zone Modules */
    ),
);

echo BarcodeGenerator::widget($optionsArray);
?>
<!-- Este div almacena la imagen que se transfiere al pdf -->
<div id="copyDiv" style="display: none"></div>
<div class="registro-view">
    <div class="ribbon_wrap">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center" id="showBarcodeImg">
                        <table style="max-width: 200px; max-height: 170px;">
                            <tr> <td style=" text-align: center; height: 15px">Registro: <?= str_pad($model->id, 8, "0", STR_PAD_LEFT) ?></td></tr>
                            <tr> <td style="height: 26px"><div id="showBarcode" ></div></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- general form elements -->
        <div class="card card-primary" style="display:<?= $bolVisibleGridSalida ?>">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-angle-double-up text-danger"></i> <?= Yii::t('app', 'Registros de Salida') ?></h3>
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
                <h3 class="card-title"><i class="fas fa-angle-double-down text-success"></i> <?= Yii::t('app', 'Registros de Entrada') ?></h3>
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
                <h3 class="card-title"><i class="fas fa-user-tag text-white"></i> <?= Yii::t('app', 'Responsable') ?></h3>
            </div>
            <div class="card-body">
                <div class="form-group">

                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-3 ">
                            <?= $form->field($model, 'entrada')->textInput(['id'=>'fecha_registro','maxlength' => true,'class'=>'form-control','readonly' => true, 'value'=> util::getDateTimeFormatedSqlToUser($model->getFechaRegistro()) ])->label(Yii::t('app', 'Fecha Registro')) ?>
                        </div>
                        <div class="col-md-9 ">
                            <?= $form->field($model, 'id_comercial')->dropDownList(Registro::getComercialesDropdownList(), ['id' => 'id_comercial', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'disabled' => true])->label(Yii::t('app', 'Empresa')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 ">
                            <?= $form->field($model, 'tipo_documento')->dropDownList(util::arrTipoDocumentos, ['id' => 'tipo_documento_reponsable', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'disabled' => true])->label(Yii::t('app', 'Tipo Documento')); ?>
                        </div>
                        <div class="col-md-2 ">
                            <?= $form->field($model, 'documento')->textInput(['id'=>'documento_reponsable','maxlength' => true,'class'=>'form-control','readonly' => true])->label(Yii::t('app', 'Documento')) ?>
                        </div>
                        <div class="col-md-6 ">
                            <?= $form->field($model, 'nombre_responsable')->textInput(['id'=>'nombre_responsable','maxlength' => true,'class'=>'form-control','readonly' => true, 'value'=> trim(strtoupper( $model->nombre_responsable )) ])->label(Yii::t('app', 'Nombre Reponsable')) ?>
                        </div>
                        <div class="col-md-3 ">
                            <?= $form->field($model, 'telefono')->textInput(['id' => 'telefono_responsable', 'class' => 'form-control', 'style' => 'width:100%', 'readonly' => true])->label(Yii::t('app', 'Telefono')) ?>
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <?= $form->field($model, 'observacion')->textArea(['id' => 'txt_observacion', 'class' => 'form-control', 'style' => 'width:100%', 'readonly' => true])->label(Yii::t('app', 'Observaciones')) ?>
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

                    <?= Html::button('<i class="fas fa-download"></i> Imprimir', ['id' => 'btn_print', 'class' => 'btn btn-primary float-left', 'onclick' => '(function ( $event ) { generatePdfRegistro( ' . $model->id . ' ) })();', 'style' => 'margin-right: 5px;']); ?>
                    <?= $strHtmlActiveBotonProcess; ?>
                    <?= $strHtmlActiveBotonUpdate; ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs(
    '$("document").ready(function(){ 
            getBase64Image();
         });'
);

?>