<?php

use app\models\Registro;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\models\util;

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
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'headerOptions' => ['style' => 'width: 5%'],
                    ],
                    [
                        'attribute' => 'id_user',
                        'label' => 'Usuario Sistema',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->user))?strtoupper($model->user->username):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'comercial',
                        'label' => 'Empresa/Responsable',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->comercial))?strtoupper($model->comercial):'' ;
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
                        'attribute' => 'nombre_propietario',
                        'label' => 'Propietario',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->nombre_propietario))?strtoupper($model->nombre_propietario):'' ;
                        }
                    ],
                    [
                        'attribute' => 'comunidad',
                        'label' => 'Comunidad',
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->llave))?strtoupper($model->comunidad):'' ;
                        }
                    ],
                    [
                        'attribute' => 'salida',
                        'headerOptions' => ['style' => 'width: 10%'],
                        'value' => function ($model) {
                            return (isset($model->salida))? util::getDateTimeFormatedSqlToUser($model->salida) :'' ;
                        },
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy',
                                'showButtonPanel'=>'true',
                                'showTodayButton' => 'true'
                            ],

                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app', 'Fecha Salida'),
                        ],
                        'label' => Yii::t('app', 'Fecha Salida'),
                        'headerOptions' => ['class' => 'col-xs-2'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; ']
                    ],
                    [
                        'attribute' => 'entrada',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'value' => function ($model) {
                            return (isset($model->entrada))? util::getDateTimeFormatedSqlToUser($model->entrada) :'' ;
                        },
                        'filterType' => GridView::FILTER_DATE,
                        'filterWidgetOptions' => [
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'dd-mm-yyyy'
                            ],

                        ],
                        'filterInputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => Yii::t('app', 'Fecha Devolución'),
                        ],
                        'label' => Yii::t('app', 'Fecha Devolución'),
                        'headerOptions' => ['class' => 'col-xs-2'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; ']
                    ],

                ];

                // Renders a export dropdown menu
                echo ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'dropdownOptions' => [
                        'label' => 'Export All',
                        'class' => 'btn btn-default'
                    ],
                    'showConfirmAlert'=>false,
                    'exportContainer' => [
                        'class' => 'btn-group mr-2'
                    ],
                    'filename'        => Yii::t('app', 'ReportMovimientos'),
                    'exportConfig' => [
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_CSV   => [
                            'label'           => Yii::t('app', 'CSV'),
                        ],
                        ExportMenu::FORMAT_EXCEL_X => [
                            'label'           => Yii::t('app', 'Excel'),
                        ],
                    ],
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
