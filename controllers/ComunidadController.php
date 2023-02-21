<?php

namespace app\controllers;

use app\models\Comunidad;
use app\models\ComunidadSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ComunidadController implements the CRUD actions for comunidad model.
 */
class ComunidadController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        //'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all comunidad models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ComunidadSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single comunidad model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new comunidad model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Comunidad();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                if($model->save()){
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Almacenado Correctamente'));
                }else{
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.'));
                }

                return $this->redirect(['update', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
            $model->nomenclatura = 'C'.$model->getNext();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreateModal()
    {
        $model = new Comunidad();
        $model->loadDefaultValues();
        $model->nomenclatura = 'C'.$model->getNext();
        $this->module->layout = 'content-simple';
        return $this->render('create-modal', ['model' => $model, 'modal'=>true]);
    }

    /**
     * Crear un registros de Comunidad desde la llamada de un formulario con ajax
     * Para esta caso se ejecuta desde el formulario de llaves
     * @return void
     */
    public function actionAjaxCreate()
    {
        $response['ok_sms'] = Yii::t('yii', 'Almacenado Correctamente');
        $response['ok'] = null;
        $response['name'] = null;
        $response['error'] = null;
        $response['nomenclatura'] = null;

        if ($this->request->isPost) {
            $model = new Comunidad();
            if ($model->load($this->request->post()) && $model->validate()) {
                if($model->save()){
                    $response['ok'] = $model->id;
                    $response['name'] = $model->nombre;
                    $response['nomenclatura'] = $model->nomenclatura;
                }else{
                    $response['error'] = Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.').'<br />';
                }
            }
        }

        // -------------------------------
        // Gestión de errores
        if($model->getErrors()){
            foreach ($model->getErrors() as $key => $campo){
                $response['error'] .= ucfirst($key).'<br />';
                foreach($campo as $error_detalle){
                    $response['error'] .= '<span class="small">'.$error_detalle.'</span><br />';
                }
            }
        }

        echo json_encode($response);
    }

    /**
     * Updates an existing comunidad model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            if($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Actualizado Correctamente'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede actualizar. Valide los datos he intente nuevamente.'));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing comunidad model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->estado=0;
        if($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Deshabilitado Correctamente'));
        }else{
            Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede actualizar el estado. Valide el formulario he intente nuevamente.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the comunidad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Comunidad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comunidad::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
