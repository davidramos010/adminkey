<?php
namespace app\controllers;

use app\commands\LoadController;
use app\models\Comerciales;
use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveStatus;
use app\models\LlaveStatusSearch;
use app\models\LlaveUbicaciones;
use app\models\Perfiles;
use app\models\PerfilesUsuario;
use app\models\Propietarios;
use app\models\Registro;
use app\models\TipoLlave;
use app\models\User;
use app\models\util;
use kartik\grid\GridView;
use Yii;
use yii\bootstrap4\Html;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\widgets\Alert;


class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $arrParam = ['params'=>[]];
        $strActionForm = 'index';
        if (!Yii::$app->user->identity) {
            $arrReturn = self::setValidateUser();
            $strActionForm = $arrReturn[0];
            $arrParam = $arrReturn[1];
        }
        // --------------------------------
        // Parametros para usuarios que ya iniciaron sesion
        if (Yii::$app->user->identity) {
            // Cantidad de llave y llaves prestadas
            $arrParam = ['params'=>Llave::getDataHome()];
        }

        return $this->render($strActionForm,$arrParam);
    }

    /**
     * Validar user - sesion
     * @return string|Response
     */
    private function setValidateUser(): array
    {
        $model = new LoginForm();
        $srtNotificacion = '';
        if ($model->load(Yii::$app->request->post())) {
            if($model->login()){

                $session = Yii::$app->session;
                !$session->isActive ? $session->open() : $session->close();
                $session->set('language', 'es');
                $session->close();

                $objPerfil = PerfilesUsuario::find()->where(['id_user'=>Yii::$app->user->identity->id ])->one();
                $strReturn = PerfilesUsuario::getIndexPerfil($objPerfil,$model);
                if(!empty($strReturn)){
                    return $this->redirect($strReturn);
                }
                $srtNotificacion .= "Validar los permisos asignados. \n";
            }
            $srtNotificacion .= "Validar los parámetros ingresados. \n";
            Yii::$app->user->logout();
            Yii::$app->user->logout(true);
        }

        return ['login', [
            'model' => $model,
            'notificacion' => $srtNotificacion
        ]];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $objPerfil = PerfilesUsuario::find()->where(['id_user' => Yii::$app->user->identity->id])->one();
            if (!empty($objPerfil)) {
                if ($objPerfil->id_perfil == 1 && (int)$model->perfil == 1) {
                    return $this->goHome();
                }
                if (in_array((int)$objPerfil->id_perfil, [2, 3]) && in_array((int)$model->perfil, [2, 3])) {
                    return $this->redirect('../registro/create');
                }
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @return \yii\console\Response|Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDownload()
    {
        $file=Yii::$app->request->get('file');
        $path=Yii::$app->request->get('path');
        $root=Yii::getAlias('@webroot').$path.$file;
        if (file_exists($root)) {
            return Yii::$app->response->sendFile($root);
        } else {
            throw new \yii\web\NotFoundHttpException("{$file} is not found!");
        }
    }

    public function actionTestCargaLlavesMasivo()
    {
        $fileHandler=fopen("../web/documents/SGA_CONTROL_CLAUS.csv","r");
        if($fileHandler){
            $first_time = true;
            while($line=fgetcsv($fileHandler,1000)){
                if ($first_time == true) { // primera fila
                    $first_time = false;
                    continue;
                }
                $strCode = $line[0];
                $strOfic = $line[1];
                $strTipo = $line[2];
                $strAcceso = $line[4];
                $numCantidad = (int) $line[5];
                $strNombrePropietario = strtoupper($line[7]);
                $strMovilPropietario = trim($line[8]);
                $numAlarma = strtoupper(trim($line[9]));
                $strAlarma = trim($line[10]);
                $strFacturable = trim($line[11]);
                $strObservaciones = trim($line[12]);
                //Buscar comunidad
                $arrCode = explode('-',$strCode);

                $strNomenclatura = "C".$arrCode[0];
                $strCodigo = $arrCode[1];

                // consultar comunidad
                $objComunidad = Comunidad::find()->where(['nomenclatura'=>$strNomenclatura])->one();
                // ubicacion
                $objLlaveUbicacion = LlaveUbicaciones::find()->where(['descripcion_almacen'=>$strOfic])->one();
                // TIPO
                $objLlaveTipo = TipoLlave::find()->where(['descripcion'=>$strTipo])->one();

                // BUSCAR PARTICULAR Y CREARLO
                if($objLlaveTipo->descripcion=='PARTICULAR'){
                    $objParticular = Propietarios::find()->where(['like','nombre_propietario',$strNombrePropietario])->one();
                    if(empty($objParticular)){
                        $objParticular = new Propietarios();
                        $objParticular->nombre_propietario = $strNombrePropietario;
                        $objParticular->direccion = $objComunidad->direccion;
                        $objParticular->cod_postal = $objComunidad->cod_postal;
                        $objParticular->poblacion = $objComunidad->poblacion;
                        $objParticular->telefono = $strMovilPropietario;
                        $objParticular->movil = $strMovilPropietario;
                        $objParticular->save();
                    }
                }

                while ($numCantidad>0){
                    // Crear llave
                    $objNewLlave = new Llave();
                    $strCodigoLlave = $strNomenclatura."-".$strCodigo;
                    $strCodigoLlave .= ($numCantidad>1) ? '-'.$numCantidad:'';
                    $objNewLlave->id_comunidad = $objComunidad->id;
                    $objNewLlave->id_tipo = $objLlaveTipo->id;
                    $objNewLlave->id_llave_ubicacion = $objLlaveUbicacion->id;
                    $objNewLlave->copia = $numCantidad;
                    $objNewLlave->codigo = $strCodigoLlave;
                    $objNewLlave->descripcion = $strAcceso;
                    $objNewLlave->alarma = $numAlarma=='SI' ? 1 : 0;
                    $objNewLlave->codigo_alarma = $objNewLlave->alarma ? $strAlarma : NULL;
                    $objNewLlave->observacion = $strObservaciones;
                    $objNewLlave->facturable = ($strFacturable=='SI')?1:0;
                    if(isset($objParticular) && !empty($objParticular)){
                        $objNewLlave->id_propietario = $objParticular->id;
                    }
                    if(!$objNewLlave->save()){
                        die('Error:Code:'.$strCodigo);
                    }
                    echo "->".$objNewLlave->id."\\n";
                    $numCantidad--;
                }

            }
        }
    }

    public function actionTestRegistrosMasivos()
    {
        $fileHandler=fopen("../web/documents/REGISTROS_ENTRADA.csv","r");
        if($fileHandler){
            $first_time = true;
            $numRow = 0;
            while($line=fgetcsv($fileHandler,1000,';')){
                $numRow++;
                if ($first_time == true) { // primera fila
                    $first_time = false;
                    continue;
                }
                $numId = $line[0];
                //$strFechaHoraEntrada = $line[1];
                $strFechaHoraSalida = util::getDateFormatedSqlToUserLine($line[1]) ;
                $strStatus = empty($strFechaHoraEntrada) ? 'S':'E';
                $strCodeLlave = strtoupper(trim($line[2]));//codigo like  '%035%'
                $strUser = strtoupper(trim($line[9]));
                $strComercial = strtoupper(trim($line[14]));
                $numTipoDoc = 1;
                $strDocumento = null;
                $strNombreResponsable = strtoupper(trim($line[11]));
                $strTelefonoResponsable= strtoupper(trim($line[12]));
                $strObservaciones = strtoupper(trim($line[13]));

                // consultar llave por codigo
                $objLlave = Llave::find()->andFilterWhere(['like', 'codigo', $strCodeLlave])->orderBy('codigo ASC')->one();
                if(empty($objLlave)){
                    echo "FILA $numRow - Llave no encontrada - ".addslashes($strCodeLlave)." \n";
                    continue;
                }
                // buscar usuario
                $objUser = User::find()->andFilterWhere(['like', 'username', $strUser])->one();
                if(empty($objUser)){
                    echo "FILA $numRow - Usuario no encontrado - ".addslashes($strUser)." \n";
                    continue;
                }
                // buscar comercial asignado
                $objComercial = Comerciales::find()->andFilterWhere(['like', 'nombre', $strComercial])->one();
                if(empty($objComercial)){
                    echo "FILA $numRow - Comercial no encontrado - ".addslashes($strComercial)." \n";
                    continue;
                }

                //$connection = Yii::$app->db;
                //$transaction = $connection->beginTransaction();
                // nuevo registr
                $newRegistro = new Registro();
                $newRegistro->id = $numId;
                $newRegistro->id_user = $objUser->id;
                $newRegistro->id_llave = $objLlave->id;
                $newRegistro->salida = $strFechaHoraSalida;
                $newRegistro->observacion = $strObservaciones;
                $newRegistro->id_comercial = $objComercial->id;
                $newRegistro->tipo_documento = $numTipoDoc;
                $newRegistro->nombre_responsable = $strNombreResponsable;
                $newRegistro->telefono = $strTelefonoResponsable;
                if($newRegistro->validate() && $newRegistro->save()){
                    $newStatus = new LlaveStatus();
                    $newStatus->id_llave  = $newRegistro->id_llave ;
                    $newStatus->status = $strStatus;
                    $newStatus->fecha = $newRegistro->getFechaRegistro() ;
                    $newStatus->id_registro = $newRegistro->id;
                    if(!$newStatus->save()){
                        echo "FILA $numRow - Registro-status no creado \n";
                    }
                }else{
                    echo "FILA $numRow - Registro no creado \n";
                }
            }
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function actionReporteEmail():void
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
        $contentBody10 = empty($numRegTotal10) ? "" : "<tr><td align=\"center\" style=\";margin-top:25px;color: #002c59\"><p align=\"center\"> " . Yii::t('app', 'indexBody10a') . " <strong> " . $numRegTotal10 . "</strong> " . Yii::t('app', 'indexBody10b') . '</p>' . $tableHtml10 . "</td></tr>";
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

        $arrEmail = Yii::$app->params['reporteMensual'];
        $strSubject = $arrEmail['subject_'.Yii::$app->language];
        Yii::$app->mail->compose("@app/mail/layouts/html",['content'=>$content])
            ->setFrom($arrEmail['from'])
            ->setTo($arrEmail['to'])
            ->setSubject($strSubject)
            ->send();
    }
}
