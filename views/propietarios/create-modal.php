<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Propietarios */
/* @var $modal boolean */

$this->title = Yii::t('app', 'Registrar');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model,
                        'modal' => true
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>