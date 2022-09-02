<?php

use app\models\Registro;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;

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
                <?php


                $gridColumns = [
                    ['class' => 'kartik\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'id_user',
                        'label' => 'Usuario',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->user))?strtoupper($model->user->username):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'codigo',
                        'label' => 'Llave',
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
                        'attribute' => 'id_llave',
                        'label' => 'Comunidad',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->llave))?strtoupper($model->llave->comunidad->nombre):'No Encontrado' ;
                        }
                    ],
                    'entrada',
                    'salida'
                ];

                // Renders a export dropdown menu
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'dropdownOptions' => [
                        'label' => 'Export All',
                        'class' => 'btn btn-secondary'
                    ]
                ]);

                // You can choose to render your own GridView separately
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $gridColumns
                ]);
                ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>

</div>
