<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

$this->title = 'Proveedores';
$this->params['breadcrumbs'][] = ['label' => 'Proveedor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comerciales-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
