<?php

use app\models\User;
use hail812\adminlte3\yii\grid\ActionColumn;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
?>

<div class="container-fluid">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Este módulo solo esta habilitado para el administrador.',
                        'Permite editar la información del usuario y su perfil.',
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
                       ['class' => 'yii\grid\SerialColumn'],
                       [
                           'attribute' => 'username',
                           'label' => 'User',
                           'format' => 'raw',
                           'headerOptions' => array('style' => 'width: 10%'),
                       ],
                       [
                           'attribute' => 'nombres',
                           'label' => 'Nombres',
                           'format' => 'raw',
                           'headerOptions' => array('style' => 'width: 20%'),
                       ],
                       [
                           'attribute' => 'apellidos',
                           'label' => 'Apellidos',
                           'format' => 'raw',
                           'headerOptions' => array('style' => 'width: 20%'),
                       ],
                       [
                           'attribute' => 'telefono',
                           'label' => 'Teléfono',
                           'format' => 'raw',
                           'headerOptions' => array('style' => 'width: 15%'),
                       ],
                       [
                           'attribute' => 'perfil',
                           'label' => 'Perfil',
                           'format' => 'raw',
                           'headerOptions' => array('style' => 'width: 15%'),
                           'filterType' => GridView::FILTER_SELECT2,
                           'filter' => User::getPerfilesDropdownList(),
                           'filterWidgetOptions' => array(
                               'theme' => Select2::THEME_BOOTSTRAP,
                               'size' => Select2::SMALL,
                               'pluginOptions' => array(
                                   'allowClear' => true,
                                   'placeholder' => 'Todos',
                               )
                           ),
                       ],
                       array(
                           'attribute' => 'estado',
                           'label' => 'Estado',
                           'headerOptions' => array('style' => 'width: 10%'),
                           'format' => 'raw',
                           'value' => function($model){
                               return ($model->estado==1)? '<span class="float-none badge bg-success">ACTIVO</span>':'<span class="float-none badge bg-danger">INACTIVO</span>' ;
                           },
                           'filterType' => GridView::FILTER_SELECT2,
                           'filter' => array('1' => 'ACTIVO', '0' => 'INACTIVO'),
                           'filterWidgetOptions' => array(
                               'theme' => Select2::THEME_BOOTSTRAP,
                               'size' => Select2::SMALL,
                               'pluginOptions' => array(
                                   'allowClear' => true,
                                   'placeholder' => 'Todos',
                               )
                           ),
                       ),
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
                                       ['user/update', 'id' => $model['id']],
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
                                            ['user/delete', 'id' => $model['id']],
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
                    'filename'        => Yii::t('app', 'ReportUsuarios'),
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
                    'columns' => $gridColumns,
                    'summaryOptions' => ['class' => 'summary mb-2'],
                    'pager' => [
                        'class' => 'yii\bootstrap4\LinkPager',
                    ]
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</div>
