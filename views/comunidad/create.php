<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */

$this->title = 'Create comunidad';
$this->params['breadcrumbs'][] = ['label' => 'Comunidads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comunidad-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
