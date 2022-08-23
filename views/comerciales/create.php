<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

$this->title = 'Create Comerciales';
$this->params['breadcrumbs'][] = ['label' => 'Comerciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comerciales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
