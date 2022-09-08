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
                    /*'delete' => ['POST'],*/
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
            'model_info' => UserInfo::find()->where(['id_user'=>$id])->one(),
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
        $strErrores = null;
        if(Yii::$app->request->post()){
            $arrParam = Yii::$app->request->post();
            //Mayus
            $arrParam['UserInfo']['nombres'] = trim(strtoupper($arrParam['UserInfo']['nombres']));
            $arrParam['UserInfo']['apellidos'] = trim(strtoupper($arrParam['UserInfo']['apellidos']));
            $arrParam['UserInfo']['direccion'] = trim(strtoupper($arrParam['UserInfo']['direccion']));
            $arrParam['UserInfo']['codigo'] = trim(strtoupper($arrParam['UserInfo']['codigo']));
            $arrUser['User'] = $arrParam['User'];
            $arrUser['User']['name'] = trim($arrParam['UserInfo']['nombres']. ' ' .$arrParam['UserInfo']['apellidos']);
            $arrUser['User']['password'] = $arrUser['User']['password_new'];
            $arrUser['User']['authKey'] = $arrUser['User']['authKey_new'];
            $transaction = Yii::$app->db->beginTransaction();
            $bolInserUser = ($model->load($arrUser) && $model->save());
            $bolInserPerfil = false;
            // pintar errores
            if(!$bolInserUser){
                $arrError = $model->getErrors();
                foreach ($arrError as $item){
                    if(isset($item[0])){
                        $strErrores .= '<br>-'.trim($item[0]);
                    }
                }
            }

            $arrUserInfo['UserInfo'] = $arrParam['UserInfo'];
            $arrUserInfo['UserInfo']['id_user'] = $model->id;
            $bolInserUserInfo = ($bolInserUser && $modelInfo->load($arrUserInfo) && $modelInfo->save());

            // pintar errores
            if($bolInserUser && !$bolInserUserInfo){
                $arrError = $modelInfo->getErrors();
                foreach ($arrError as $item){
                    if(isset($item[0])){
                        $strErrores .= '<br>-'.trim($item[0]);
                    }
                }
            }

            if($bolInserUser && $bolInserUserInfo){
                $newPerfilUser = new PerfilesUsuario();
                $newPerfilUser->id_user = (int) $model->id;
                $newPerfilUser->id_perfil = (int) $model->idPerfil;
                $bolInserPerfil = $newPerfilUser->save();
                if(!$bolInserPerfil){
                    $strErrores .= '<br>-El perfil no se asigna correctamente';
                }
            }

            if($bolInserPerfil && $bolInserUser && $bolInserUserInfo){
                if(empty($transaction->commit())){
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Registrado Correctamente'));
                    return $this->redirect(['index']);
                }else{
                    $strErrores .= '<br>-No se puede registrar. Valide los datos he intente nuevamente.';
                }
            }else{
                $transaction->rollBack();
                $strErrores .= '<br>-Error al guardar. Valide los datos y vuelva a intentar.';
            }
        }

        if(!empty($strErrores)){
            Yii::$app->session->setFlash('error', $strErrores );
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
        $modelInfo = UserInfo::find()->where(['id_user'=>$id])->one();

        if(Yii::$app->request->post()){
            $arrParam = Yii::$app->request->post();
            $arrParam['UserInfo']['nombres'] = trim(strtoupper($arrParam['UserInfo']['nombres']));
            $arrParam['UserInfo']['apellidos'] = trim(strtoupper($arrParam['UserInfo']['apellidos']));
            $arrUser['User'] = $arrParam['User'];
            $arrUser['User']['name'] = trim($arrParam['UserInfo']['nombres']. ' ' .$arrParam['UserInfo']['apellidos']);
            //$arrUser['User']['password'] = $arrUser['User']['password_new'];
            //$arrUser['User']['authKey'] = $arrUser['User']['authKey_new'];
            $transaction = Yii::$app->db->beginTransaction();
            $bolInserUser = ($model->load($arrUser) && $model->validate() && $model->save());
            $bolInserPerfil = false;
            // pintar errores
            if(!$bolInserUser){
                $arrError = $model->getErrors();
                foreach ($arrError as $item){
                    if(isset($item[0])){
                        Yii::$app->session->setFlash('warning', $item[0]);
                    }
                }
            }

            $bolInserUserInfo = ($bolInserUser && $modelInfo->load( $arrParam )&& $model->validate() && $modelInfo->save());
            // pintar errores
            if($bolInserUser && !$bolInserUserInfo){
                $arrError = $modelInfo->getErrors();
                foreach ($arrError as $item){
                    if(isset($item[0])){
                        Yii::$app->session->setFlash('warning', $item[0]);
                    }
                }
            }

            if($bolInserUser && $bolInserUserInfo){
                $newPerfilUser =  PerfilesUsuario::find()->where(['id_user'=>$id])->one();
                $newPerfilUser = (empty($newPerfilUser))?new PerfilesUsuario():$newPerfilUser;
                $newPerfilUser->id_user = (int) $model->id;
                $newPerfilUser->id_perfil = (int) $model->idPerfil;
                $bolInserPerfil = $newPerfilUser->save();
                if(!$bolInserPerfil){
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'El perfil no se asigna correctamente'));
                }
            }

            if($bolInserPerfil && $bolInserUser && $bolInserUserInfo){
                $transaction->commit();
                return $this->redirect(['update', 'id' => $model->id]);
            }else{
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', Yii::t('yii', 'Error al guardar. Valide los datos y vuelva a intentar.'));
            }
        }


        return $this->render('update', [
            'model' => $model,
            'model_info' => $modelInfo
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
        $objUserInfo = UserInfo::find()->where(['id_user'=>$id])->one();
        $objUserInfo->estado=0;//Inactivo
        $objUserInfo->save();
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
