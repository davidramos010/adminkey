<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
\hail812\adminlte3\assets\AdminLteAsset::register($this);
\hail812\adminlte3\assets\PluginAsset::register($this)->add(['sweetalert2', 'toastr']);
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
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
    <?php $this->registerCsrfMetaTags() ?>
    <title>AdminKey::<?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="m-0 justify-content-center">
<?php $this->beginBody() ?>
    <div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">
                <?php
                    if (!is_null($this->title)) {
                        echo Html::encode($this->title);
                    }
                ?>
            </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <?php
            echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => [
                    'class' => 'breadcrumb float-sm-right'
                ]
            ]);
            ?>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <div class="content" >
        <?= $content ?><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
<?php
// Impresión de alertas - Ejemplos
//    Yii::$app->session->setFlash('error', 'This is the message');
//    Yii::$app->session->setFlash('success', 'This is the message');
//    Yii::$app->session->setFlash('warning', 'This is the message');
//    Yii::$app->session->setFlash('info', 'This is the message');
$flashMessages = Yii::$app->session->getAllFlashes();
if ($flashMessages) {
    foreach($flashMessages as $key => $message) {
        $this->registerJs(" toastr.".$key."('".$message."'); ");
    }
}
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
