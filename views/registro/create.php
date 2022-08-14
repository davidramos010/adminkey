<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */

$this->title = 'Registrar Movimiento de llave';
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$assetPlu = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins');
$this->registerCssFile( $assetPlu . '/datatables-bs4/css/dataTables.bootstrap4.min.css');
$this->registerCssFile( $assetPlu . '/datatables-responsive/css/responsive.bootstrap4.min.css');
$this->registerCssFile( $assetPlu . '/datatables-buttons/css/buttons.bootstrap4.min.css');
$this->registerCssFile( $assetDir . '/css/adminlte.min.css');
$this->registerJsFile('@web/js/registro.js');

?>

<div class="registro-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
