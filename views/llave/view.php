<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */

$this->title = 'Info General : '.$model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$optionsArray = array(
    'elementId'=> 'showBarcode', /* div or canvas id*/
    'value'=> strtoupper($model->codigo),  /* value for EAN 13 be careful to set right values for each barcode type */
    'type'=>'code39',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
    'settings'=>array(
        'output'=>'css' /*css, bmp, canvas note- bmp and canvas incompatible wtih IE*/,
        /*if the output setting canvas*/
        'posX' => 10,
        'posY' => 20,
        /* */
        'bgColor'=>'#FFF', /*background color*/
        'color' => '#000000', /*"1" Bars color*/
        'barWidth' => 2,
        'barHeight' => 60,
        /*-----------below settings only for datamatrix--------------------*/
        'moduleSize' => 5,
        'addQuietZone' => 0, /*Quiet Zone Modules */
    ),

);
echo BarcodeGenerator::widget($optionsArray);
$this->registerJsFile('@web/js/llave.js');

?>

<div class="llave-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Llaves</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'id_comunidad',
                        'label' => 'Comunidad',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->comunidad))?strtoupper($model->comunidad->nombre):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'id_tipo',
                        'label' => 'Tipo',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->tipo))?strtoupper($model->tipo->descripcion):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'copia',
                        'label' => 'Número de Copias',
                    ],
                    'codigo',
                    'descripcion',
                    'observacion',
                    [
                        'attribute' => 'activa',
                        'label' => 'Estado',
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->activa ? 'Activo':'Inactivo';
                        }
                    ],
                ],
            ]) ?>

            <div  style="padding: 15px 15px; aling-items: center; justify-content: center" >
                <div id="showBarcode" ></div>
            </div>

            <div  style="padding: 5px 5px 5px" >
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Esta seguro que desea eliminar el registro?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Volver a listado'), ['index'], ['class' => 'btn btn-default ']) ?>
                <?= Html::button('Imprimir Barras', [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { printDiv() })();' ]); ?>
            </div>
        </div>
    </div>
</div>
