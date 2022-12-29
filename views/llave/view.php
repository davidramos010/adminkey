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
    'value'=> strtoupper(str_pad($model->codigo, 8, " ", STR_PAD_LEFT)),  /* value for EAN 13 be careful to set right values for each barcode type */
    'type'=>'code39',/*supported types  ean8, ean13, upc, std25, int25, code11, code39, code93, code128, codabar, msi, datamatrix*/
    'settings'=>array(
        'output'=>'bmp' /*css, bmp, canvas note- bmp and canvas incompatible wtih IE*/,
        /*if the output setting canvas*/
        'posX' => 10,
        'posY' => 20,
        /* */
        'bgColor'=>'1', /*background color*/
        'color' => '1', /*"1" Bars color*/
        'barWidth' => 1,
        'barHeight' => 20,
        'fontSize' => 10,
        /*-----------below settings only for datamatrix--------------------*/
        'moduleSize' => 6,
        'addQuietZone' => 0, /*Quiet Zone Modules */
    ),
);
echo BarcodeGenerator::widget($optionsArray);
$this->registerJsFile('@web/js/llave.js');

?>

<div class="llave-view" style="max-width: 960px">
    <h1><?= Html::encode($this->title) ?></h1>
    <!-- form start -->
    <?= $this->render('_form', [
        'model' => $model,'view'=>true
    ]) ?>
    <!-- form end -->

    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <!-- /.card-header -->
            <div id="showTableBarcode" style=" max-width: 230px; max-height: 170px; aling-items: center; justify-content: center" class="border border-primary">
                <table align="center"  style=" font-size: 10px; max-width: 200px; max-height: 170px;">
                    <tr> <td style=" text-align: center; height: 15px"><?= strtoupper(trim($model->nomenclatura)) ?></td></tr>
                    <tr> <td align="center" style="height: 26px"><div id="showBarcode" ></div></td></tr>
                    <tr>
                        <td style="text-align: center; vertical-align: top">
                            <div style="font-size: 10px; font-weight: bold; max-width: 150px;"><?= strtoupper(trim($model->codigo)) ?></div>
                            <div style="font-size: 12px; max-width: 150px;"><?= strtoupper(trim($model->descripcion)) ?></div>
                        </td>
                    </tr>
                </table>
            </div>

            <div  style="padding: 5px 5px 5px" >
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Esta seguro que desea eliminar el registro?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Volver a listado'), ['index'], ['class' => 'btn btn-default ']) ?>
                <?= Html::button('Imprimir Barras', [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { printDiv() })();' ]); ?>
            </div>
        </div>
    </div>
</div>
