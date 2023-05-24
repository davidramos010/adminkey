<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

$this->title = 'Info General : '.$model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Proveedor', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>

<div class="comerciales-update">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?= $this->render('_form', ['model' => $model]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>