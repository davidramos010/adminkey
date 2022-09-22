<?php

use app\models\Propietarios;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\models\User;
use hail812\adminlte3\yii\grid\ActionColumn;
use kartik\icons\Icon;
use kartik\widgets\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropietariosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Propietarios');
?>
<div class="container-fluid">

    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Administración de propietarios/representantes de activos.',
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">
                    <?= Html::a('Crear Registro',['create'],['class' => 'btn btn-success']);  ?>
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
                                  'attribute' => 'nombre_propietario',
                                  'label' => 'Nombre Prop.',
                                  'headerOptions' => ['style' => 'width: 15%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return (isset($model->nombre_propietario))? strtoupper($model->nombre_propietario) :'' ;
                                  }
                              ],
                              [
                                  'attribute' => 'documento_propietario',
                                  'label' => 'Ident. Prop.',
                                  'headerOptions' => ['style' => 'width: 10%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return (isset($model->tipo_documento_propietario) && isset($model->documento_propietario))? '<span class="float-none badge bg-default">'.Propietarios::getTipoDocmento($model->tipo_documento_propietario) .'</span> '.strtoupper($model->documento_propietario) :'' ;
                                  }
                              ],
                              [
                                  'attribute' => 'nombre_representante',
                                  'label' => 'Nombre Repr.',
                                  'headerOptions' => ['style' => 'width: 15%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return (isset($model->nombre_representante))? strtoupper($model->nombre_representante) :'' ;
                                  }
                              ],
                              [
                                  'attribute' => 'documento_representante',
                                  'label' => 'Ident. Repr.',
                                  'headerOptions' => ['style' => 'width: 10%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return (isset($model->tipo_documento_representante) && isset($model->documento_representante))? '<span class="float-none badge bg-default">'.Propietarios::getTipoDocmento($model->tipo_documento_representante) .'</span> '.strtoupper($model->documento_representante) :'' ;
                                  }
                              ],
                              [
                                  'attribute' => 'direccion',
                                  'label' => 'Dirección',
                                  'headerOptions' => ['style' => 'width: 15%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return (isset($model->direccion))? strtoupper($model->direccion) :'' ;
                                  }
                              ],
                              [
                                  'attribute' => 'poblacion',
                                  'label' => 'Población',
                                  'headerOptions' => ['style' => 'width: 10%'],
                              ],
                              [
                                  'attribute' => 'cod_postal',
                                  'label' => 'Cod.Postal',
                                  'headerOptions' => ['style' => 'width: 5%'],
                              ],
                              [
                                  'attribute' => 'movil',
                                  'label' => 'Movil Teléfono',
                                  'headerOptions' => ['style' => 'width: 10%'],
                                  'format' => 'raw',
                                  'value' => function($model){
                                      return $model->movil.' '.$model->telefono;
                                  }
                              ],
                              [
                                  'class' => '\kartik\grid\ActionColumn',
                                  'header' => '',
                                  'headerOptions' => array('style' => 'width: 100%'),
                                  'mergeHeader' => false,
                                  'template' => ' {update} ',
                                  'width'=>'70px',
                                  'vAlign'=>GridView::ALIGN_MIDDLE,
                                  'hAlign'=>GridView::ALIGN_LEFT,
                                  'buttons' => [
                                      'update' => function ($url, $model) {
                                          $viewButton = Html::a(
                                              Html::button('<i class="fas fa-pen"></i>', ['class' => 'btn btn-primary btn-xs'] ),
                                              ['propietarios/update', 'id' => $model['id']],
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
                          ]
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
                    'filename'        => Yii::t('app', 'ReportPropietarios'),
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

    <!--.row-->
</div>
