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


$buttonFiltroPendientes = Html::a('Pendientes ' . (strpos(Url::current(),
        'pendientes=1') ? '✅' : ''),
    Url::current([
        'pendientes' => strpos(Url::current(),
            'pendientes=1') ? '' : '1'
    ]), [
        'class' => 'btn btn-default ' . (strpos(Url::current(),
                'pendientes=1') ? 'active' : ''),
        'title' => Yii::t('app', 'Filtrar por registros pendiente de devolución')
    ]);

?>
<div class="registro-index">
    <div class="ribbon_wrap" >

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title"><?= Yii::t('app','Importante').' !!'; ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="ribbon_addon pull-right margin-r-" style="margin-right: 3% !important">
                                        <?php
                                        echo Html::ul([
                                            Yii::t('app','Listado de registro de entrada y salida de llaves.'),
                                        ], ['encode' => false]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                                        <?php
                                        echo Html::ul([
                                            '<span style="font-size: x-small; border-color: #ed969e; background-color: #f5c6cb; border: 1px solid #dee2e6; "> &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; &nbsp; <span style="font-size: 14px">'.Yii::t('app','KO. Registros pendientes por devolver.').'</span>',
                                            '<span style="font-size: x-small; border-color: #8fd19e; background-color: #c3e6cb; border: 1px solid #dee2e6; "> &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; &nbsp; <span style="font-size: 14px">'.Yii::t('app','OK. Registros devueltos.').'</span>',
                                            '<span style="font-size: x-small; border: 1px solid #dee2e6; "> &nbsp; &nbsp; &nbsp; &nbsp; </span> &nbsp; &nbsp;  <span style="font-size: 14px">'.Yii::t('app', 'OK. Registros de almacenamiento.').' </span>',
                                        ], ['encode' => false]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="card card-primary">
            <!-- /.card-header -->
            <div class="card-body">
                <?php Pjax::begin(['timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
                <?php

                $gridColumns = [
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; '],
                    ],
                    [
                        'attribute' => 'username',
                        'label' => Yii::t('app','User'),
                        'headerOptions' => ['style' => 'width: 5%;'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; font-size: small; '],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->user))?strtoupper($model->user->username):'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'comercial',
                        'label' => Yii::t('app','Empresa/Proveedor'),
                        'headerOptions' => ['style' => 'width: 15%;'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle;  '],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->comercial))?strtoupper($model->comercial):'' ;
                        }
                    ],
                    [
                        'attribute' => 'nombre_responsable',
                        'label' => Yii::t('app','Responsable'),
                        'headerOptions' => ['style' => 'width: 15%'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; '],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->nombre_responsable))?strtoupper($model->nombre_responsable):'' ;
                        }
                    ],
                    [
                        'attribute' => 'llaves',
                        'label' => Yii::t('app','Llaves'),
                        'headerOptions' => ['style' => 'width: 15%;'],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            $strLLaves = '';
                            $strLLaves .= (!empty($model->llaves_s))?'<span class="float-none badge bg-danger" style="white-space: normal;">'.strtoupper($model->llaves_s).'</span>':'';
                            $strLLaves .= (!empty($strLLaves))?'<br>':'';
                            $strLLaves .= (!empty($model->llaves_e))?'<span class="float-none badge bg-success" style="white-space: normal;">'.strtoupper($model->llaves_e).'</span>':'';
                            return (isset($strLLaves))?$strLLaves:'No Encontrado' ;
                        }
                    ],
                    [
                        'attribute' => 'propietarios',
                        'label' => Yii::t('app','Propietario'),
                        'headerOptions' => ['style' => 'width: 15%; vertical-align: middle; '],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; '],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->propietarios))?strtoupper($model->propietarios):'' ;
                        }
                    ],
                    [
                        'attribute' => 'clientes',
                        'label' =>  Yii::t('app','Cliente'),
                        'headerOptions' => ['style' => 'width: 15%; vertical-align: middle;  '],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; font-size: small;'],
                        'format' => 'raw',
                        'enableSorting'=>false,
                        'value' => function($model){
                            return (isset($model->clientes))?strtoupper($model->clientes):'' ;
                        }
                    ],
                    [
                        'attribute' => 'salida',
                        'enableSorting'=>false,
                        'value' => function ($model) {
                            return (isset($model->salida))? util::getDateTimeFormatedSqlToUser($model->salida) :  util::getDateTimeFormatedSqlToUser($model->entrada);
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
                            'placeholder' => Yii::t('app', 'Fecha').' '.Yii::t('app', 'Operación'),
                        ],
                        'label' => Yii::t('app', 'Fecha').' '.Yii::t('app', 'Operación'),
                        'headerOptions' => ['class' => 'col-xs-2','style' => 'width: 10%'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle;'],
                    ],
                    [
                        'class' => '\kartik\grid\ActionColumn',
                        'header' => '',
                        'headerOptions' => ['style' => 'width: 5%;'],
                        'mergeHeader' => false,
                        'template' => ' {view} {download}',
                        'vAlign'=>GridView::ALIGN_MIDDLE,
                        'hAlign'=>GridView::ALIGN_LEFT,
                        'buttons' => [
                            'view' => function ($url, $model) {
                                $viewButton = Html::a(
                                    Html::button('<i class="fas fa-eye"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                    ['registro/view', 'id' => $model['id']],
                                    [
                                        'title' => Yii::t('common', 'Ver'),
                                        'data' => [
                                            'tooltip' => true,
                                            'pjax' => 0
                                        ]
                                    ]
                                );
                                return $viewButton;
                            },
                            'download' => function ($url, $model) {
                                $downloadButton = Html::button('<i class="fas fa-download"></i> ' ,
                                    [
                                        'id' => 'btn_registrar',
                                        'title' => Yii::t('common', 'Descargar'),
                                        'class' => 'btn btn-primary btn-xs',
                                        'onclick' => '(function ( $event ) { generatePdfRegistro( ' . $model->id . ' ) })();', 'style' => 'margin-right: 5px;'
                                    ]
                                );
                                return $downloadButton;
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
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'rowOptions'=>function($model){
                        $arrClass = [];
                        $numLlaveSt = (int) $model->llaves_st ;
                        $numLlaveSp = (int) $model->llaves_sp ;
                        if($numLlaveSt > $numLlaveSp){ // pendientes
                            $arrClass = ['class' => 'table-danger'];
                        }
                        if(empty($arrClass) && !empty($numLlaveSt) && !empty($numLlaveSp) && $numLlaveSt <= $numLlaveSp){ // terminados
                            $arrClass = ['class' => 'table-success'];
                        }
                        return $arrClass;
                    },
                    'filterModel' => $searchModel,
                    'columns' => $gridColumns,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'resizableColumns' => false,
                    'condensed' => true,
                    'floatHeader' => false,

                    'toolbar' => [
                        [
                            'content' =>
                                $buttonFiltroPendientes. ' ' .
                                Html::a('Recargar', Url::current(), [
                                    'class' => 'btn bg-orange',
                                    'title' => Yii::t('Common', 'Recargar manteniendo filtros')
                                ]) . ' ' . Html::a('Limpiar', ['index'], [
                                    'class' => 'btn btn-default',
                                    'title' => Yii::t('Common', 'Limpiar filtros')
                                ]),
                        ],
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
