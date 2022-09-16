<?php

use app\models\util;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\Select2;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContratosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contratos');
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container-fluid">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Use los filtros para identificar un contrato.',
                        'El campo CREADO filtra contratos con fecha de creación del día seleccionado.'
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">
                    <?php
                    echo Html::a('Crear Registro',['create-contrato'],['class' => 'btn btn-success']);
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
                <?php

                $gridColumns = [
                    [
                        'attribute' => 'nombre',
                        'label' => 'Contrato',
                        'headerOptions' => ['style' => 'width: 15%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return strtoupper($model->nombre);
                        }
                    ],
                    [
                        'attribute' => 'llaves',
                        'label' => 'Llaves',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return strtoupper($model->llaves);
                        }
                    ],
                    [
                        'attribute' => 'cliente',
                        'label' => 'Cliente',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return strtoupper($model->cliente);
                        }
                    ],
                    [
                        'attribute' => 'propietario',
                        'label' => 'Propietario',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return strtoupper($model->propietario);
                        }
                    ],
                    [
                        'attribute' => 'estado',
                        'label' => 'Estado',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return ($model->estado==1)?'<span class="float-none badge bg-success">ACTIVO</span>':'<span class="float-none badge bg-danger">ACTIVO</span>' ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => [ '1' => 'ACTIVO', '0' => 'INACTIVO'],
                        'filterWidgetOptions' => [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Todos',
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'fecha',
                        'headerOptions' => ['style' => 'width: 15%'],
                        'value' => function ($model) {
                            return (isset($model->fecha))? util::getDateTimeFormatedSqlToUser($model->fecha) :'' ;
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
                            'placeholder' => Yii::t('app', 'Fecha Creación'),
                        ],
                        'label' => Yii::t('app', 'Creado'),
                        'headerOptions' => ['class' => 'col-xs-2'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; ']
                    ],
                    [
                        'attribute' => 'copia_firma',
                        'label' => 'Documento Firmado',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'format' => 'raw',
                        'value' => function($model){
                            $strReturn = "";
                            if(!empty($model->copia_firma)){
                                $url = Yii::$app->urlManager->createUrl(['site/download','path'=>'/contratos_firmados/','file'=>$model->copia_firma]);
                                $strReturn = Html::a('<i class="fas fa-download"></i> Descargar' , $url, ['title'=>'Descargar Plantilla', 'target' => '_blank', 'class' => 'btn btn-primary float-right btn-sm', 'style'=>'margin-right: 5px;', 'data' => ['tooltip' => true, 'pjax' => 0 ]])  ;
                            }
                            return $strReturn;
                        }
                    ],
                    [
                        'class' => '\kartik\grid\ActionColumn',
                        'header' => '',
                        'headerOptions' => array('style' => 'width: 100%'),
                        'mergeHeader' => false,
                        'template' => ' {update} {delete} ',
                        'width'=>'70px',
                        'vAlign'=>GridView::ALIGN_MIDDLE,
                        'hAlign'=>GridView::ALIGN_LEFT,
                        'buttons' => [
                            'update' => function ($url, $model) {
                                $viewButton = Html::a(
                                    Html::button('<i class="fas fa-pen"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                    ['contratos/create-contrato', 'idContratoLog' => $model['id']],
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
                            'delete' => function ($url, $model) {
                                $viewButton = null;
                                if($model['estado']){

                                    $viewButton = Html::a(
                                        Html::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                        ['contratos/delete-contrato', 'idContratoLog' => $model['id']],
                                        [
                                            'title' => Yii::t('common', 'Eliminar'),
                                            'data' => [
                                                'confirm' => 'Esta seguro que desea eliminar el registro?',
                                                'method' => 'post',
                                                'tooltip' => true,
                                                'pjax' => 0
                                            ]
                                        ]
                                    );
                                }
                                return $viewButton;
                            }
                        ]
                    ]
                ]; ?>
                <?= // Renders a export dropdown menu
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumns,
                    'dropdownOptions' => [
                        'label' => 'Export All',
                        'class' => 'btn btn-default',
                    ],
                    'showConfirmAlert'=>false,
                    'exportContainer' => [
                        'class' => 'btn-group mr-2'
                    ],
                    'filename'        => Yii::t('app', 'ReportLlave'),
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
                ?>
                <?= \kartik\grid\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'columns' => $gridColumns,
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>

</div>
