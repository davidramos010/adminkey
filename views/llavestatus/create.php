<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LlaveStatus */

$this->title = 'Create Llave Status';
$this->params['breadcrumbs'][] = ['label' => 'Llave Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llave-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
