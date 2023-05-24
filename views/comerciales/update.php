<?php

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

use yii\helpers\Html;

$this->title = Yii::t('app', 'Info General').' : '.$model->nombre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Proveedor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Actualizar');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>