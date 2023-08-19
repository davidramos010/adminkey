<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Registro */

$this->title = Yii::t('app','Actualizar - Eliminar Movimiento');
$this->params['breadcrumbs'][] = ['label' => 'Registros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/registro.js');
?>
<div class="registro-update">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'update'
    ]) ?>

</div>
