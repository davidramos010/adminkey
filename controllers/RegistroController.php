<?php

namespace app\controllers;

use app\components\ValidadorCsv;
use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveStatus;
use app\models\Registro;
use app\models\RegistroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;

/**
 * RegistroController implements the CRUD actions for Registro model.
 */
class RegistroController extends Controller
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

                ],
            ]
        );
    }

    /**
     * Lists all Registro models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RegistroSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Registro model.
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
     * Creates a new Registro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Registro();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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
     * Updates an existing Registro model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Registro model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Registro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Registro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Registro::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Displays a single Registro model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReporte($id=1)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Salida: Entrega/Salida de llave
     * Entrada: Devolución de llave
     * @return
     */
    public function actionAjaxAddKey()
    {
        $strCode = (!empty($this->request->get()) && isset($this->request->get()['code']))?(string) trim($this->request->get()['code']):null;
        $strCode = str_replace("'","-",$strCode);
        $arrModelStatus = null;
        $arrComunidadLlave = null;
        $strEstado = null;

        $arrModelLlave = Llave::find()->where(['codigo'=>$strCode])->asArray()->one();
        if(!empty($arrModelLlave)){
            $numId = $arrModelLlave['id'];
            $arrModelStatus = (object) LlaveStatus::find()->where(['id_llave'=>$numId])->orderBy(['id' => SORT_DESC])->asArray()->one();
            $arrComunidadLlave = (!empty($arrModelLlave))?Comunidad::find()->where(['id'=>$arrModelLlave['id_comunidad']])->asArray()->one():null;
            $strEstado = (empty($arrModelStatus) || !isset($arrModelStatus->status))?'E':$arrModelStatus->status;
        }

        return json_encode( ['llave'=>$arrModelLlave,'status'=>$arrModelStatus, 'comunidad'=>$arrComunidadLlave, 'estado'=>$strEstado]);
    }

    /**
     * @return false|string
     */
    public function actionAddFirma()
    {
        $arrParam = $this->request->post();
        $data_uri = $arrParam['signature'];//"data:image/png;base64,iVBORw0K...";
        $encoded_image = explode(",", $data_uri)[1];
        $decoded_image = base64_decode($encoded_image);
        $fileName = date('Ymdhis') . '_firma.jpg';
        $pathToSave = Yii::getAlias('@webroot') . '/firmas/' .$fileName;

        if(file_put_contents($pathToSave, $decoded_image) && Yii::$app->session->has('lastRegistro')){
            $objRegistro = Registro::findOne(Yii::$app->session->get('lastRegistro'));
            $objRegistro->firma_soporte = $fileName;
            $objRegistro->save();
        }
    }

    /**
     * @return false|string
     */
    public function actionAjaxRegMov()
    {
        $arrParam = $this->request->post();
        $strObservacion = $arrParam['observacion'];
        $idComercial = $arrParam['comercial'];
        $arrKeysEntrada = (empty($arrParam['listKeyEntrada']) || !isset($arrParam['listKeyEntrada']))?null:$arrParam['listKeyEntrada'];
        $arrKeysSalida = (empty($arrParam['listKeySalida']) || !isset($arrParam['listKeySalida']))?null:$arrParam['listKeySalida'];

        if(!empty($arrKeysEntrada) || !empty($arrKeysSalida)){
            $newRegistro = new Registro();
            $newRegistro->id_user = (int) Yii::$app->user->id;
            $newRegistro->observacion = $strObservacion;
            $newRegistro->id_comercial = (int) $idComercial;
            $newRegistro->entrada = (!empty($arrKeysEntrada))?date('Y-m-d H:i:s'):NULL;
            $newRegistro->salida = (!empty($arrKeysSalida))?date('Y-m-d H:i:s'):NULL;
            if($newRegistro->save()){
                Yii::$app->session->set('lastRegistro', $newRegistro->id);
            }
        }

        //Entrada: Devolución de llave
        if(isset($newRegistro->id) && !empty($arrKeysEntrada)){
            foreach ($arrKeysEntrada as $value){
                $newRegistroStatus = new LlaveStatus();
                $strEstado = 'Entrada';
                $newRegistroStatus->id_llave = $value;
                $newRegistroStatus->status = ($strEstado=='Entrada')?'E':'S';
                $newRegistroStatus->id_registro = $newRegistro->id;
                $newRegistroStatus->save();
            }
        }

        //Salida: Entrega de llave
        if(isset($newRegistro->id) && !empty($arrKeysSalida)){
            foreach ($arrKeysSalida as $value){
                $newRegistroStatus = new LlaveStatus();
                $strEstado = 'Salida';
                $newRegistroStatus->id_llave = $value;
                $newRegistroStatus->status = ($strEstado=='Entrada')?'E':'S';
                $newRegistroStatus->id_registro = $newRegistro->id;
                $newRegistroStatus->save();
            }
        }

        return json_encode( ['result'=>'OK']);
    }
}
