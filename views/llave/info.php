<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use barcode\barcode\BarcodeGenerator;
use yii\bootstrap\Modal;

$this->registerJsFile('@web/js/llave.js');
?>
<!-- form start -->
<!-- info modal -->
<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?= Yii::t('app', 'Información movimientos de llave') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?= Yii::t('app', 'Historial de movimientos') ?></h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap" id="dataTable">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%"><?= Yii::t('app', 'Operación') ?></th>
                                        <th style="width: 10%"><?= Yii::t('app', 'Fecha') ?></th>
                                        <th style="width: 23%"><?= Yii::t('app', 'Empresa') ?></th>
                                        <th style="width: 23%"><?= Yii::t('app', 'Responsable') ?></th>
                                        <th style="width: 34%"><?= Yii::t('app', 'Observación') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="modal-email-contenido-table">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Cerrar') ?></button>
                <?= Html::button('Exportar Csv', [ 'class' => 'btn btn-primary', 'onclick' => '(function ( $event ) { fnExcelReport("dataTable") })();' ]); ?>
            </div>
        </div>
    </div>
</div>
<!-- form end -->

