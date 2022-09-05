<?php

namespace app\controllers;

use app\models\Codipostal;
use app\modules\contratacion\models\CodigosPostales;
use Yii;
use app\models\Propietarios;
use app\models\PropietariosSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropietariosController implements the CRUD actions for Propietarios model.
 */
class PropietariosController extends Controller
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
     * Lists all Propietarios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropietariosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Propietarios model.
     * @param int $id ID
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
     * Creates a new Propietarios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Propietarios();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->nombre_propietario = strtoupper($model->nombre_propietario);
            $model->nombre_representante = strtoupper($model->nombre_representante);
            $model->observaciones = strtoupper($model->observaciones);
            $model->poblacion = strtoupper($model->poblacion);
            $model->direccion = strtoupper($model->direccion);
            if($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Almacenado Correctamente'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.'));
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Propietarios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->nombre_propietario = strtoupper($model->nombre_propietario);
            $model->nombre_representante = strtoupper($model->nombre_representante);
            $model->nombre_representante = strtoupper($model->nombre_representante);
            $model->observaciones = strtoupper($model->observaciones);
            $model->poblacion = strtoupper($model->poblacion);
            $model->direccion = strtoupper($model->direccion);
            if($model->save()){
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Almacenado Correctamente'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.'));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Propietarios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        Yii::$app->session->setFlash('error', Yii::t('yii', 'Opción no disponible'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Propietarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Propietarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Propietarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Devuelve un listado de poblaciones en funcion del codipostal especificado
     * @param bool $q
     * @return array
     */
    public function actionCodigosPostales($q = false)
    {
        $rows = Codipostal::find()->select('cp,poblacio,provincia')->distinct()
            ->where(['like', 'cp', $q])->orWhere(['like', 'poblacio', $q])->asArray()->all();
        return json_encode($rows);
    }

}
