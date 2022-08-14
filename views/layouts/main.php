<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Html;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
$assetPlu = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/plugins');

$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
    <!-- Navbar -->
    <?php if(!empty(Yii::$app->user) && isset(Yii::$app->user) && !empty(Yii::$app->user->identity)): ?>
       <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
    <?php endif; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php if(isset(Yii::$app->user->isGuest) && !empty(Yii::$app->user->identity)): ?>
      <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>
    <?php endif; ?>
    <!-- Content Wrapper. Contains page content -->
    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <?php if(!empty(Yii::$app->user) && isset(Yii::$app->user) && !empty(Yii::$app->user->identity)): ?>
      <?= $this->render('control-sidebar') ?>
    <?php endif; ?>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?= $this->render('footer') ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
