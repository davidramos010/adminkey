<?php
namespace app\controllers;

use app\commands\LoadController;
use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveStatusSearch;
use app\models\LlaveUbicaciones;
use app\models\Perfiles;
use app\models\PerfilesUsuario;
use app\models\Propietarios;
use app\models\TipoLlave;
use Yii;
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
        $srtNotificacion = NULL;
        $arrParam = [];
        if (!Yii::$app->user->identity) {
            $model = new LoginForm();
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

            return $this->render('login', [
                'model' => $model,
                'notificacion' => $srtNotificacion
            ]);
        }

        // --------------------------------
        // Parametros para usuarios que ya iniciaron sesion
        if (Yii::$app->user->identity) {
            $searchModelStatus = new LlaveStatusSearch();
            // Cantidad de llave y llaves prestadas
            $arrParam['llaves'] = Llave::getInfoDashboard();
            // --------------------------------------------
            // Lista de llaves prestadas
            $searchModelStatus->status = 'S';
            $arrParam['llavesDataProvider'][5] = (count($arrParam['llaves']['arrLlavesFecha'][5]))?$searchModelStatus->searchBetween([],5):null;
            $arrParam['llavesDataProvider'][10] = (count($arrParam['llaves']['arrLlavesFecha'][10]))?$searchModelStatus->searchBetween([],10):null;
            $arrParam['llavesDataProvider'][15] = (count($arrParam['llaves']['arrLlavesFecha'][15]))?$searchModelStatus->searchBetween([],15):null;
            // --------------------------------------------
            // Contador de llaves
            $arrParam['llavesDataProvider']['cliente'] = $searchModelStatus->searchDataByCliente();
            $arrParam['llavesDataProvider']['propietario'] = $searchModelStatus->searchDataByPropietario();

        }

        return $this->render('index',['params'=>$arrParam]);
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
            $objPerfil = PerfilesUsuario::find()->where(['id_user'=>Yii::$app->user->identity->id ])->one();
            if(!empty($objPerfil)){
                if($objPerfil->id_perfil==1 && (int) $model->perfil==1 ){
                    return $this->redirect('index.php');
                }
                if($objPerfil->id_perfil==2 && (int) $model->perfil==2){
                    return $this->redirect('index.php?r=registro/create');
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

    /**
     * @param $local
     * @return Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionChangeLang($local)
    {
        $available_locales = ['es', 'ca', 'en' ];
        if (!in_array($local, $available_locales)) {
            throw new \yii\web\BadRequestHttpException();
        }

        $session = Yii::$app->session;
        !$session->isActive ? $session->open() : $session->close();
        $session->set('language', $local);
        $session->close();

        return isset($_SERVER['HTTP_REFERER']) ? $this->redirect($_SERVER['HTTP_REFERER']) : $this->redirect(Yii::$app->homeUrl);
    }

    public function actionTest()
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
                    if(isset($objParticular) && empty($objParticular)){
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
}
