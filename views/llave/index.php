<?php

use app\models\Llave;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Llaves';
?>
<div class="llave-index">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'El codigo de barras de cada llave se genera Automaticamente',
                        'El codigo de la llave hace referencia a una codificación interna del usuario.'
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">
                    <?php
                    echo Html::a('Crear Registro',['create'],['class' => 'btn btn-success']);
                    ?>
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
                            'attribute' => 'id_comunidad',
                            'label' => 'Comunidad',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->comunidad))?strtoupper($model->comunidad->nombre):'No Encontrado' ;
                            }
                        ],
                        [
                            'attribute' => 'id_tipo',
                            'label' => 'Tipo',
                            'format' => 'raw',
                            'value' => function($model){
                                return (isset($model->tipo))?strtoupper($model->tipo->descripcion):'No Encontrado' ;
                            }
                        ],
                        'codigo',
                        'descripcion',
                        'observacion',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Llave $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
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
