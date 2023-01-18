<?php

namespace app\controllers;

use app\models\TipoLlave;
use app\models\TipoLlaveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OperacionesController implements the CRUD actions for Llave,Registros model.
 */
class OperacionesController extends Controller
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
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     *
     *
     * @return string
     */
    public function actionOperaciones()
    {
        $searchModel = new TipoLlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('operaciones', [ ]);
    }

    /**
     *
     *
     * @return string
     */
    public function actionRegistros()
    {
        $searchModel = new TipoLlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
