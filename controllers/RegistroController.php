<?php

namespace app\controllers;

use app\components\ValidadorCsv;
use app\models\Codipostal;
use app\models\Comerciales;
use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveStatus;
use app\models\Registro;
use app\models\RegistroSearch;
use app\models\User;
use kartik\mpdf\Pdf;
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
        $searchModel = new RegistroSearch();
        $arrInfoStatusE = $searchModel->search_status($id, 'E');
        $arrInfoStatusS = $searchModel->search_status($id, 'S');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'arrInfoStatusE' => $arrInfoStatusE,
            'arrInfoStatusS' => $arrInfoStatusS
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

        $objUser = User::findOne(Yii::$app->user->id);
        $model->nombre_responsable = trim($objUser->userInfo->nombres . ' ' . $objUser->userInfo->apellidos);
        $model->telefono = $objUser->userInfo->telefono;
        $model->tipo_documento = $objUser->userInfo->tipo_documento;
        $model->documento = $objUser->userInfo->documento;
        $model->id_comercial = $objUser->userInfo->id_comercial;

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
    public function actionReporte($id = 1)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Salida: Entrega/Salida de llave
     * Entrada: Devoluci??n de llave
     * @return
     */
    public function actionAjaxAddKey()
    {
        $strCode = (!empty($this->request->get()) && isset($this->request->get()['code'])) ? (string)trim($this->request->get()['code']) : null;
        $strCode = str_replace("'", "-", $strCode);
        $arrModelStatus = null;
        $arrComunidadLlave = null;
        $strEstado = null;
        $strError = null;

        $arrModelLlave = Llave::find()->where(['codigo' => $strCode])->asArray()->one();
        if (!empty($arrModelLlave)) {
            // solo el administrador puede ingresar llaves de otros usuario
            $userSession = Yii::$app->user->id;
            $userPerfil = Yii::$app->user->identity->perfiluser->id_perfil;

            $numId = $arrModelLlave['id'];
            $arrModelStatus = (object) LlaveStatus::find()->where(['id_llave' => $numId])->orderBy(['id' => SORT_DESC])->asArray()->one();
            $arrComunidadLlave = (!empty($arrModelLlave)) ? Comunidad::find()->where(['id' => $arrModelLlave['id_comunidad']])->asArray()->one() : null;
            $strEstado = (empty($arrModelStatus) || !isset($arrModelStatus->status)) ? 'E' : $arrModelStatus->status;

            if($userPerfil!=1 && $strEstado=='S'){ // si no es admin, evalua quien creo el registro
                $objRegistro = Registro::findOne(['id'=>$arrModelStatus->id_registro]);
                if(!empty($objRegistro) && (int) $objRegistro->id_user != (int) $userSession ){
                    $strError = Yii::t('app', 'Restriccion devolucion llave');
                }
            }
        }

        return json_encode(['llave' => $arrModelLlave, 'status' => $arrModelStatus, 'comunidad' => $arrComunidadLlave, 'estado' => $strEstado, 'error' => $strError]);
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
        $pathToSave = Yii::getAlias('@webroot') . '/firmas/' . $fileName;

        if (file_put_contents($pathToSave, $decoded_image) && Yii::$app->session->has('lastRegistro')) {
            $objRegistro = Registro::findOne(Yii::$app->session->get('lastRegistro'));
            $objRegistro->firma_soporte = $fileName;
            $objRegistro->save();
        }
    }

    /**
     * @return false|string
     */
    public function actionAjaxRegisterMotion()
    {
        $arrParam = $this->request->post();
        $arrKeysEntrada = (empty($arrParam['listKeyEntrada']) || !isset($arrParam['listKeyEntrada'])) ? null : explode(',', $arrParam['listKeyEntrada']);
        $arrKeysSalida = (empty($arrParam['listKeySalida']) || !isset($arrParam['listKeySalida'])) ? null : explode(',', $arrParam['listKeySalida']);

        if (!empty($arrKeysEntrada) || !empty($arrKeysSalida)) {
            $newRegistro = new Registro();
            $newRegistro->load($arrParam);
            $newRegistro->id_user = (int)Yii::$app->user->id;
            $newRegistro->entrada = (!empty($arrKeysEntrada)) ? date('Y-m-d H:i:s') : NULL;
            $newRegistro->salida = (!empty($arrKeysSalida)) ? date('Y-m-d H:i:s') : NULL;
            if ($newRegistro->save()) {
                Yii::$app->session->set('lastRegistro', $newRegistro->id);
            }
        }

        //Entrada: Devoluci??n de llave
        if (isset($newRegistro->id) && !empty($arrKeysEntrada)) {
            foreach ($arrKeysEntrada as $value) {
                $newRegistroStatus = new LlaveStatus();
                $strEstado = 'Entrada';
                $newRegistroStatus->id_llave = (int)$value;
                $newRegistroStatus->status = ($strEstado == 'Entrada') ? 'E' : 'S';
                $newRegistroStatus->id_registro = $newRegistro->id;
                $newRegistroStatus->save();
            }
        }

        //Salida: Entrega de llave
        if (isset($newRegistro->id) && !empty($arrKeysSalida)) {
            foreach ($arrKeysSalida as $value) {
                $newRegistroStatus = new LlaveStatus();
                $strEstado = 'Salida';
                $newRegistroStatus->id_llave = (int)$value;
                $newRegistroStatus->status = ($strEstado == 'Entrada') ? 'E' : 'S';
                $newRegistroStatus->id_registro = $newRegistro->id;
                $newRegistroStatus->save();
            }
        }

        return json_encode(['result' => 'OK']);
    }

    /**
     * Generacion de reoprte pdf
     * @return mixed
     */
    public function actionPrintRegister($id = null)
    {
        if (empty($id)) {
            return false;
        }
        // get Data
        $newObjRegistro = new Registro();
        $newObjRegistro->id = $id;
        $arrParams = $newObjRegistro->getInfoRegistro($id);
        // get your HTML raw content without any layouts or scripts
        $content = $newObjRegistro->getHtmlAceptacion($arrParams);
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => [Yii::$app->params['contacto']],
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Devuelve un listado de poblaciones en funcion del codipostal especificado
     * @param bool $q
     * @return array
     */
    public function actionFindComercial($q = false)
    {
        $rows = Comerciales::find()->select('id,nombre')->distinct()
            ->where(['like', 'nombre', $q])->andWhere(['estado' => 1])->asArray()->all();
        return json_encode($rows);
    }

    /**
     * Buscar info de un comercial por un metodo ajax
     * @return false|string
     */
    public function actionAjaxFindComercial()
    {
        $arrParam = $this->request->post();
        $rows = Comerciales::find()->where(['id'=>$arrParam['numIdResponsable']])->asArray()->all();
        return json_encode($rows);
    }
}