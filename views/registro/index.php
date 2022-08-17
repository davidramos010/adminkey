<?php

use app\models\Registro;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registros';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registro-index">

    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Listado de registro de entrada y salida de llaves'
                    ], ['encode' => false]);

                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">

                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $this->title; ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'username',
                            'label' => 'Usuario',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->user))?strtoupper($model->user->username):'No Encontrado' ;
                            }
                        ],
                        [
                            'attribute' => 'codigo',
                            'label' => 'Codigo Llave',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->llave))?strtoupper($model->llave->codigo):'No Encontrado' ;
                            }
                        ],
                        [
                            'attribute' => 'id_llave',
                            'label' => 'Descripcion',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->llave))?strtoupper($model->llave->descripcion):'No Encontrado' ;
                            }
                        ],
                        [
                            'attribute' => 'comunidad',
                            'label' => 'Comunidad',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->llave))?strtoupper($model->llave->comunidad->nombre):'No Encontrado' ;
                            }
                        ],
                        [
                            'attribute' => 'comercial',
                            'label' => 'Empresa',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->comercial))?strtoupper($model->comercial):'No Encontrado' ;
                            }
                        ],
                        'entrada',
                        'salida',
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>

</div>
