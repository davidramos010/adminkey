<?php

use yii\helpers\Html;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminKeys</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <?php if(isset(Yii::$app->user->isGuest)): ?>
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?= Html::img('@web/img/user.png', ['width' => 160, 'alt' => 'User Image']); ?><br>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?></a>
                </div>
            </div>
        <?php endif; ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    [
                        'label' => 'Administrador',
                        'icon' => 'tachometer-alt',
                        'items' => [
                            ['label' => 'Comunidad', 'url' => ['comunidad/index'], 'iconStyle' => 'far'],
                            ['label' => 'Llaves', 'url' => ['llave/index'], 'iconStyle' => 'far'],
                            ['label' => 'Tipo Llave', 'url' => ['tipo-llave/index'], 'iconStyle' => 'far'],
                        ]
                    ],
                    ['label' => 'Registro',  'icon' => 'fas fa-edit', 'url' => ['registro/index']],
                    ['label' => 'Reporte',  'icon' => 'fas fa-edit', 'url' => ['registro/reporte']],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>