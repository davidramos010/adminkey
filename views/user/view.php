<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Info General : '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuario</h3>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'username',
                    'name',
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Perfil'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->perfiluser))?strtoupper($model->perfiluser->perfil->nombre):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'password',
                        'label' => Yii::t('app', 'Password'),
                        'format' => 'raw',
                        'value' => '*****'
                    ],
                    [
                        'attribute' => 'authKey',
                        'label' => Yii::t('app', 'AuthKey'),
                        'format' => 'raw',
                        'value' => '*****'
                    ],
                    [
                        'attribute' => 'accessToken',
                        'label' => Yii::t('app', 'AccessToken'),
                        'format' => 'raw',
                        'value' => '*****'
                    ],
                ],
            ]) ?>

            <div style="padding: 5px 5px 5px" >
                <?= Html::a(Yii::t('app', 'Eliminar'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Esta seguro que desea eliminar el registro?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a(Yii::t('app', 'Modificar'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Volver a listado'), ['index'], ['class' => 'btn btn-default ']) ?>
            </div>
        </div>
    </div>

    <!--.card-->
</div>