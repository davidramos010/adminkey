<?php

namespace app\controllers;

use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveSearch;
use app\models\LlaveStatus;
use app\models\Propietarios;
use app\models\TipoLlave;
use app\models\util;
use kartik\helpers\Html;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\UnsetArrayValue;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LlaveController implements the CRUD actions for Llave model.
 */
class LlaveController extends BaseController
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
        $dataProvider->pagination->pageSize = 50;

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
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

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
            // definicion de la variable nomenclatura
            $model->nomenclatura = $model->getNomenclatura();
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Buscar detalles del registro de la llave
     * @return false|string
     */
    public function actionAjaxFindCode()
    {
        $arrParam = $this->request->post();
        $comunidad_id = $arrParam['comunidad'];
        $propietario_id = $arrParam['propietario'];
        $arrInfo = [];
        if(!empty($comunidad_id)){
            $objComunidad = Comunidad::findOne(['id'=>$comunidad_id]);
            $model = new Llave();
            $model->id_comunidad = (int) $comunidad_id;
            $arrInfo['id'] = (string) $model->getNext();
            $arrInfo['nomenclatura'] = $objComunidad->nomenclatura;
        }

        if(!isset($model) && !empty($propietario_id)){
            $objPropietario = Propietarios::findOne(['id'=> (int) $propietario_id]);
            $model = new Llave();
            $model->id_propietario = (int) $propietario_id;
            $arrInfo['id'] = (string) $model->getNext();
            $arrInfo['nomenclatura'] = 'P'.$objPropietario->id;
        }

        return json_encode( $arrInfo);
    }

    /**
     * buscar movimientos - retorna un arreglo con los registros de los movimientos
     * @return false|string
     */
    public function actionAjaxFindStatus():string
    {
        $arrParam   = $this->request->post();
        $numIdLlave = $arrParam['numIdLlave'];
        $strTableTr = "";
        $arrStatus = LlaveStatus::find()->where(['id_llave'=>$numIdLlave])->orderBy('fecha DESC')->all();
        if(count($arrStatus)){
            foreach ($arrStatus as $modelStatus){
                $modelStatus->status = ($modelStatus->status=='S')?'<span class="float-none badge bg-danger">'.Yii::t('app','Exit').'</span>':'<span class="float-none badge bg-success">'.Yii::t('app','Entry').'</span>';
                $strComercial = !empty($modelStatus->registro->id_comercial) ? $modelStatus->registro->comerciales->nombre : '';
                $strTableTr .= "<tr>";
                $strTableTr .= "<td >".$modelStatus->status."</td>";
                $strTableTr .= "<td style='font-size: 12px; font-weight: bold'>". substr(util::getDateTimeFormatedSqlToUser($modelStatus->fecha),0,10)   ."</td>";
                $strTableTr .= "<td style='font-size: 13px; '>".$strComercial."</td>";
                $strTableTr .= "<td style='font-size: 13px; '>".$modelStatus->registro->nombre_responsable."</td>";
                $strTableTr .= "<td style='font-size: 12px;'>".$modelStatus->registro->observacion."</td></tr>";
            }
        }else{
            $strTableTr = "<tr><td colspan='5' class='text-black-50 text-md-center' >".Yii::t('yii','No results found.')."</td></tr>";
        }

        return $strTableTr;
    }

    /**
     * buscar movimientos - retorna un arreglo con los resgistros de los movimientos
     * @return false|string
     */
    public function actionAjaxFindManual():string
    {
        $arrParam   = $this->request->post();
        $strTableTr = "";
        $searchModel = new LlaveSearch();
        $dataProvider = $searchModel->searchManual($arrParam);

        if(count($dataProvider)){
            foreach ($dataProvider as $modelLlave){
                // ---------------------------
                $strCodigo = $modelLlave->codigo;
                $strOperacion = ($modelLlave->llaveLastStatus=='S')?'S':'E';
                $strOperacionClick = ($strOperacion=='S')?'E':'S';
                $strComunidad = !empty($modelLlave->comunidad) && isset($modelLlave->comunidad->nombre) ? $modelLlave->comunidad->nombre : '';
                $strPropietario = !empty($modelLlave->propietarios) && isset($modelLlave->propietarios->id) ? $modelLlave->propietarios->getNombre() : '';
                $strCliente = $strComunidad;
                $strCliente .= !empty($strPropietario) && !empty($strCliente) ? ' / ':'';
                $strCliente .= !empty($strPropietario) ? $strPropietario:'';
                $strTipo = !empty($modelLlave->tipo) && isset($modelLlave->tipo->descripcion) ? $modelLlave->tipo->descripcion : '';
                $strStatus = ($strOperacion=='S')?'<span class="float-none badge bg-danger">'.Yii::t('app','Prestada').'</span>':'<span class="float-none badge bg-success">'.Yii::t('app','Almacenada').'</span>';
                $strDescripcion = trim($modelLlave->descripcion);
                $strCallFunction = " addKeyForm('$modelLlave->codigo','$strOperacionClick',true) ";
                $strButton = ($strOperacion=='S')?
                    Html::button('<i class="fas fa-arrow-circle-right"></i>', ['id' => 'btn_add', 'title'=>Yii::t('app','Devolución de LLave'), 'class' => 'btn-xs btn-danger', 'onclick' => $strCallFunction]) :
                    Html::button('<i class="fas fa-arrow-circle-left"></i>', ['id' => 'btn_add', 'title'=>Yii::t('app','Entrega/Salida de Llave'), 'class' => 'btn-xs btn-success', 'onclick' => $strCallFunction]);

                $strEmpresa = ($strOperacion=='S')? $modelLlave->comercial : '';
                $strResponsable = ($strOperacion=='S')? $modelLlave->responsable : '';
                // ---------------------------
                $strTableTr .= "<tr id='tr_".$strCodigo."' >";
                $strTableTr .= "    <td style='font-size: 10px; '>" . $strButton . "</td>";
                $strTableTr .= "    <td style='font-size: 10px; font-weight: bold'>" . $strCodigo . "</td>";
                $strTableTr .= "    <td style='font-size: 12px; '>" . trim($strCliente) . "</td>";
                $strTableTr .= "    <td style='font-size: 12px;'>" . $strDescripcion . "</td>";
                $strTableTr .= "    <td style='font-size: 10px; '>" . $strTipo . "</td>";
                $strTableTr .= "    <td style='font-size: 14px;'>" . $strStatus . "</td>";
                $strTableTr .= "    <td style='font-size: 12px;'>" . $strEmpresa . "</td>";
                $strTableTr .= "    <td style='font-size: 12px;'>" . $strResponsable . "</td>";
                $strTableTr .= "</tr>";
            }
        }else{
            $strTableTr = "<tr><td colspan='8' class='text-black-50 text-md-center' >".Yii::t('yii','No results found.')."</td></tr>";
        }

        return $strTableTr;
    }

    /**
     * Funcion que retorna los atributos de una tipo de llave
     * @return false|string
     */
    public function actionAjaxFindAttributes()
    {
        $arrParam = $this->request->post();
        $numTipoLlave = (int) $arrParam['numIdTipoLlave'];
        $objTipoLLave = TipoLlave::findOne(['id'=>$numTipoLlave]);
        return !empty($objTipoLLave)? json_encode( $objTipoLLave->getAttributes() ):'';
    }
}
