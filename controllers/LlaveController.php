<?php

namespace app\controllers;

use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveSearch;
use yii\helpers\UnsetArrayValue;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LlaveController implements the CRUD actions for Llave model.
 */
class LlaveController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Llave models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string
     */
    public function actionReport()
    {
        $searchModel = new LlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Llave model.
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
     * Creates a new Llave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Llave();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->codigo = $strCodBase = $model->nomenclatura.'-'.$model->codigo;
                $model->codigo .= ($model->copia>1)?'.1':'';
                $model->save();

                if((int) $model->copia > 1){
                    $numContador = 1;
                    while ((int) $model->copia>$numContador){
                        $numContador++;
                        $modelClone = new Llave();
                        $modelClone->attributes = $model->attributes;
                        $modelClone->codigo = $strCodBase.'.'.$numContador;
                        $modelClone->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Llave model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $model->codigo = $model->nomenclatura.'-'.$model->codigo;
            $model->codigo .= ($model->copia>1)?'.1':'';
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->nomenclatura = $model->comunidad->nomenclatura;
        $model->codigo = str_replace($model->nomenclatura.'-','',$model->codigo);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Llave model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $objLlave = Llave::findOne($id);
        $objLlave->activa =0;
        $objLlave->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Llave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Llave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Llave::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionAjaxFindCode()
    {
        $arrParam = $this->request->post();
        $comunidad_id = $arrParam['comunidad'];
        $objComunidad = Comunidad::findOne(['id'=>$comunidad_id]);
        $model = new Llave();
        $model->id_comunidad = (int) $comunidad_id;
        $arrInfo['id'] = (string) $model->getNext();
        $arrInfo['nomenclatura'] = $objComunidad->nomenclatura;
        return json_encode( $arrInfo);
    }
}
