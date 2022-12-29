<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use barcode\barcode\BarcodeGenerator as BarcodeGenerator;
use yii\bootstrap\Modal;

$this->registerJsFile('@web/js/llave.js');
?>
<!-- form start -->
<!-- info modal -->
<div class="modal fade" id="modal-comunidad" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <?= Yii::t('app', 'error_url_modal'); ?>
            </div>
        </div>
    </div>
</div>
<!-- form end -->

