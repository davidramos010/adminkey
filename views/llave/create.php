<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */

$this->title = Yii::t('app', 'Registrar');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Llaves'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/llave.js');
?>
<div class="llave-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
