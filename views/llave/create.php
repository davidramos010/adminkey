<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */

$this->title = 'Registrar';
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llave-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
