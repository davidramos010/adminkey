<?php

use app\models\Contratos;
use app\models\Llave;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use rmrevin\yii\fontawesome\component\Icon;
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

$this->registerJsFile('@web/js/generar.js');

$this->title = Yii::t('app', 'Generar Contratos');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Contratos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$strFileIsVisible = $model_log->isNewRecord ? 'none' : 'inline';
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
                                    'Seleccione un formato de contrato',
                                    'Seleccione mínimo una llave para generar un contrato.',
                                    'Una vez se almacene la información, se podrá imprimir el formato tantas veces como se necesario.',
                                    'Cuando se registre una copia firmada, no se podrán aplicar más cambios en el contrato.'
                                ], ['encode' => false]);
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['action' =>['contratos/generar-contrato'],'id' => 'generar-form', 'options' => ['enctype' => 'multipart/form-data'] ]); ?>
                        <?= $form->field($model_log, 'parametros')->hiddenInput(['id'=>'parametros'])->label(false); ?>
                        <?= $form->field($model_log, 'id')->hiddenInput(['id'=>'id'])->label(false); ?>
                        <div class="row">
                            <div class="col-md-6 " >
                                <?= $form->field($model_log, 'id_contrato')->dropDownList( Contratos::getContratosDropdownList()  , ['class'=>'form-control', 'prompt' => 'Seleccione Uno', 'disabled'=>!empty($model_log->copia_firma) ])->label('Contrato'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 " >
                                <?= $form->field($model_log, 'observacion')->textArea(['id' => 'observaciones', 'class' => 'form-control', 'style' => 'width:100%', 'disabled'=>!empty($model_log->copia_firma) ])->label('Notas/Observaciones') ?>
                            </div>
                        </div>
                        <div class="row" style="display: <?= $strFileIsVisible ?>">
                            <div class="col-md-6" >
                                <?php $strLabel = (empty($model_log->copia_firma))?'':' * Ya existe un documento adjunto no puede registrar mas contratos'; ?>
                                <?= $form->field($model_log, 'copia_firma', ['options' => ['class' => 'file-uploader']])->widget(FileInput::class, [
                                    'language' => 'es',
                                    'options' => [
                                        'id' => 'copia_firma',
                                        'multiple' => false,
                                        'disabled' => (!empty($model_log->copia_firma)),
                                    ],
                                    'pluginOptions' => array_merge(
                                        [
                                            'showBrowse' => true,
                                            'showCaption' => true,
                                            'showRemove' => false,
                                            'showUpload' => false,
                                            'showPreview' => false,
                                            'initialCaption' => "Seleccione el documento firmado",
                                            'allowedFileTypes' => ['png', 'jpg', 'pdf', 'jpeg', 'gif', 'pdf', 'office', 'doc', 'docx'],
                                            'msgInvalidFileType' => 'El tipo de archivo de: {name} no es correcto. Sólo se admiten archivos del tipo "docx,pdf,imagenes".'

                                        ])
                                ])->label('Contrato Firmado ' . $strLabel)   ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                        <br/>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title"> <?= Yii::t('app', 'Selección de llaves') ?> </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8" >
                                <?php
                                    Pjax::begin(['id'=>'llaves']);
                                    $gridColumns = [
                                        [
                                            'attribute' => 'nombre_propietario',
                                            'label' => 'Propietario',
                                            'headerOptions' => ['style' => 'width: 20%'],
                                            'format' => 'raw',
                                            'value' => function($model){
                                                return (isset($model->nombre_propietario))?strtoupper($model->nombre_propietario):'' ;
                                            }
                                        ],
                                        [
                                            'attribute' => 'cliente_comunidad',
                                            'label' => 'Cliente',
                                            'headerOptions' => ['style' => 'width: 20%'],
                                            'format' => 'raw',
                                            'value' => function($model){
                                                return (isset($model->cliente_comunidad))?strtoupper($model->cliente_comunidad):'No Encontrado' ;
                                            }
                                        ],
                                        [
                                            'attribute' => 'id_tipo',
                                            'label' => 'Tipo Llave',
                                            'headerOptions' => ['style' => 'width: 10%'],
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
                                            'headerOptions' => ['style' => 'width: 10%'],
                                        ],
                                        [
                                            'attribute' => 'descripcion',
                                            'label' => 'Descripción',
                                            'headerOptions' => ['style' => 'width: 30%'],
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
                                        [
                                            'class' => '\kartik\grid\ActionColumn',
                                            'header' => '',
                                            'mergeHeader' => false,
                                            'template' => ' {add}',
                                            'vAlign'=>GridView::ALIGN_MIDDLE,
                                            'hAlign'=>GridView::ALIGN_LEFT,
                                            'buttons' => [
                                                'add' => function ($url, $model) {
                                                    $viewButton = Html::button(
                                                        '<i class="fas fa-plus"></i>',
                                                        [
                                                            'type' => 'button',
                                                            'class' => 'btn btn-primary btn-xs',
                                                            'data' => [
                                                                'js-id' => $model->id,
                                                            ]
                                                        ]
                                                    );
                                                    return $viewButton;
                                                },
                                            ]
                                        ]

                                    ]; ?>
                                    <?= GridView::widget([
                                        'id' => 'pestana-llaves',
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'resizableColumns' => false,
                                        'condensed' => true,
                                        'floatHeader' => false,
                                        'pjax' => true,
                                        'pjaxSettings' => [
                                            'options' => [
                                                'timeout' => false,
                                                'enablePushState' => false,
                                                'clientOptions' => ['method' => 'GET']
                                            ]
                                        ],
                                        'toolbar' => false,
                                        'columns' => $gridColumns,
                                    ]); ?>
                                    <?php Pjax::end(); ?>
                            </div>
                            <div class="col-md-4" style="padding-top: 23px">
                                <?php $form = ActiveForm::begin(['action' =>['contratos/generar-contrato'],'id' => 'generar-table' ]); ?>
                                    <table id="tblKeyCheck" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 35%">Codigo</th>
                                        <th style="width: 60%">Descripción</th>
                                        <th style="width: 5%"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <!-- Cuerpo -->
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="3"><?= Yii::t('app', 'Relación de llaves seleccionadas para impresion de contrato.') ?></th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div  style="padding-top: 15px" >
                            <?php if(!$model_log->copia_firma && empty($model_log->copia_firma)): ?>
                                <?= Html::button('Guardar Contrato', [ 'class' => 'btn btn-success', 'onclick' => '(function ( $event ) { sendForm() })();' ]); ?>
                            <?php endif; ?>
                            <?= Html::a(Yii::t('app', 'Cancelar'), ['generar-list'], ['class' => 'btn btn-default ']) ?>
                            <?php if(!$model_log->isNewRecord && !empty($model_log->copia_firma)): ?>
                                <?php $url = Yii::$app->urlManager->createUrl(['site/download','path'=>'/contratos_firmados/','file'=>$model_log->copia_firma]); ?>
                                <?= Html::a('<i class="fas fa-download"></i> Descargar Copia' , $url, ['title'=>'Descargar Copia', 'target' => '_blank', 'class' => 'btn btn-info download_link', 'data' => ['tooltip' => true, 'pjax' => 0 ]]);  ?>
                            <?php endif; ?>
                            <?php if(!$model_log->isNewRecord): ?>
                                <?= Html::a('<i class="fas fa-download"></i> Generar Contrato' , ['descargar-contrato', 'id' => $model_log->id], ['title'=>'Generar Contrato', 'target' => '_blank', 'class' => 'btn btn-warning download_link', 'data' => ['tooltip' => true, 'pjax' => 0 ]]);  ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- info modal -->
                    <div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Información de la llave</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table-responsive border-opacity-10">
                                        <tr class="table-head-fixed">
                                            <td style="width: 30%"></td>
                                            <td style="width: 70%"></td>
                                        </tr>
                                        <tr class="text-body"> <td class="text-bold">Propietario</td><td id="ll_propietario"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Cliente</td><td id="ll_cliente"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Tipo</td><td id="ll_tipo"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Código</td><td id="ll_codigo"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Descripción</td><td id="ll_descripcion"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Observación</td><td id="ll_observacion"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Alarma</td><td id="ll_alarma"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Ubicación</td><td id="ll_ubicacion"></td> </tr>
                                        <tr class="text-body"> <td class="text-bold">Estado</td><td id="ll_estado"></td> </tr>
                                    </table>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>


<?php

$this->registerJs('
        var fieldsChanged = false;
        $(document).on("change", "#generar-form :input", function(){
            fieldsChanged = true;
        });
        $(window).on("beforeunload", function(){
            if(fieldsChanged)
               return "Tiene cambios sin guardar, ¿está seguro de que desea salir de esta página?";
        });
');

$this->registerJs(
    "$(document).on('click', '[data-js-id]', function () {
            selectChk($(this).data('js-id')) ;
        });
        
        $( document ).ready(function() {
            fnReloadSeleccionLlaves();
        });  "
      );
?>