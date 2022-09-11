<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

$this->title = 'Create Comerciales';
$this->params['breadcrumbs'][] = ['label' => 'Comerciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comerciales-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
