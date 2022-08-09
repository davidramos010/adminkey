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

$this->title = 'Comunidads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comunidad-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create comunidad', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nombre',
            'dirección',
            'telefono1',
            'telefono2',
            //'contacto',
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
