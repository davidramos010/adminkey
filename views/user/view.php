<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $model_info app\models\UserInfo */


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
                    'username',
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Nombres'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->nombres))?strtoupper($model->userInfo->nombres):'' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Apellidos'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->apellidos))?strtoupper($model->userInfo->apellidos):'' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Teléfono'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->telefono))?$model->userInfo->telefono:'' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Email'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->email))?$model->userInfo->email:'' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Dirección'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->direccion))?strtoupper($model->userInfo->direccion):'' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Estado'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->estado) && $model->userInfo->estado==1)?'Activo':'Inactivo';
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Perfil'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->perfiluser))?strtoupper($model->perfiluser->perfil->nombre):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'id',
                        'label' => Yii::t('app', 'Creado'),
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->userInfo->created))? date("d/m/Y H:i", strtotime($model->userInfo->created)):'';
                        }
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