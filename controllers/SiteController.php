<?php
namespace app\controllers;

use app\models\Perfiles;
use app\models\PerfilesUsuario;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\widgets\Alert;


class SiteController extends Controller
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
        if (!Yii::$app->user->identity) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post())) {
                if($model->login()){
                    $objPerfil = PerfilesUsuario::find()->where(['id_user'=>Yii::$app->user->identity->id ])->one();
                    if(!empty($objPerfil)){
                        if($objPerfil->id_perfil==1 && (int) $model->perfil==1 ){
                            return $this->render('index');
                        }
                        if($objPerfil->id_perfil==2 && (int) $model->perfil==2){
                            return $this->redirect('index.php?r=registro/create');
                        }
                    }
                }
                $srtNotificacion = "Validar los parámetros ingresados";
                Yii::$app->user->logout();
                Yii::$app->user->logout(true);
            }

            return $this->render('login', [
                'model' => $model,
                'notificacion' => $srtNotificacion
            ]);
        }

        return $this->render('index');
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
                    return $this->render('index');
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
}
