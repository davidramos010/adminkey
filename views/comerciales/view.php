<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Comerciales */

$this->title = 'Info General : '.$model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Comerciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="comerciales-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Comunidad</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'nombre',
                    'telefono',
                    'contacto',
                ],
            ]) ?>

            <div  style="padding: 5px 5px 5px" >
                <?= Html::a('Modificar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Esta seguro que desea eliminar el registro?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a(Yii::t('app', 'Volver a listado'), ['index'], ['class' => 'btn btn-default ']) ?>
            </div>
        </div>
    </div>
</div>
