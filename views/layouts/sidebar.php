<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Html;

/* @var $assetDir Url */
$strUserName = (!empty(Yii::$app->user) && isset(Yii::$app->user) && isset(Yii::$app->user->identity) && isset(Yii::$app->user->identity->username)) ? Yii::$app->user->identity->username:null;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Yii::$app->getHomeUrl() ?>" class="brand-link">
        <?= Html::img('@web/img/logo.png', ['width' => '100%', 'width-max' => '200', 'alt' => 'AdminKeys', 'class'=>'brand-image img-circle elevation-3', 'style'=>'opacity: .8']); ?>
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
                        'label' => Yii::t('app', 'Administrador'),
                        'icon' => 'tachometer-alt',
                        'items' => [
                            ['label' => Yii::t('app','Usuarios'), 'url' => ['user/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app','Tipo Llave'), 'url' => ['tipo-llave/index'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => Yii::t('app', 'Clientes').'/'.Yii::t('app', 'Proveedores'),
                        'icon' => 'fa-solid fa-address-book',
                        'items' => [
                            ['label' => Yii::t('app', 'Clientes'), 'url' => ['comunidad/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app', 'Llaves'), 'url' => ['llave/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app', 'Propietarios'), 'url' => ['propietarios/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app', 'Proveedores'), 'url' => ['comerciales/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app', 'Formato Contratos'), 'url' => ['contratos/index'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => Yii::t('app', 'Operaciones Masivas'),
                        'icon' => 'fa-solid  fa-file-import',
                        'items' => [
                            ['label' => Yii::t('app','Importar Llaves'), 'url' => ['operaciones/llaves'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app','Importar Movimientos'), 'url' => ['operaciones/registros'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => Yii::t('app','Administración'),
                        'icon' => 'fa-solid fa-file-invoice',
                        'items' => [
                            ['label' => Yii::t('app','Contratos'), 'url' => ['contratos/generar-list'], 'iconStyle' => 'far'],
                        ],
                        'visible' => ((int) Yii::$app->user->identity->perfiluser->id_perfil==1)
                    ],
                    [
                        'label' => Yii::t('app','Reportes'),
                        'icon' => 'fa-solid fa-file-excel',
                        'items' => [
                            ['label' => Yii::t('app','Albaranes'), 'url' => ['registro/index'], 'iconStyle' => 'far'],
                            ['label' => Yii::t('app','Existencias Llaves'), 'url' => ['llave/report'], 'iconStyle' => 'far'],
                        ],
                    ],
                    ['label' => Yii::t('app','Registro'),  'icon' => 'fa-solid fa-key', 'url' => ['registro/create']],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank','visible' => YII_ENV_DEV],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank','visible' => YII_DEBUG],
                ],
            ]);
            ?>
        </nav>
        <i class="fa-light fa-pen-to-square"></i>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>