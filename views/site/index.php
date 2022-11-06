<?php

use hail812\adminlte\widgets\Callout;
use hail812\adminlte\widgets\InfoBox;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

$this->title = 'Dashboard';
$this->registerJsFile('@web/js/home.js');
$this->registerJsFile('@web/js/llave.js');

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $params array */

$gridColumns = [
    [
        'attribute' => 'id_llave',
        'label' => 'Código',
        'headerOptions' => ['style' => 'width: 5%; '],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return '<button type="button" class="btn btn-outline-info btn-block btn-sm" data-toggle="modal" data-target="#modal-default" onclick="getInfoLlaveCard('.$model->id_llave.')">'.$model->llave->codigo.'</button>';
        }
    ],
    [
        'attribute' => 'id_llave',
        'label' => 'Descripción',
        'headerOptions' => ['style' => 'width: 15%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return $model->llave->descripcion;
        }
    ],
    [
        'attribute' => 'id_llave',
        'label' => 'Cliente',
        'headerOptions' => ['style' => 'width: 15%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return (isset($model->llave->comunidad) && !empty($model->llave->comunidad))?$model->llave->comunidad->nombre:'';
        }
    ],
    [
        'attribute' => 'id_llave',
        'label' => 'Dirección',
        'headerOptions' => ['style' => 'width: 20%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return (isset($model->llave->comunidad) && !empty($model->llave->comunidad))?$model->llave->comunidad->poblacion.' '.$model->llave->comunidad->direccion:'';
        }
    ],
    [
        'attribute' => 'id_llave',
        'label' => 'Responsable',
        'headerOptions' => ['style' => 'width: 15%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return (isset($model->registro->comerciales) && !empty($model->registro->comerciales))?$model->registro->comerciales->nombre:'';
        }
    ],
    [
        'attribute' => 'id_llave',
        'label' => 'Teléfono',
        'headerOptions' => ['style' => 'width: 15%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return (isset($model->registro->comerciales) && !empty($model->registro->comerciales))?$model->registro->comerciales->telefono.' '.$model->registro->comerciales->movil:'';
        }
    ],
    [
        'attribute' => 'fecha',
        'label' => 'Fecha Salida',
        'headerOptions' => ['style' => 'width: 15%'],
        'format' => 'raw',
        'enableSorting' => false,
        'value' => function($model){
            return $model->fecha ;
        }
    ],
];

?>
<div class="pull-right" >

    <!-- form start -->
    <?= $this->render('../llave/info') ?>
    <!-- form end -->

    <?php if(count($params['llaves']['arrLlavesFecha'][5])): ?>
        <?php

        $addHtmlGrid = " <button id='BtnGridView5' type='button' class='btn btn-outline-primary btn-xs' onclick='fnToogleSeeKey(5)'> ".Yii::t('common', 'Ver')." + </button> ";
        $addHtmlGrid .= '<div id="GridViewExport5" style="display: none">'.ExportMenu::widget([
            'dataProvider' => $params['llavesDataProvider'][5],
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => '',
                'class' => 'btn btn-default',
            ],
            'showConfirmAlert' => false,
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'filename' => Yii::t('app', 'ReportLlavesSinRetorno5dias'),
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_CSV => [
                    'label' => Yii::t('app', 'CSV'),
                ],
                ExportMenu::FORMAT_EXCEL_X => [
                    'label' => Yii::t('app', 'Excel'),
                ],
            ],
        ]).'</div>';
        $addHtmlGrid .= GridView::widget([
            'dataProvider' => $params['llavesDataProvider'][5],
            'id' => 'GridView5',
            'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
            'columns' => $gridColumns,
            'summary'=>'',
            'options'=>[ 'style'=>'font-size:12px;display:none']
        ]);
        ?>

        <div class="row">
            <div class="col-lg-12" >
                <?= Callout::widget([
                    'head' => '<i class="fas fa-info"></i> &nbsp; '.Yii::t('app', 'importante').' !',
                    'body' => Yii::t('app', 'indexBody5a').' <strong> '.count($params['llaves']['arrLlavesFecha'][5]).' </strong>'.Yii::t('app', 'indexBody5b').' '.$addHtmlGrid
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if(count($params['llaves']['arrLlavesFecha'][10])): ?>
        <?php
            $addHtmlGrid = " <button id='BtnGridView10' type='button' class='btn btn-outline-warning btn-xs' onclick='fnToogleSeeKey(10)'> ".Yii::t('common', 'Ver')." + </button> ";
            $addHtmlGrid .= '<div id="GridViewExport10" style="display: none">'.ExportMenu::widget([
            'dataProvider' => $params['llavesDataProvider'][10],
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => '',
                'class' => 'btn btn-default',
            ],
            'showConfirmAlert' => false,
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'filename' => Yii::t('app', 'ReportLlavesSinRetorno10dias'),
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_CSV => [
                    'label' => Yii::t('app', 'CSV'),
                ],
                ExportMenu::FORMAT_EXCEL_X => [
                    'label' => Yii::t('app', 'Excel'),
                ],
            ],
        ]).'</div>';
            $addHtmlGrid .= GridView::widget([
                'dataProvider' => $params['llavesDataProvider'][10],
                'id' => 'GridView10',
                'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                'columns' => $gridColumns,
                'summary'=>'',
                'options'=>[ 'style'=>'font-size:12px;display:none'],
                'rowOptions' => function ($model) {
                    return [
                        'data-js-url' => $model->id,
                        'class' => 'row-grid cursor-pointer ' ,
                    ];
                },
            ]);
        ?>
        <div class="row">
            <div class="col-lg-12">
                <?= Callout::widget([
                    'type' => 'warning',
                    'head' => '<i class="fas fa-info-circle"></i> '.Yii::t('app', 'arrLlavesFecha10').'!',
                    'body' => Yii::t('app', 'indexBody10a').'<strong> '.count($params['llaves']['arrLlavesFecha'][10]).' </strong>'.Yii::t('app', 'indexBody10b').' '.$addHtmlGrid
                ]) ?>
            </div>
        </div>

    <?php endif; ?>
    <?php if(count($params['llaves']['arrLlavesFecha'][15])): ?>
        <?php
        $addHtmlGrid = " <button id='BtnGridView15' type='button' class='btn btn-outline-danger btn-xs' onclick='fnToogleSeeKey(15)'> ".Yii::t('common', 'Ver')." + </button> ";
        // Renders a export dropdown menu
        $addHtmlGrid .= '<div id="GridViewExport15" style="display: none">'.ExportMenu::widget([
            'dataProvider' => $params['llavesDataProvider'][15],
            'columns' => $gridColumns,
            'dropdownOptions' => [
                'label' => '',
                'class' => 'btn btn-default',
            ],
            'showConfirmAlert' => false,
            'exportContainer' => [
                'class' => 'btn-group mr-2'
            ],
            'filename' => Yii::t('app', 'ReportLlavesSinRetorno15dias'),
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_EXCEL => false,
                ExportMenu::FORMAT_TEXT => false,
                ExportMenu::FORMAT_PDF => false,
                ExportMenu::FORMAT_CSV => [
                    'label' => Yii::t('app', 'CSV'),
                ],
                ExportMenu::FORMAT_EXCEL_X => [
                    'label' => Yii::t('app', 'Excel'),
                ],
            ],
        ]).'</div>';
        $addHtmlGrid .= GridView::widget([
            'dataProvider' => $params['llavesDataProvider'][15],
            'id' => 'GridView15',
            'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
            'columns' => $gridColumns,
            'summary'=>'',
            'options'=>[ 'style'=>'font-size:12px;display:none']
        ]);

        ?>
        <div class="row">
            <div class="col-lg-12">
                <?= Callout::widget([
                    'type' => 'danger',
                    'head' => '<i class="fas fa-times-circle"></i> '.Yii::t('app', 'arrLlavesFecha15'),
                    'body' => Yii::t('app', 'indexBody15a').'<strong> '.count($params['llaves']['arrLlavesFecha'][15]).' </strong>  '.Yii::t('app', 'indexBody15b').$addHtmlGrid
                ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-3">
            <?= InfoBox::widget([
                'text' => Yii::t('app', 'Cant. Llaves'),
                'number' => $params['llaves']['num_llaves'] ,
                'theme' => 'gradient-success',
                'icon' => 'fas fa-key',
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= InfoBox::widget([
                'text' => Yii::t('app', 'Llaves por fuera'),
                'number' => $params['llaves']['num_salida'],
                'theme' => 'gradient-info',
                'icon' => 'fas fa-key',
            ]) ?>
        </div>
        <div class="col-md-6 ">
            <?php $numPorcentaje = (float) $params['llaves']['porcentaje_salida']; ?>
            <?= InfoBox::widget([
                'text' => '<div class="progress">
                                <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$numPorcentaje.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$numPorcentaje.'%">
                                 <span class="sr-only">'.$numPorcentaje.' '.Yii::t('app', 'indexLlavesFuera').'</span>
                                </div>
                              </div>
                              ',
                'number' => '<small>
                               '.$params['llaves']['porcentaje_salida'].' '.Yii::t('app', 'indexLlavesFuera').'
                              </small>',
                'theme' => 'gradient-default',
                'icon' => 'fas fa-sign-out-alt',
            ]) ?>
        </div>
    </div>

    <div class="col-md-12 card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= Yii::t('app', 'indexTituloCliente') ?></h3>
        </div>
        <div class="card-body">

                <?php
                $gridColumns = [
                    [
                        'attribute' => 'descripcion',
                        'label' => Yii::t('app', 'Cliente'),
                        'headerOptions' => ['style' => 'width: 40%'],
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model){
                            return $model->descripcion;
                        }
                    ],
                    [
                        'attribute' => 'total',
                        'label' => Yii::t('app', 'Cant. llaves'),
                        'headerOptions' => ['style' => 'width: 15%'],
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model){
                            return $model->total;
                        }
                    ],
                    [
                        'attribute' => 'salida',
                        'label' => Yii::t('app', 'Llaves por fuera'),
                        'headerOptions' => ['style' => 'width: 15%'],
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model){
                            return $model->salida;
                        }
                    ],
                    [
                        'attribute' => 'salida',
                        'label' => '% '.Yii::t('app', 'Llaves por fuera'),
                        'headerOptions' => ['style' => 'width: 30%'],
                        'format' => 'raw',
                        'enableSorting' => false,
                        'value' => function($model){
                            $numPrc = round(((100/$model->total)*$model->salida),2);

                            switch ($numPrc){
                                case ($numPrc>10 && $numPrc<=30):
                                    $strColor = 'info';
                                    break;
                                case ($numPrc>30 && $numPrc<=60):
                                    $strColor = 'warning';
                                    break;
                                case ($numPrc>60 && $numPrc<=90):
                                    $strColor = 'danger';
                                    break;
                                default:
                                    $strColor = 'dark';
                                    break;
                            }
                            return "<div class='progress progress-sm'>
                                 <div class='progress-bar bg-".$strColor."' role='progressbar' aria-valuenow='".$numPrc."' aria-valuemin=0 aria-valuemax=100 style='width: ".$numPrc."%'></div>
                                </div>
                                <small>".$numPrc."% ".Yii::t('app', 'Llaves por fuera')."</small>";
                        }
                    ],
                ];
                $addHtmlGrid  = '<div id="GridViewReportClientes" >'.ExportMenu::widget([
                        'dataProvider' => $params['llavesDataProvider']['cliente'],
                        'columns' => $gridColumns,
                        'dropdownOptions' => [
                            'label' => '',
                            'class' => 'btn btn-default',
                        ],
                        'showConfirmAlert' => false,
                        'exportContainer' => [
                            'class' => 'btn-group mr-2'
                        ],
                        'filename' => Yii::t('app', 'ReportLlavesCliente'),
                        'exportConfig' => [
                            ExportMenu::FORMAT_HTML => false,
                            ExportMenu::FORMAT_EXCEL => false,
                            ExportMenu::FORMAT_TEXT => false,
                            ExportMenu::FORMAT_PDF => false,
                            ExportMenu::FORMAT_CSV => [
                                'label' => Yii::t('app', 'CSV'),
                            ],
                            ExportMenu::FORMAT_EXCEL_X => [
                                'label' => Yii::t('app', 'Excel'),
                            ],
                        ],
                    ]).'</div>';
                $addHtmlGrid .= GridView::widget([
                    'dataProvider' => $params['llavesDataProvider']['cliente'],
                    'id' => 'GridViewCliente',
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'columns' => $gridColumns,
                    'summary'=>'',
                ]);

                echo $addHtmlGrid;
                ?>

        </div>
        <!-- /.card-body -->
    </div>

    <div class="col-md-12 card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= Yii::t('app', 'indexTituloPropietario') ?></h3>
        </div>
        <div class="card-body">

            <?php
            $gridColumns = [
                [
                    'attribute' => 'descripcion',
                    'label' => Yii::t('app', 'Propietario'),
                    'headerOptions' => ['style' => 'width: 40%'],
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($model){
                        return $model->descripcion;
                    }
                ],
                [
                    'attribute' => 'total',
                    'label' => Yii::t('app', 'Cant. llaves'),
                    'headerOptions' => ['style' => 'width: 15%'],
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($model){
                        return $model->total;
                    }
                ],
                [
                    'attribute' => 'salida',
                    'label' => Yii::t('app', 'Llaves por fuera'),
                    'headerOptions' => ['style' => 'width: 15%'],
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($model){
                        return $model->salida;
                    }
                ],
                [
                    'attribute' => 'salida',
                    'label' => '% '.Yii::t('app', 'Llaves por fuera'),
                    'headerOptions' => ['style' => 'width: 30%'],
                    'format' => 'raw',
                    'enableSorting' => false,
                    'value' => function($model){
                        $numPrc = round(((100/$model->total)*$model->salida),2);
                        switch ($numPrc){
                            case ($numPrc>10 && $numPrc<=30):
                                $strColor = 'info';
                                break;
                            case ($numPrc>30 && $numPrc<=60):
                                $strColor = 'warning';
                                break;
                            case ($numPrc>60 && $numPrc<=90):
                                $strColor = 'danger';
                                break;
                            default:
                                $strColor = 'dark';
                                break;
                        }
                        return "<div class='progress progress-sm'>
                                 <div class='progress-bar bg-".$strColor."' role='progressbar' aria-valuenow='".$numPrc."' aria-valuemin=0 aria-valuemax=100 style='width: ".$numPrc."%'></div>
                                </div>
                                <small>".$numPrc."% ".Yii::t('app', 'Llaves por fuera')." </small>";
                    }
                ],

            ];
            $addHtmlGrid  = '<div id="GridViewReportPropietarios" >'.ExportMenu::widget([
                    'dataProvider' => $params['llavesDataProvider']['propietario'],
                    'columns' => $gridColumns,
                    'dropdownOptions' => [
                        'label' => '',
                        'class' => 'btn btn-default',
                    ],
                    'showConfirmAlert' => false,
                    'exportContainer' => [
                        'class' => 'btn-group mr-2'
                    ],
                    'filename' => Yii::t('app', 'ReportLlavesPropietario'),
                    'exportConfig' => [
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_CSV => [
                            'label' => Yii::t('app', 'CSV'),
                        ],
                        ExportMenu::FORMAT_EXCEL_X => [
                            'label' => Yii::t('app', 'Excel'),
                        ],
                    ],
                ]).'</div>';
            $addHtmlGrid .= GridView::widget([
                'dataProvider' => $params['llavesDataProvider']['propietario'],
                'id' => 'GridViewPropietario',
                'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                'columns' => $gridColumns,
                'summary'=>'',
            ]);

            echo $addHtmlGrid;
            ?>
        </div>
        <!-- /.card-body -->
    </div>
</div>
<?php $this->registerJs(
    "$(document).on('onclick', '[data-js-url]', function () {
         //alert($(this).data('js-url'));
        }); "
);
?>