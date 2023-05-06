    <?php

use app\models\Llave;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Llaves';
$this->registerJsFile('@web/js/llave.js');
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
                            $strLabel = (isset($model->tipo))?strtoupper($model->tipo->descripcion):'No Encontrado' ;
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
                                case 4:
                                    $class = 'bg-secondary';
                                    break;
                                case 5:
                                    $class = 'bg-dark';
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
                                'placeholder' => '',
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'codigo',
                        'label' => 'Código',
                        'headerOptions' => ['style' => 'width: 5%'],
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
                        'headerOptions' => ['style' => 'width: 15%'],
                        'enableSorting'=>false,
                    ],
                    [
                        'attribute' => 'alarma',
                        'label' => 'Alarma',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {
                            return ($model->alarma==1)?'<span class="float-none badge bg-success">SI</span>':'<span class="float-none badge bg-danger">NO</span>' ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['1' => 'Si', '0' => 'No', ''=>'Todos'],
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
                                'placeholder' => '',
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'activa',
                        'label' => 'Activa',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'value' => function ($model) {
                            return ($model->activa==0)?'<span class="float-none badge bg-danger">Inactiva</span>':'<span class="float-none badge bg-success">Aactiva</span>' ;
                        },
                        'format' => 'raw',
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ['1' => 'Si', '0' => 'No', ''=>'Todos'],
                        'filterWidgetOptions' => [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::SMALL,
                            'pluginOptions' => [
                                'allowClear' => true,
                                'placeholder' => '',
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'id',
                        'label' => 'Info',
                        'headerOptions' => ['style' => 'width: 5%'],
                        'enableSorting'=>false,
                        'format' => 'raw',
                        'value' => function($model){
                            return '<button type="button" class="btn btn-outline-info btn-block btn-sm" data-toggle="modal" data-target="#modal-default" onclick="getInfoLlaveCard('.$model->id.')"><i class="fas fa-info-circle"></i></button> ';
                        }
                    ],
                    [
                        'class' => '\kartik\grid\ActionColumn',
                        'header' => '',
                        'headerOptions' => array('style' => 'width: 10%'),
                        'mergeHeader' => false,
                        'template' => '{view} {update} {delete} ',
                        'width'=>'70px',
                        'vAlign'=>GridView::ALIGN_MIDDLE,
                        'hAlign'=>GridView::ALIGN_LEFT,
                        'buttons' => [

                            'view' => function ($url, $model) {
                                $viewButton = Html::a(
                                    Html::button('<i class="fas fa-eye"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                    ['llave/view', 'id' => $model['id']],
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
                            'update' => function ($url, $model) {
                                $viewButton = Html::a(
                                    Html::button('<i class="fas fa-pen"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                    ['llave/update', 'id' => $model['id']],
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
                                if($model['activa']){
                                    $viewButton = Html::a(
                                        Html::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                        ['llave/delete', 'id' => $model['id']],
                                        [
                                            'title' => Yii::t('common', 'Eliminar'),
                                            'data' => [
                                                'confirm' => Yii::t('app', '¿Estás segura de que quieres eliminar este registro?'),
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
