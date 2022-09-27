<?php


use hail812\adminlte\widgets\InfoBox;

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $params array */

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <?= \hail812\adminlte\widgets\Alert::widget([
                'type' => 'success',
                'body' => '<h3>Notas Importantes 123</h3>',
            ]) ?>
            <?= \hail812\adminlte\widgets\Callout::widget([
                'type' => 'danger',
                'head' => 'Notas Importantes - Alertas!',
                'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tincidunt bibendum tellus nec convallis. Maecenas euismod nulla nec scelerisque rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. In hendrerit dapibus euismod. Etiam sed justo tempus, eleifend libero at, porta risus.'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= InfoBox::widget([
                'text' => 'Cantidad de Llaves',
                'number' => $params['llaves']['num_llaves'] ,
                'theme' => 'gradient-success',
                'icon' => 'far fa-flag',
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= InfoBox::widget([
                'text' => 'Llaves con Salida',
                'number' => $params['llaves']['num_salida'],
                'theme' => 'gradient-info',
                'icon' => 'far fa-flag',
            ]) ?>
        </div>
        <div class="col-md-4 ">
            <?php $numPorcentaje = (float) $params['llaves']['porcentaje_salida']; ?>
            <?= InfoBox::widget([
                'text' => '<div class="progress">
                                <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$numPorcentaje.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$numPorcentaje.'%">
                                 <span class="sr-only">'.$numPorcentaje.'% de llaves por fuera</span>
                                </div>
                              </div>
                              ',
                'number' => '<small>
                               '.$params['llaves']['porcentaje_salida'].'% de llaves por fuera 
                              </small>',
                'theme' => 'gradient-default',
                'icon' => 'far fa-copy',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'CPU Traffic',
                'number' => '10 <small>%</small>',
                'icon' => 'fas fa-cog',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Messages',
                'number' => '1,410',
                'icon' => 'far fa-envelope',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Bookmarks',
                'number' => '410',
                 'theme' => 'success',
                'icon' => 'far fa-flag',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Uploads',
                'number' => '13,648',
                'theme' => 'gradient-warning',
                'icon' => 'far fa-copy',
            ]) ?>
        </div>
    </div>

</div>