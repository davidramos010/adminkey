<?php
$strAddStyle = "margin-left: 0 !important;";
if (!empty(Yii::$app->user) && isset(Yii::$app->user) && !empty(Yii::$app->user->identity)):
    $strAddStyle = "";
endif;

?>
<footer class="main-footerain-footer" style="<?= $strAddStyle ?>">
    <strong>Copyright &copy; <?= date('Y') ?>.</strong>
    All rights reserved. AdminKeys<br>
    info@adminkeys.es
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.1.0 PRO
    </div>
</footer>