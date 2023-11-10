<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveUbicaciones;
use app\models\Propietarios;
use app\models\TipoLlave;
use kartik\grid\GridView;
use Yii;
use yii\bootstrap4\Html;
use yii\console\Controller;
use yii\console\ExitCode;

use app\utils\HOF;
use Exception;
use GuzzleHttp\Client;
use yii\db\Exception as dbException;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author David Ramos <qiang.xue@gmail.com>
 * @since 2.0
 */
class ReportController extends Controller
{
    /**
     * Este comando envia un reporte via email, informando las llaves que están en prestamo y aun no se devuelven
     * @return int
     * @throws \Throwable
     * @throws dbException
     */
    public function actionReporteMensual(): int
    {

        $strAddCss = "table {
                           width: 100%;
                           border: 1px solid #999;
                           text-align: left;
                           border-collapse: collapse;
                           margin: 0 0 1em 0;
                           caption-side: top;
                           color: #002c59;
                        }
                        caption, td, th {
                           padding: 0.3em;
                        }
                        th, td {
                           border-bottom: 1px solid #999;
                           width: 25%;
                        }
                        caption {
                           font-weight: bold;
                           font-style: italic;
                        }";

        $gridColumns = [
            [
                'attribute' => 'id_llave',
                'label' => 'Código',
                'headerOptions' => ['style' => 'width: 10%; '],
                'format' => 'raw',
                'enableSorting' => false,
                'value' =>function($model){
                    return $model->llave->codigo;
                }
            ],
            [
                'attribute' => 'id_llave',
                'label' => 'Descripción',
                'headerOptions' => ['style' => 'width: 15%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return isset($model->llave)?$model->llave->descripcion:'NA';
                }
            ],
            [
                'attribute' => 'id_llave',
                'label' => 'Cliente',
                'headerOptions' => ['style' => 'width: 15%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return (isset($model->llave->comunidad) && !empty($model->llave->comunidad))?$model->llave->comunidad->nombre:'';
                }
            ],
            [
                'attribute' => 'id_llave',
                'label' => 'Dirección',
                'headerOptions' => ['style' => 'width: 20%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return (isset($model->llave->comunidad) && !empty($model->llave->comunidad))?$model->llave->comunidad->poblacion.' '.$model->llave->comunidad->direccion:'';
                }
            ],
            [
                'attribute' => 'id_llave',
                'label' => 'Responsable',
                'headerOptions' => ['style' => 'width: 15%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return (isset($model->registro->comerciales) && !empty($model->registro->comerciales))?$model->registro->comerciales->nombre:'';
                }
            ],
            [
                'attribute' => 'id_llave',
                'label' => 'Teléfono',
                'headerOptions' => ['style' => 'width: 10%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return (isset($model->registro->comerciales) && !empty($model->registro->comerciales))?$model->registro->comerciales->telefono.' '.$model->registro->comerciales->movil:'';
                }
            ],
            [
                'attribute' => 'fecha',
                'label' => 'Fecha Salida',
                'headerOptions' => ['style' => 'width: 15%'],
                'format' => 'raw',
                'enableSorting' => false,
                'value' => function($model){
                    return $model->fecha ;
                }
            ],
        ];
        $newModelLlave = new Llave();
        $arrData = $newModelLlave::getDataReport();
        $addHtmlGrid5 = GridView::widget([
            'dataProvider' => $arrData['llavesDataProvider'][5],
            'columns' => $gridColumns,
            'options' => ['style' => 'color: #000;border: 1px solid #ddd; border-collapse: collapse; width: 100%;'],
        ]);

        $addHtmlGrid10 = GridView::widget([
            'dataProvider' => $arrData['llavesDataProvider'][10],
            'columns' => $gridColumns,
            'options' => ['style' => 'color: #000;border: 1px solid #ddd; border-collapse: collapse; width: 100%;'],
        ]);

        $addHtmlGrid15 = GridView::widget([
            'dataProvider' => $arrData['llavesDataProvider'][15],
            'columns' => $gridColumns,
            'options' => ['style' => 'color: #000;border: 1px solid #ddd; border-collapse: collapse; width: 100%;'],
        ]);

        $numRegTotal5 = $arrData['llavesDataProvider'][5]->getCount();
        $numRegTotal10 = $arrData['llavesDataProvider'][10]->getCount();
        $numRegTotal15 = $arrData['llavesDataProvider'][15]->getCount();

        // Add inline styles to the HTML table
        $tableHtml5 = Html::tag('div', $addHtmlGrid5, ['style' => $strAddCss]);
        $tableHtml10 = Html::tag('div', $addHtmlGrid10, ['style' => $strAddCss]);
        $tableHtml15 = Html::tag('div', $addHtmlGrid15, ['style' => $strAddCss]);

        $strTitulo = Yii::t('app', 'Reporte de Estado');
        $strFooter1 = Yii::t('app', 'Le recordamos que tiene derecho a dirigir sus reclamaciones ante las Autoridades de protección de datos.');
        $strFooter2 = Yii::t('app', 'Por favor, no responda a este correo, se trata de un correo automatizado.');

        $contentBody5 = empty($numRegTotal5) ? "" : "<tr><td align=\"center\" style=\";margin-top:25px;color: #002c59\"><p align=\"center\"> " . Yii::t('app', 'indexBody5a') . " <strong> " . $numRegTotal5 . "</strong> " . Yii::t('app', 'indexBody5b') . '</p>' . $tableHtml5 . "</td></tr>";
        $contentBody10 = empty($numRegTotal10) ? "" : "<tr><td align=\"center\" style=\";margin-top:25px;color: #002c59\"><p align=\"center\"> " . Yii::t('app', 'indexBody10a') . " <strong> " . $numRegTotal10 . "</strong> " . Yii::t('app', 'indexBod10b') . '</p>' . $tableHtml10 . "</td></tr>";
        $contentBody15 = empty($numRegTotal15) ? "" : "<tr><td align=\"center\" style=\";margin-top:25px;color: #002c59\"><p align=\"center\"> " . Yii::t('app', 'indexBody15a') . " <strong> " . $numRegTotal15 . "</strong> " . Yii::t('app', 'indexBody15b') . '</p>' . $tableHtml15 . "</td></tr>";

        $content = "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 100%; color: #000000;\">
                        <tr>
                            <td align=\"center\">
                                <img src=\"http://adminkeys.es/img/logo_adminkey_transparent.png\" style=\"width: 100px\"/>
                                <div class=\"login-logo\">
                                    <b>".$strTitulo."</b><br/>
                                </div>
                            </td>
                        </tr>
                        ".$contentBody5."
                        ".$contentBody10."
                        ".$contentBody15."
                        <tr>
                            <td>
                                <span style=\"font-size: 11px;\">
                                    <span style=\"font-family: arial,helvetica,sans-serif;\">
                                        <span style=\"color: #808080;\">".$strFooter1."</span>
                                    </span>
                                </span>
                                <br>
                                <span style=\"color: #808080;\">
                                    <span style=\"font-size: 11px;\">
                                        <span style=\"font-family: arial,helvetica,sans-serif;\"><strong><em>".$strFooter2."</em></strong></span>
                                    </span>
                                </span>
                            </td>
                        </tr>
                    </table>";
        Yii::$app->mail->compose("@app/mail/layouts/html",['content'=>$content])
            ->setFrom('soporte@adminkeys.es')
            ->setTo('dramos@adminkeys.es')
            ->setSubject('Email enviado desde Yii2-Swiftmailer')
            ->send();
        return ExitCode::OK;
    }
}
