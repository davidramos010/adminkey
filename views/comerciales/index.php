<?php

use app\models\Comerciales;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\export\ExportMenu;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\ComercialesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';
?>
<div class="comerciales-index">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'En cada formulario el sistema validara que no se repitan los campos:',
                        'En caso que esta combinación ya exista, el sistema recomienda editar el registro existente para no crear un nuevo registro.'
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
                    $gridColumns =[
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'attribute' => 'nombre',
                                        'label' => 'Nombre',
                                        'headerOptions' => ['style' => 'width: 20%']
                                    ],
                                    [
                                        'attribute' => 'contacto',
                                        'label' => 'Persona/Contacto',
                                        'headerOptions' => ['style' => 'width: 20%']
                                    ],
                                    [
                                        'attribute' => 'telefono',
                                        'label' => 'Teléfono',
                                        'headerOptions' => ['style' => 'width: 20%']
                                    ],
                                    [
                                        'attribute' => 'direccion',
                                        'label' => 'Dirección',
                                        'headerOptions' => ['style' => 'width: 20%']
                                    ],
                                    [
                                        'attribute' => 'estado',
                                        'label' => 'Estado',
                                        'headerOptions' => ['style' => 'width: 10%'],
                                        'value' => function ($model) {
                                            return ($model->estado==1)?'<span class="float-none badge bg-success">ACTIVO</span>':'<span class="float-none badge bg-danger">INACTIVO</span>' ;
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
                                                    ['comerciales/update', 'id' => $model['id']],
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
                                                        ['comerciales/delete', 'id' => $model['id']],
                                                        [
                                                            'title' => Yii::t('common', 'Eliminar'),
                                                            'data' => [
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
                                ];
                ?>
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
                    'filename'        => Yii::t('app', 'ReportProveedor'),
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'columns' => $gridColumns
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</div>
