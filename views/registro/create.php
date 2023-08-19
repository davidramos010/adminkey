<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Registro */

$this->title = Yii::t('app','Registrar Movimiento');
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/registro.js');

?>

<div class="registro-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
