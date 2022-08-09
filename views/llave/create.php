<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */

$this->title = 'Create Llave';
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llave-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
