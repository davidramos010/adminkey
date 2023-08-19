<?php

use app\models\TipoLlave;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoLlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Llaves';
?>
<div class="tipo-llave-index">
    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Este módulo solo es accedido por el administrador.',
                    ], ['encode' => false]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">
                    <?php
                    //echo Html::a('Crear Registro',['create'],['class' => 'btn btn-success']);
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
                        'id',
                        'codigo',
                        'descripcion',

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


