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
?>
<div class="container-fluid">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'El nombre del documento debe ser Unico',
                        'Una vez se cumpla la fecha de finalización el contrato, no estará disponible para impresión.'
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
                        'label' => 'Nombre',
                        'headerOptions' => ['style' => 'width: 15%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return strtoupper($model->nombre);
                        }
                    ],
                    [
                        'attribute' => 'estado',
                        'label' => 'Estado',
                        'headerOptions' => ['style' => 'width: 30%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return ($model->estado==1)?'<span class="float-none badge bg-success">ACTIVO</span>':'<span class="float-none badge bg-danger">ACTIVO</span>' ;
                        }
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
                            'placeholder' => Yii::t('app', 'Validado desde'),
                        ],
                        'label' => Yii::t('app', 'Validado desde'),
                        'headerOptions' => ['class' => 'col-xs-2'],
                        'contentOptions' => ['class' => 'text-center col-xs-2', 'style' => 'vertical-align: middle; ']
                    ],
                    [
                        'attribute' => 'parametros',
                        'label' => 'llaves',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'format' => 'raw',
                        'value' => function($model){
                            return $model->parametros;
                        }
                    ],
                    [
                        'attribute' => 'copia_firma',
                        'label' => 'Documento Firmado',
                        'headerOptions' => ['style' => 'width: 20%'],
                        'format' => 'raw',
                        'value' => function($model){
                            $url = Yii::$app->urlManager->createUrl(['site/download','path'=>'/plantillas/','file'=>$model->copia_firma]);
                            return Html::a('<i class="fas fa-download"></i>' , $url, ['title'=>'Descargar Plantilla', 'target' => '_blank', 'class' => 'box_button fl download_link', 'data' => ['tooltip' => true, 'pjax' => 0 ]])  ;
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
