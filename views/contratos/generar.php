<?php

use app\models\Contratos;
use app\models\Llave;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\Html;

use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Contratos */
/* @var $model_log app\models\ContratosLog */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $searchModel app\models\LlaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJsFile('@web/js/contratos.js');

$this->title = Yii::t('app', 'Generar Contratos');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contratos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                                <?php
                                echo Html::ul([
                                    'El nombre del documento debe ser Unico',
                                    'Una vez se cumpla la fecha de finalización el contrato, no estará disponible para impresión.',
                                    'Los documentos deben ser extension doc/docx.'
                                ], ['encode' => false]);
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php $form = ActiveForm::begin([
                        'id' => 'generar-form', 'options' => ['enctype' => 'multipart/form-data']
                    ]); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 " >
                                <?= $form->field($model_log, 'id_contrato')->dropDownList( Contratos::getContratosDropdownList()  , ['class'=>'form-control', 'prompt' => 'Seleccione Uno' ])->label('Contrato'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" >
                                <?=
                                     $form->field($model_log, 'parametros')->widget(Select2::classname(), [
                                        'data' => [$model_log->parametros => $model_log->parametros],
                                        'options' => ['multiple'=>true, 'placeholder' => 'Seleccionar Llave ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 3,
                                            'language' => [
                                                'errorLoading' => new JsExpression("function () { return 'Buscando resultados...'; }"),
                                            ],
                                            'ajax' => [
                                                'url' => Url::to(['contratos/ajax-consultar-llaves']),
                                                'dataType' => 'json',
                                                'processResults' => new JsExpression('(data) => procesarResultadosLlave(data)'),
                                                'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                            ],
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function(llave) { console.log(llave); return llave.codigo; }'),
                                            'templateSelection' => new JsExpression('function (llave) { return llave.codigo; }'),
                                        ],
                                    ])->label('llaves');

                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 " >
                                <?php Pjax::begin(); ?>
                                <?php

                                $gridColumns = [
                                    [
                                        'class' => '\kartik\grid\CheckboxColumn',
                                        'checkboxOptions' =>
                                            function($model) {
                                                return ['id'=>'grid_chk_'.$model->id, 'value' => $model->id, 'class' => 'checkbox-row'];
                                            }
                                    ],
                                    [
                                        'attribute' => 'nombre_propietario',
                                        'label' => 'Propietario',
                                        'headerOptions' => ['style' => 'width: 15%'],
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return (isset($model->nombre_propietario))?strtoupper($model->nombre_propietario):'' ;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id_comunidad',
                                        'label' => 'Cliente',
                                        'headerOptions' => ['style' => 'width: 20%'],
                                        'format' => 'raw',
                                        'value' => function($model){
                                            return (isset($model->comunidad))?strtoupper($model->comunidad->nombre):'No Encontrado' ;
                                        }
                                    ],
                                    [
                                        'attribute' => 'id_tipo',
                                        'label' => 'Tipo Llave',
                                        'headerOptions' => ['style' => 'width: 5%'],
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
                                    ],
                                    [
                                        'attribute' => 'descripcion',
                                        'label' => 'Descripción',
                                        'headerOptions' => ['style' => 'width: 15%'],
                                    ],
                                    [
                                        'attribute' => 'llaveLastStatus',
                                        'label' => 'Estado',
                                        'headerOptions' => ['style' => 'width: 5%'],
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

                                ]; ?>
                                <?= GridView::widget([
                                    'id' => 'grid_llaves',
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'formatter' => array('class' => 'yii\i18n\Formatter', 'nullDisplay' => ''),
                                    'columns' => $gridColumns,
                                ]); ?>
                                <?php Pjax::end(); ?>
                            </div>
                        </div>
                        <div  style="padding-top: 15px" >
                            <?= Html::submitButton('Guardar Contrato', ['class' => 'btn btn-success ']) ?>
                            <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>