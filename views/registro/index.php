<?php

use app\models\Registro;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\models\util;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registros';
$this->registerJsFile('@web/js/registro.js');
?>
<div class="registro-index">

    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Listado de registro de entrada y salida de llaves.'
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
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->user))?strtoupper($model->user->username):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'comercial',
                        'label' => 'Empresa/Proveedor',
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->comercial))?strtoupper($model->comercial):'' ;
                        }
                    ],
                    [
                        'attribute' => 'llaves',
                        'label' => 'Llaves',
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            $strLLaves = '';
                            $strLLaves .= (!empty($model->llaves_s))?'<span class="float-none badge bg-danger">'.strtoupper($model->llaves_s).'</span>':'';
                            $strLLaves .= (!empty($strLLaves))?'<br>':'';
                            $strLLaves .= (!empty($model->llaves_e))?'<span class="float-none badge bg-success">'.strtoupper($model->llaves_e).'</span>':'';
                            return (isset($strLLaves))?$strLLaves:'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'propietarios',
                        'label' => 'Propietario',
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->propietarios))?strtoupper($model->propietarios):'' ;
                        }
                    ],
                    [
                        'attribute' => 'clientes',
                        'label' => 'Cliente',
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->clientes))?strtoupper($model->clientes):'' ;
                        }
                    ],
                    [
                        'attribute' => 'salida',
                        'headerOptions' => ['style' => 'width: 10%'],
                        'enableSorting'=>false,
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
                        'enableSorting'=>false,
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
                    [
                        'attribute' => 'id',
                        'label' => 'Soporte',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'format' => 'raw',
                        'value' => function($model){
                            return Html::button('<i class="fas fa-download"></i> Descargar', ['id' => 'btn_registrar', 'class' => 'btn btn-primary float-left btn-sm', 'onclick' => '(function ( $event ) { generatePdfRegistro( '.$model->id.' ) })();', 'style'=>'margin-right: 5px;']);
                        }
                    ],
                    [
                        'class' => '\kartik\grid\ActionColumn',
                        'header' => '',
                        'headerOptions' => array('style' => 'width: 100%'),
                        'mergeHeader' => false,
                        'template' => ' {view} ',
                        'width'=>'70px',
                        'vAlign'=>GridView::ALIGN_MIDDLE,
                        'hAlign'=>GridView::ALIGN_LEFT,
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $viewButton = Html::a(
                                    Html::button('<i class="fas fa-eye"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                    ['registro/view', 'id' => $model['id']],
                                    [
                                        'title' => Yii::t('common', 'Editar'),
                                        'data' => [
                                            'tooltip' => true,
                                            'pjax' => 0
                                        ]
                                    ]
                                );
                                return $viewButton;
                            },
                        ]
                    ]
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
                echo "<br><br>";
                // You can choose to render your own GridView separately
                echo \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => $gridColumns,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'resizableColumns' => false,
                    'condensed' => true,
                    'floatHeader' => false,
                    'pjax' => true,
                    'pjaxSettings' => [
                        'options' => [
                            'timeout' => false,
                            'enablePushState' => false,
                            'clientOptions' => [
                                'method' => 'GET'
                            ]
                        ]
                    ],
                    'toolbar' => [
                        [
                            'content' =>
                                Html::a('Recargar', Url::current(), [
                                    'class' => 'btn bg-orange',
                                    'title' => Yii::t('Common', 'Recargar manteniendo filtros')
                                ]) . ' ' . Html::a('Limpiar', ['index'], [
                                    'class' => 'btn btn-default',
                                    'title' => Yii::t('Common', 'Limpiar filtros')
                                ]),
                        ],
                        //count($dataProvider->models) < 100 ? '{toggleData}' : '',
                    ],
                    'panelPrefix' => 'panel mb-0 panel-',
                    'panel' => [
                        'heading' =>  $this->title,
                        'type' => GridView::TYPE_PRIMARY,
                        'class' => 'mb-0'
                    ],
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
