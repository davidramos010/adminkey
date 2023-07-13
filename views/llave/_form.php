<?php

use app\components\Tools;
use app\models\Llave;
use app\models\LlaveNotas;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Llave */
/* @var $modelNota LlaveNotas */
/* @var $llaveNota LlaveNotas */
/* @var $form yii\widgets\ActiveForm */
/* @var $view bool */

$strStyleVisiblePropietario = isset($model->tipo) && $model->tipo->propietario ? '' : 'none';
$strStyleVisibleComunidad = isset($model->tipo) && $model->tipo->comunidad ? '' : 'none';
$strStyleVisibleBtnAdd = $model->isNewRecord ? '' : 'none';

$url_find_attributes = Url::toRoute(['llave/ajax-find-attributes']);
$url_find_code = Url::toRoute(['llave/ajax-find-code']);
$url_create_comunidad = Url::toRoute(['comunidad/ajax-create']);
$url_create_notas = Url::toRoute(['llave/ajax-set-llave-nota']);
$url_create_propietarios = Url::toRoute(['propietarios/ajax-create']);
$url_delete_notas = Url::toRoute(['llave/ajax-del-llave-nota']);
$url_add_copi_key = Url::toRoute(['llave/ajax-add-copi-key']);

?>
<div class="llave-form">
    <div class="row">
        <div class="col-md-12">
            <div class="ribbon_addon pull-right margin-r-5" style="margin-right: 3% !important">
                <?php
                echo Html::ul([
                    Yii::t('app','El código se genera automáticamente al momento de seleccionar la comunidad.'),
                        Yii::t('app','El sistema creará tantas llaves como copias existan.'),
                ], ['encode' => false]);
                ?>
            </div>
        </div>
    </div>

    <!-- form modal start -->
    <?= $this->render('info_comunidad') ?>
    <?= $this->render('info_propietario') ?>
    <?= isset($llaveNota) ? $this->render('info_nota',['llaveNota'=> $llaveNota,'model'=>$model ]) : "" ?>
    <!-- form modal end -->

    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><?= Yii::t('app', 'Llaves') ?></h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <?php $form = ActiveForm::begin(); ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 ">
                    <?= $form->field($model, 'id_tipo')->dropDownList(Llave::getTipoLlaveDropdownList(), ['onchange' => 'fnTipoLlaveSelected()', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view])->label(Yii::t('app','Tipo')); ?>
                </div>
            </div>
            <div class="row" id="divFormComunidad" style="display: <?= $strStyleVisibleComunidad ?>">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'id_comunidad')->dropDownList(Llave::getComunidadesDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view, 'data-js-find-nomenclatura'=>'comunidad'])->label(Yii::t('app','Comunidad')); ?>
                </div>
                <div class="col-md-1 " style="vertical-align: bottom">
                    <div class="form-group field-id_comunidad_modal" style="display: <?= $strStyleVisibleBtnAdd ?>">
                        <label class="control-label" for="id_comunidad_modal"><?= Yii::t('app', 'Adicionar') ?></label><br/>
                        <?= Html::a('<i class="fas fa-info-circle"></i>', ['comunidad/create-modal'], ['class' => 'btn btn-success', 'id' => 'btn-modal-comunidad', 'title' => Yii::t('app', 'Nueva Comunidad')]); ?>
                    </div>
                </div>
            </div>
            <div class="row" id="divFormPropietario" style="display: <?= $strStyleVisiblePropietario ?>">
                <div class="col-md-6 ">
                    <?= $form->field($model, 'id_propietario')->dropDownList(Llave::getPropietariosDropdownList(), ['id' => 'id_propietario', 'class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view, 'data-js-find-nomenclatura'=>'propietario'])->label(Yii::t('app','Propietario')); ?>
                </div>
                <div class="col-md-1" style="vertical-align: bottom">
                    <div class="form-group field-id_propietario_modal"  style="display: <?= $strStyleVisibleBtnAdd ?>">
                        <label class="control-label" for="id_propietario_modal"><?= Yii::t('app', 'Adicionar') ?></label><br/>
                        <?= Html::a('<i class="fas fa-info-circle"></i>', ['propietarios/create-modal'], ['class' => 'btn btn-success', 'id' => 'btn-modal-propietario', 'title' => Yii::t('app', 'Nuevo Propietario')]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'id_llave_ubicacion')->dropDownList(Llave::getUbicacionDropdownList(), ['class' => 'form-control', 'prompt' => 'Seleccione Uno', 'readonly' => $view])->label(Yii::t('app', 'Ubicación Almacenamiento')); ?>
                </div>
            </div>
            <div class="row">
                <?php if (!$view): ?>
                    <div class="col-md-1 ">
                        <?= $form->field($model, 'nomenclatura')->textInput(['id' => 'llave-nomenclatura', 'maxlength' => true, 'class' => 'form-control', 'readonly' => true])->label('_') ?>
                    </div>
                <?php endif; ?>
                <div class="col-md-2 ">
                    <?= $form->field($model, 'codigo')->textInput(['id' => 'llave-codigo', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view])->label(Yii::t('app', 'Código').' '. Yii::t('app', 'Llave')) ?>
                </div>
                <div class="col-md-1 ">
                    <?php if (!$view): ?>
                        <?= $form->field($model, 'alarma')->widget(SwitchInput::class, ['id' => 'alarma', 'pluginOptions' => ['id' => 'alarma', 'size' => 'small', 'onText' => 'SI', 'offText' => 'NO'], 'pluginEvents' => ["switchChange.bootstrapSwitch" => "function(item) { if($(item.currentTarget).is(':checked')){ $('#codigo_alarma').val('').prop('readonly', false); }else{ $('#codigo_alarma').val('').prop('readonly', true);} }"]])->label('Alarma'); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'alarma')->textInput(['id' => 'alarma', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view, 'value' => ($model->alarma) ? 'SI' : 'NO'])->label(Yii::t('app','Alarma')) ?>
                    <?php endif; ?>
                </div>
                <div class="col-md-2 ">
                    <?= $form->field($model, 'codigo_alarma')->textInput(['id' => 'codigo_alarma', 'maxlength' => true, 'class' => 'form-control', 'readonly' => true])->label(Yii::t('app', 'Código').' '. Yii::t('app', 'Alarma')) ?>
                </div>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, 'class' => 'form-control', 'readonly' => $view])->label(Yii::t('app', 'Descripción')) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'observacion')->textArea(['class' => 'form-control', 'readonly' => $view])->label(Yii::t('app', 'Observaciones')) ?>
            </div>
            <?php if($model->isNewRecord): ?>
                <div class="form-group" style="display: <?= $model->isNewRecord ? 'Inline' : 'none' ?>" >
                    <?= $form->field($modelNota, 'nota')->textArea(['class' => 'form-control', 'readonly' => $view])->label(Yii::t('app', 'Notas')) ?>
                </div>
            <?php else: ?>
                <div class="form-group " style="align-content: center; display: <?= !$model->isNewRecord ? 'Inline' : 'none' ?>" >
                    <div class="col-md-12 ">
                        <table id="tblNotasList" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th style="width: 60%">Nota</th>
                            <th style="width: 17%">Fecha</th>
                            <th style="width: 18%">Usuario</th>
                            <th style="width: 5%"><?= Html::button('<i class="fas fa-plus"></i>', ['class' => 'btn btn-success btn-xs', 'id' => 'btn-modal-addnota', 'title' => Yii::t('app', 'Nueva Nota')]); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $modelNota as $valueNota ): ?>
                            <tr id="tableNotaRow_<?= $valueNota['id'] ?>" >
                                <td><?= $valueNota['nota']; ?></td>
                                <td><?= Tools::getDateTimeFormatedSqlToUser($valueNota['created']) ; ?></td>
                                <td><?= $valueNota->user->name; ?></td>
                                <td><?= Html::button('<i class="fas fa-trash-alt"></i>', ['class' => 'btn btn-danger btn-xs deleteRowButton', 'onclick' => 'fnDelNotaLlave('.$valueNota['id'].') ']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="4"><?= Yii::t('app', 'Historico de notas.') ?></th>
                        </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'copia')->textInput(['type' => 'number', 'maxlength' => true, 'style'=>'width:50%;', 'class' => 'form-control', 'readonly' => !$model->isNewRecord])->label(Yii::t('app', 'Número de copias')) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 ">
                    <?php if (!$view): ?>
                        <?= $form->field($model, 'facturable')->widget(SwitchInput::class, ['id' => 'facturable', 'pluginOptions' => ['id' => 'facturable', 'size' => 'small', 'onText' => 'SI', 'offText' => 'NO']])->label(Yii::t('app', 'Facturable')); ?>
                    <?php else: ?>
                        <?= $form->field($model, 'facturable')->textInput(['id' => 'facturable', 'maxlength' => true, 'class' => 'form-control', 'readonly' => $view, 'value' => ($model->facturable) ? 'SI' : 'NO'])->label(Yii::t('app', 'Facturable')) ?>
                    <?php endif; ?>

                </div>
            </div>
            <?php if (!$view): ?>
                <div style="padding-top: 15px">
                    <?= Html::submitButton(Yii::t('app','Guardar').' '.Yii::t('app','Llave'), ['class' => 'btn btn-success ']) ?>
                    <?= Html::a(Yii::t('app', 'Cancelar'), ['index'], ['class' => 'btn btn-default ']) ?>
                </div>
            <?php endif; ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

$this->registerJs(
    <<<JS
    const strUrlFindAttributes = '$url_find_attributes';
    const strUrlFindCode = '$url_find_code';
    const strUrlCreateComunidad = '$url_create_comunidad';
    const strUrlCreateNotas = '$url_create_notas';
    const strUrlDeleteNotas = '$url_delete_notas';
    const strUrlCreatePropietarios = '$url_create_propietarios';
    const strUrlAddCopiKey = '$url_add_copi_key';
    
    
JS
    , $this::POS_HEAD);

$this->registerJs(
    '$(document).on("click", "[data-js-find-nomenclatura]", function (e) {
            findCodeLlave();
        });'
);

$this->registerJs(
    '$("#codigo_alarma").on("change", function() {
            activeCodAlarma();
        });'
);
$this->registerJs(
'$("#btn-modal-comunidad").click(function(e){
       e.preventDefault();      
       $("#modal-comunidad").modal("show")
                  .find(".modal-content")
                  .load($(this).attr("href"));  
       }); '
);

$this->registerJs(
    '$("#btn-modal-propietario").click(function(e){
       e.preventDefault();      
       $("#modal-propietario").modal("show")
                  .find(".modal-content")
                  .load($(this).attr("href"));  
       }); '
);

$this->registerJs(
    '$("#btn-modal-addnota").click(function(e){
       e.preventDefault();      
       $("#modal-addnota").modal("show");  
       }); '
);

$this->registerJs(
    '$(document).on("click", "[data-js-set-propietario]", function (e) {
            fnSetPropietario();
        });'
);

$this->registerJs(
    '$(document).on("click", "[data-js-set-comunidad]", function (e) {
            fnSetComunidad();
        });'
);

$this->registerJs(
    '$(document).on("click", "[data-js-set-nota]", function (e) {
            fnSetNotaLlave();
        });'
);
?>
