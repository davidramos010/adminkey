<?php

namespace app\controllers;

use app\models\Codipostal;
use app\models\ContratosLog;
use app\models\Llave;
use app\models\LlaveSearch;
use Yii;
use app\models\Contratos;
use app\models\ContratosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ContratosController implements the CRUD actions for Contratos model.
 */
class ContratosController extends Controller
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
     * Lists all Contratos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContratosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contratos models.
     * @return mixed
     */
    public function actionGenerarList()
    {
        $searchModel = new ContratosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('generar_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contratos models.
     * @return mixed
     */
    public function actionCreateContrato()
    {
        $model = new Contratos();
        $modelLog = new ContratosLog();

        $searchModel = new LlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('generar', [
            'model' => $model,
            'model_log' => $modelLog,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contratos model.
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
     * Creates a new Contratos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contratos();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->nombre = strtoupper($model->nombre);
            $model->id_user = Yii::$app->user->identity->id;
            $model->fecha_ini = (!empty($model->fecha_ini))? date("Y-m-d", strtotime($model->fecha_ini)) :null;
            $model->fecha_fin = (!empty($model->fecha_fin))? date("Y-m-d", strtotime($model->fecha_fin)) :null;
            $model->save();
            //Validar documento
            try {
                $documentoContrato = UploadedFile::getInstance($model, 'documento');
                $model->documento = $model->nombre.'.'. $documentoContrato->getExtension();
                $documentoContrato->saveAs(Yii::getAlias('@webroot') . '/plantillas/' . $model->documento );
                $model->save();
                Yii::$app->session->setFlash('success', Yii::t('yii', 'Registrado Correctamente'));
                return $this->redirect(['index']);
            }catch (ErrorException $e){
                Yii::$app->session->setFlash('error', Yii::t('yii', 'El proceso no finaliza correctamente, por favor revise los datos.'));
            }
        }

        $model->estado=1;//default
        $model->fecha_ini = (empty($model->fecha_ini))? date("d-m-Y") :null;

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    /**
     * Updates an existing Contratos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->nombre = strtoupper($model->nombre);
            $model->id_user = Yii::$app->user->identity->id;
            $model->fecha_ini = (!empty($model->fecha_ini))? date("Y-m-d", strtotime($model->fecha_ini)) :null;
            $model->fecha_fin = (!empty($model->fecha_fin))? date("Y-m-d", strtotime($model->fecha_fin)) :null;
            $model->save();
            //Validar documento
            try {
                $documentoContrato = UploadedFile::getInstance($model, 'documento');
                if(!empty($documentoContrato)){
                    $model->documento = $model->nombre.'.'. $documentoContrato->getExtension();
                    $documentoContrato->saveAs(Yii::getAlias('@webroot') . '/plantillas/' . $model->documento );
                    $model->save();
                }

                Yii::$app->session->setFlash('success', Yii::t('yii', 'Registrado Correctamente'));
                return $this->redirect(['index']);
            }catch (ErrorException $e){
                Yii::$app->session->setFlash('error', Yii::t('yii', 'El proceso no finaliza correctamente, por favor revise los datos.'));
            }
        }

        $model->fecha_ini = (!empty($model->fecha_ini))? date("d-m-Y", strtotime($model->fecha_ini)) :null;
        $model->fecha_fin = (!empty($model->fecha_fin))? date("d-m-Y", strtotime($model->fecha_fin)) :null;

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Contratos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->estado=0;
        if($model->save()){
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Se ha Inactivado correctamente'));
        }else{
            Yii::$app->session->setFlash('error', Yii::t('yii', 'El proceso no finaliza correctamente, vuelva a intentar.'));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Contratos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Contratos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contratos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }


    /**
     * @return void
     */
    public function actionAjaxConsultarLlaves($q = false)
    {
        $rows = Llave::find()->select('id,codigo')->distinct()
            ->where(['like', 'codigo', $q])->asArray()->all();
        return json_encode($rows);
    }

    /**
     * buscar informacion general de una llave por id
     * @return void
     */
    public function actionAjaxFindLlave()
    {
        $arrParam = $this->request->post();
        $numId = $arrParam['id'];

        $query = Llave::find()->alias('ll');
        // add conditions that should always apply here
        $query->select([
            "ll.*",
            "ls.status as llaveLastStatus",
            "lu.descripcion_almacen as ubicacion",
            "c.nombre as comunidad",
            "(CASE
                WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                ELSE NULL
            END) as nombre_propietario",
            "tl.descripcion as tipo"
        ]);
        $query->leftJoin('llave_status ls','ls.id_llave = ll.id and ls.id = ( SELECT MAX(id) FROM llave_status cm WHERE ls.id_llave = ll.id )');
        $query->leftJoin('propietarios pp','ll.id_propietario = pp.id');
        $query->leftJoin('tipo_llave tl','ll.id_tipo = tl.id');
        $query->leftJoin('llave_ubicaciones lu','ll.id_llave_ubicacion = lu.id');
        $query->leftJoin('comunidad c','ll.id_comunidad = c.id');
        $query->where(['ll.id'=>$numId]);
        $arrLlave = $query->asArray()->all();
        return json_encode($arrLlave[0]);

    }


}
