<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Html;
$strUserName = (!empty(Yii::$app->user) && isset(Yii::$app->user) && isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->username)) ? Yii::$app->user->identity->username:null;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?= $assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminKeys</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <?php if(empty(Yii::$app->user) && isset(Yii::$app->user) && !empty(Yii::$app->user->identity)): ?>
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?= Html::img('@web/img/user.png', ['width' => 160, 'alt' => 'User Image']); ?><br>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= $strUserName ?></a>
                </div>
            </div>
        <?php endif; ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo Menu::widget([
                'items' => [
                    [
                        'label' => 'Administrador',
                        'icon' => 'tachometer-alt',
                        'items' => [
                            ['label' => 'Usuarios', 'url' => ['user/index'], 'iconStyle' => 'far'],
                            ['label' => 'Tipo Llave', 'url' => ['tipo-llave/index'], 'iconStyle' => 'far'],
                            ['label' => 'Llaves', 'url' => ['llave/index'], 'iconStyle' => 'far'],
                            ['label' => 'Contratos', 'url' => ['contratos/index'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => 'Clientes/Proveedores',
                        'icon' => 'tachometer-alt',
                        'items' => [
                            ['label' => 'Cliente', 'url' => ['comunidad/index'], 'iconStyle' => 'far'],
                            ['label' => 'Propietarios', 'url' => ['propietarios/index'], 'iconStyle' => 'far'],
                            ['label' => 'Proveedor', 'url' => ['comerciales/index'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => 'Reportes',
                        'icon' => 'fa-solid fa-file-export',
                        'items' => [
                            ['label' => 'Reporte Movimientos', 'url' => ['registro/index'], 'iconStyle' => 'far'],
                            ['label' => 'Llaves', 'url' => ['llave/report'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    ['label' => 'Registro',  'icon' => 'fas fa-edit', 'url' => ['registro/create']],
                    ['label' => 'Contratos',  'icon' => 'fas fa-file-contract', 'url' => ['contratos/generar-list']],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    /*['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],*/
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>