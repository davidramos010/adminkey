<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */

$this->title = 'Update comunidad: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Comunidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comunidad-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
