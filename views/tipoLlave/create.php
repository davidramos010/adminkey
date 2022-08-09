<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TipoLlave */

$this->title = 'Create Tipo Llave';
$this->params['breadcrumbs'][] = ['label' => 'Tipo Llaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-llave-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
