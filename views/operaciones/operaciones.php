<?php

use app\models\Registro;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\models\util;

/* @var $this yii\web\View */


$this->title = 'Registros';
$this->registerJsFile('@web/js/registro.js');
?>
<div class="registro-index">

    <div class="ribbon_wrap" >
        <div class="row">
            <div class="col-md-10">
                <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                    <?php
                    echo Html::ul([
                        'Listado de registro de entrada y salida de llaves.'
                    ], ['encode' => false]);

                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="d-flex justify-content-end">

                </div>
            </div>
        </div>

        <div class="card card-primary">
            <!-- /.card-header -->
            <div class="card-body">

            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
    </div>
</div>
