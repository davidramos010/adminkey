<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=\yii\helpers\Url::home()?>" class="nav-link"><?= Yii::t('app','Inicio') ?></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-group-sm dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <?= Yii::t('app','Lang') ?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu small" style="width: 50px">
                    <li class="small" ><a href="<?= Url::toRoute(['change-lang', 'local' => 'en']) ?>">ENGLISH</a></li>
                    <li class="small" ><a href="<?= Url::toRoute(['change-lang', 'local' => 'ca']) ?>">CATALAN</a></li>
                    <li class="small" ><a href="<?= Url::toRoute(['change-lang', 'local' => 'es']) ?>">CALTELLANO</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
