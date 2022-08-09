<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */

$this->title = 'Create Registro';
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registro-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
