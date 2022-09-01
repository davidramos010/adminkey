<?php

namespace app\controllers;

use app\models\PerfilesUsuario;
use app\models\UserInfo;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hail812\adminlte\widgets\Menu;
use hail812\adminlte\widgets\FlashAlert;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$n = (Yii::$app->user->identity->accessToken=='1234');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id Id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $modelInfo = new UserInfo();
        if(Yii::$app->request->post()){
            $arrParam = Yii::$app->request->post();
            $arrUser['User'] = $arrParam['User'];
            $arrUser['User']['name'] = trim($arrParam['UserInfo']['nombres']. ' ' .$arrParam['UserInfo']['apellidos']);
            $arrUser['User']['password'] = $arrUser['User']['password_new'];
            $arrUser['User']['authKey'] = $arrUser['User']['authKey_new'];
            $bolInserUser = ($model->load($arrUser) && $model->save());

            $arrUserInfo['UserInfo'] = $arrParam['UserInfo'];
            $arrUserInfo['UserInfo']['id_user'] = $model->id;
            $bolInserUserInfo = ($bolInserUser && $modelInfo->load($arrUserInfo) && $modelInfo->save());
            $bolInserPerfil = false;
            if($bolInserUser && $bolInserUserInfo){
                $newPerfilUser = new PerfilesUsuario();
                $newPerfilUser->id_user = (int) $model->id;
                $newPerfilUser->id_perfil = (int) $model->idPerfil;
                $bolInserPerfil = $newPerfilUser->save();
            }

            if($bolInserPerfil && $bolInserUser && $bolInserUserInfo){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $modelInfo->estado=1; // Activo por defecto

        return $this->render('create', [
            'model' => $model,
            'model_info' => $modelInfo,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id Id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
