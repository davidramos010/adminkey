<?php

use app\models\Llave;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
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
    <div class="ribbon_wrap">
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                      Yii::t('app','reportHeaderLlaves1'),
                    ], ['encode' => false]);
                    echo Html::ul([
                        Yii::t('app','reportHeaderLlaves2') . "  ".
                        Html::button(Yii::t('app', 'Exportar Consolidado'),
                            ['class' => 'btn btn-info btn-xs', 'data-js-export-excel' => true]),
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-body">
                <?php
                Pjax::begin();
                $gridColumns = [
                    [
                        'attribute' => 'nombre_propietario',
                        'label' => Yii::t('app', 'Propietario'),
                        'headerOptions' => ['style' => 'width: 14%'],
                        'enableSorting' => false,
                        'format' => 'raw',
                        'value' => function ($model) {
                            return (isset($model->nombre_propietario)) ? strtoupper($model->nombre_propietario) : '';
                        }
                    ],
                    [
                        'attribute' => 'cliente_comunidad',
                        'label' => Yii::t('app', 'Edificio'),
                        'headerOptions' => ['style' => 'width: 15%'],
                        'enableSorting' => false,
                        'format' => 'raw',
                        'value' => function ($model) {
                            return (isset($model->cliente_comunidad)) ? strtoupper($model->cliente_comunidad) : '';
                        }
                    ],
                    [
                        'attribute' => 'id_tipo',
                        'class' => 'kartik\grid\DataColumn',
                        'label' => Yii::t('app', 'Tipo Llave'),
                        'headerOptions' => ['style' => 'width: 8%; '],
                        'enableSorting' => false,
                        'value' => function ($model) {
                            $strLabel = (isset($model->tipo)) ? strtoupper($model->tipo->descripcion) : '';
                            switch ($model->id_tipo) {
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
                        'label' => Yii::t('app', 'Código'),
                        'headerOptions' => ['style' => 'width: 7%'],
                        'enableSorting' => false,
                    ],
                    [
                        'attribute' => 'descripcion',
                        'label' => Yii::t('app', 'Descripción'),
                        'headerOptions' => ['style' => 'width: 20%'],
                        'enableSorting' => false,
                    ],
                    [
                        'attribute' => 'observacion',
                        'label' => Yii::t('app', 'Observación'),
                        'headerOptions' => ['style' => 'width: 15%'],
                        'enableSorting' => false,
                        'value' => function ($model) {
                            return $model->observacion ?: '';
                        }
                    ],
                    [
                        'attribute' => 'alarma',
                        'class' => 'kartik\grid\DataColumn',
                        'label' => Yii::t('app', 'Alarma'),
                        'headerOptions' => ['style' => 'width: 4%'],
                        'enableSorting' => false,
                        'value' => function ($model) {
                            switch ($model->alarma) {
                                case 1:
                                    $strReturnAlarm = '<span class="float-none badge bg-success">SI</span>';
                                    break;
                                case 0:
                                    $strReturnAlarm = '<span class="float-none badge bg-danger">NO</span>';
                                    break;
                                default:
                                    $strReturnAlarm = '';
                            }

                            return $strReturnAlarm;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['1' => 'Si', '0' => 'No', '' => 'Todos'],
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
                        'class' => 'kartik\grid\DataColumn',
                        'label' => Yii::t('app', 'Facturable'),
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting' => false,
                        'value' => function ($model) {

                            switch ($model->facturable) {
                                case 1:
                                    $strReturnFac = '<span class="float-none badge bg-success">SI</span>';
                                    break;
                                case 0:
                                    $strReturnFac = '<span class="float-none badge bg-danger">NO</span>';
                                    break;
                                default:
                                    $strReturnFac = '';
                            }

                            return $strReturnFac;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['1' => 'Si', '0' => 'No', '' => 'Todos'],
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
                        'class' => 'kartik\grid\DataColumn',
                        'label' => Yii::t('app', 'Estado'),
                        'headerOptions' => ['style' => 'width: 7%'],
                        'enableSorting' => false,
                        'value' => function ($model) {
                            return ($model->llaveLastStatus=='S')?
                            '<span class="float-none badge bg-danger">'.Yii::t('app','Prestada').'</span>':
                            '<span class="float-none badge bg-success">'.Yii::t('app','Almacenada').'</span>' ;
                        },
                        'format' => 'raw',
                        'pageSummary' => true,
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['S' => Yii::t('app','Prestada'), 'E' => Yii::t('app','Almacenada')],
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
                        'class' => '\kartik\grid\ActionColumn',
                        'header' => '',
                        'headerOptions' => array('style' => 'width: 100%'),
                        'mergeHeader' => false,
                        'template' => ' {info} ',
                        'width'=>'70px',
                        'vAlign'=>GridView::ALIGN_MIDDLE,
                        'hAlign'=>GridView::ALIGN_LEFT,
                        'buttons' => [
                            'info' => function ($url, $model) {
                                return '<button type="button" class="btn btn-outline-info btn-block btn-sm"
                                        data-toggle="modal" data-target="#modal-default"
                                        onclick="getInfoLlaveCard(' . $model->id . ')">
                                        <i class="fas fa-info-circle"></i></button> ';
                                },
                        ]
                    ]

                ];

                echo ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
                        'exportConfig' => [
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_PDF => [
                                'pdfConfig' => [
                                    'methods' => [
                                        'SetTitle' => 'Grid Export - AdminKey.com',
                                        'SetSubject' => 'Generating Report - AdminKey.com',
                                        'SetHeader' => ['AdminKey.com ||Generated On: ' . date("r")],
                                        'SetFooter' => ['|Page {PAGENO}|'],
                                        'SetAuthor' => 'AdminKey.com',
                                        'SetCreator' => 'AdminKey.com',
                                        'SetKeywords' => 'Report - AdminKey.com',
                                    ]
                                ]
                            ],
                        ],
                        'dropdownOptions' => [
                            'label' => 'Export All',
                            'class' => 'btn btn-outline-secondary btn-default'
                        ]
                    ]) . "<hr>\n".
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => $gridColumns,
                        'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                        'toolbar' => [
                            [
                                'content' =>
                                    Html::a(Yii::t('common', 'Recargar'), Url::current(), [
                                        'class' => 'btn bg-orange',
                                        'title' => Yii::t('common', 'Recargar manteniendo filtros')
                                    ]) . ' ' . Html::a(Yii::t('common', 'Limpiar'), ['report'], [
                                        'class' => 'btn btn-default',
                                        'title' => Yii::t('common', 'Limpiar filtros')
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
                <br><br>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<?php
$this->registerJs('
        $(document).on("click", "[data-js-export-excel]", function (e) {
            window.location.replace("' . Url::toRoute('llave/report') . '" + "?report=all");
        });
');
?>
