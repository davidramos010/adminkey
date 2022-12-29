<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comunidad */
/* @var $modal boolean */

$this->title = Yii::t('app', 'Registrar');
?>
<div style="align-content: center;">
    <?= $this->render('_form', [
        'model' => $model,
        'modal' => $modal
    ]) ?>

</div>
