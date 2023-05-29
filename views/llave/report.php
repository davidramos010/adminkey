<?php

use app\models\Llave;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\Select2;

use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJsFile('@web/js/llave.js');
$this->title = 'Reporte de Llaves';
?>
<div class="llave-index">
    <!-- form start -->
    <?= $this->render('info') ?>
    <!-- form end -->

    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Utilice los recuadros de cada columna para filtrar la información que desea consultar.',
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-body">
                <?php Pjax::begin(['timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
                <?php

                $gridColumns = [
                     [
                        'attribute' => 'nombre_propietario',
                        'label' => 'Propietario',
                        'headerOptions' => ['style' => 'width: 15%'],
                        'enableSorting'=>false,
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->nombre_propietario))?strtoupper($model->nombre_propietario):'' ;
                        }
                    ],
                    [
                        'attribute' => 'cliente_comunidad',
                        'label' => 'Cliente',
                        'headerOptions' => ['style' => 'width: 15%'],
                        'enableSorting'=>false,
                        'format' => 'raw',
                        'value' => function($model){
                            return (isset($model->cliente_comunidad))?strtoupper($model->cliente_comunidad):'' ;
                        }
                    ],
                    [
                        'attribute' => 'id_tipo',
                        'label' => 'Tipo Llave',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {
                            $strLabel = (isset($model->tipo))?strtoupper($model->tipo->descripcion):'' ;
                            switch ($model->id_tipo){
                                case 1:
                                    $class = 'bg-success';
                                    break;
                                case 2:
                                    $class = 'bg-info';
                                    break;
                                case 3:
                                    $class = 'bg-primary';
                                    break;
                                default:
                                    $class = 'bg-muted';
                            }
                            return '<span class="float-none badge '.$class.'">'.$strLabel.'</span>';
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => Llave::getTipoLlaveDropdownList(),
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
                        'attribute' => 'codigo',
                        'label' => 'Código',
                        'headerOptions' => ['style' => 'width: 8%'],
                        'enableSorting'=>false,
                    ],
                    [
                        'attribute' => 'descripcion',
                        'label' => 'Descripción',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'enableSorting'=>false,
                    ],
                    [
                        'attribute' => 'observacion',
                        'label' => 'Observación',
                        'headerOptions' => ['style' => 'width: 17%'],
                        'enableSorting'=>false,
                    ],
                    [
                        'attribute' => 'alarma',
                        'label' => 'Alarma',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {

                            switch ($model->alarma){
                                case 1:
                                    $strReturnAlarm = '<span class="float-none badge bg-success">SI</span>';
                                    break;
                                case 0:
                                    $strReturnAlarm = '<span class="float-none badge bg-danger">NO</span>';
                                    break;
                                default:
                                    $strReturnAlarm = '';
                            }

                            return $strReturnAlarm ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => [ '1' => 'Si', '0' => 'No', ''=>'Todos'],
                        'filterWidgetOptions' => [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'options' => ['prompt' => ''],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Todos',
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'facturable',
                        'label' => 'Facturable',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {

                            switch ($model->facturable){
                                case 1:
                                    $strReturnFac = '<span class="float-none badge bg-success">SI</span>';
                                    break;
                                case 0:
                                    $strReturnFac = '<span class="float-none badge bg-danger">NO</span>';
                                    break;
                                default:
                                    $strReturnFac = '';
                            }

                            return $strReturnFac ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => [ '1' => 'Si', '0' => 'No', ''=>'Todos'],
                        'filterWidgetOptions' => [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'options' => ['prompt' => ''],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => 'Todos',
                            ],
                        ],
                    ],
                    [
                        'attribute' => 'llaveLastStatus',
                        'label' => 'Estado',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {
                            return ($model->llaveLastStatus=='S')?'<span class="float-none badge bg-danger">Prestada</span>':'<span class="float-none badge bg-success">Almacenada</span>' ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['S' => 'Prestada', 'E' => 'Almacenada'],
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
                        'attribute' => 'id',
                        'label' => 'Info',
                        'filter'=>false,
                        'enableSorting'=>false,
                        'headerOptions' => ['style' => 'width: 5%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return '<button type="button" class="btn btn-outline-info btn-block btn-sm" data-toggle="modal" data-target="#modal-default" onclick="getInfoLlaveCard('.$model->id.')"><i class="fas fa-info-circle"></i></button> ';
                        }
                    ],
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
                <br><br>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'resizableColumns' => false,
                    'condensed' => true,
                    'floatHeader' => false,
                    'columns' => $gridColumns,
                    'toolbar' => [
                        [
                            'content' =>
                                 Html::a('Recargar', Url::current(), [
                                    'class' => 'btn bg-orange',
                                    'title' => Yii::t('Common', 'Recargar manteniendo filtros')
                                ]) . ' ' . Html::a('Limpiar', ['report'], [
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
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

</div>
