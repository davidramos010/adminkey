<?php

use app\models\Llave;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Llaves';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="llave-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Llave', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_comunidad',
            'id_tipo',
            'copia',
            'codigo',
            //'descripcion',
            //'observacion',
            //'activa',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Llave $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
