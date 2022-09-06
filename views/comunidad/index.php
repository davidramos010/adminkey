<?php

use app\models\Comunidad;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ComunidadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
?>
<div class="comunidad-index">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'En cada formulario el sistema validará la nomenclatura asignada, pues ésta debe ser única en cada cliente.',
                        'En caso de que nomenclatura ya exista, el sistema recomienda editar el registro existente para no crear un nuevo registro.'
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
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        //'id',
                        'nombre',
                        'direccion',
                        'telefono1',
                        'telefono2',
                        'contacto',
                        //'nomenclatura',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Comunidad $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                        ],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>

</div>