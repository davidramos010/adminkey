<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */
/* @var $modelNota stdClass */
/* @var $llaveNota app\models\LlaveNotas */

$this->title = 'Info General : '.$model->nomenclatura.'-'.$model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Llaves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="llave-update" >

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,'view'=>false, 'modelNota' => $modelNota, 'llaveNota' => $llaveNota
    ]) ?>

</div>
