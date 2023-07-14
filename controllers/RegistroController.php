<?php

namespace app\controllers;

use app\components\Tools;
use app\models\Comerciales;
use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveStatus;
use app\models\LlaveStatusLog;
use app\models\Propietarios;
use app\models\Registro;
use app\models\RegistroLog;
use app\models\RegistroSearch;
use kartik\mpdf\Pdf;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * RegistroController implements the CRUD actions for Registro model.
 */
class RegistroController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
     * $bolActiveBotonUpdate : Solo puede editar sus propios registros
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        try {
            $modelRegistro = Registro::findOne(['id'=>$id]);
            $searchModel = new RegistroSearch();
            $arrInfoStatusE = $searchModel->search_status($id, 'E');
            $arrInfoStatusS = $searchModel->search_status($id, 'S');
            $bolActiveBotonProcess = $arrInfoStatusS->getTotalCount();
            $bolActiveBotonUpdate = isset(Yii::$app->user) && !empty(Yii::$app->user->getIdentity()) && strtoupper(Yii::$app->user->identity->username) == strtoupper($modelRegistro->user->username);

            return $this->render('view', [
                'model' => $modelRegistro,
                'arrInfoStatusE' => $arrInfoStatusE,
                'arrInfoStatusS' => $arrInfoStatusS,
                'bolActiveBotonProcess' => $bolActiveBotonProcess,
                'bolActiveBotonUpdate' => $bolActiveBotonUpdate,
            ]);
        }catch (\Exception $exception){
            $this->redirect(['index']);
        }
    }

    /**
     * Creates a new Registro model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Registro();
        $strSalida = '';
        $strEntrada = '';
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        //Cargar datos de un albaran existente
        if(!empty($this->request->get()) && isset($this->request->get()['id'])){
            $nunIdMov = !empty($this->request->get()['id'])? (int) $this->request->get()['id'] : null;
            $model = $nunIdMov ? $this->findModel($nunIdMov) : $model;
        }

        return $this->render('create', [
            'model' => $model,
            'tabla_salida'=> $strSalida,
            'tabla_entrada'=> $strEntrada,
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
        $model->fecha_registro = empty($model->entrada)?$model->salida:$model->entrada;
        $model->fecha_registro = !empty($model->fecha_registro)?Tools::getDateTimeShortFormatedSqlToUser( $model->fecha_registro ,'-'):'';
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
     * ajax-find-keys-register
     * @return void
     */
    public function actionAjaxFindKeysRegister(){
        $numIdRegistro = (!empty($this->request->get()) && isset($this->request->get()['numIdRegistro'])) ? (int) trim($this->request->get()['numIdRegistro']) : null;
        $model = $numIdRegistro ? $this->findModel($numIdRegistro) : null;
        $arrParamsAll = $model->getInfoByParamsStatus();
        return json_encode(['all' => $arrParamsAll]);
    }

    /**
     * Salida: Entrega/Salida de llave
     * Entrada: Devolución de llave
     * @return
     */
    public function actionAjaxAddKey()
    {
        $strCode = (!empty($this->request->get()) && isset($this->request->get()['code'])) ? (string)trim($this->request->get()['code']) : null;
        $strCode = str_replace("'", "-", $strCode);
        $arrModelStatus = null;
        $strEstado = null;
        $strError = null;

        $arrModelLlave = Llave::find()->where(['codigo' => $strCode])->asArray()->one();
        if (!empty($arrModelLlave)) {
            // solo el administrador puede ingresar llaves de otros usuario
            $userSession = Yii::$app->user->id;
            $userPerfil = Yii::$app->user->identity->perfiluser->id_perfil;

            $numId = $arrModelLlave['id'];
            $arrModelStatus = (object)LlaveStatus::find()->where(['id_llave' => $numId])->orderBy(['id' => SORT_DESC])->asArray()->one();
            $arrComunidadLlave = (!empty($arrModelLlave)) ? Comunidad::find()->where(['id' => $arrModelLlave['id_comunidad']])->asArray()->one() : null;
            $arrPropietario = (!empty($arrModelLlave['id_propietario'])) ? Propietarios::find()->where(['id' => $arrModelLlave['id_propietario']])->one() : null;
            $strEstado = (empty($arrModelStatus) || !isset($arrModelStatus->status)) ? 'E' : $arrModelStatus->status;
            $strCliente = (!empty($arrComunidadLlave)) ? $arrComunidadLlave['nombre'] : $arrPropietario->nombre;

            if ($userPerfil != 1 && $strEstado == 'S') { // si no es admin, evalua quien creo el registro
                $objRegistro = Registro::findOne(['id' => $arrModelStatus->id_registro]);
                if (!empty($objRegistro) && (int)$objRegistro->id_user != (int)$userSession) {
                    $strError = Yii::t('app', 'Restriccion devolucion llave');
                }
            }
        }

        return json_encode(['llave' => $arrModelLlave, 'status' => $arrModelStatus, 'cliente' => $strCliente, 'estado' => $strEstado, 'error' => $strError]);
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
     * Nuevo registro
     * @return false|string
     */
    public function actionAjaxRegisterMotion()
    {
        $arrParam = $this->request->post();
        $arrKeysEntrada = (empty($arrParam['listKeyEntrada']) || !isset($arrParam['listKeyEntrada'])) ?
            null : explode(',', $arrParam['listKeyEntrada']);
        $arrKeysSalida = (empty($arrParam['listKeySalida']) || !isset($arrParam['listKeySalida'])) ?
            null : explode(',', $arrParam['listKeySalida']);
        $strFechaOperacion = isset($arrParam['Registro']['entrada']) && !empty($arrParam['Registro']['entrada']) ? Tools::getDateTimeFormatedUserToSql($arrParam['Registro']['entrada']) : date('Y-m-d H:i:s');


        if (!empty($arrKeysEntrada) || !empty($arrKeysSalida)) {
            $newRegistro = new Registro();
            $newRegistro->load($arrParam);
            $newRegistro->id_user = (int)Yii::$app->user->id;
            $newRegistro->entrada = (!empty($arrKeysEntrada)) ? $strFechaOperacion : NULL;
            $newRegistro->salida = (!empty($arrKeysSalida)) ? $strFechaOperacion : NULL;
            if ($newRegistro->save()) {
                Yii::$app->session->set('lastRegistro', $newRegistro->id);
            }
        }

        //Entrada: Devolución de llave
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
     * Eliminar registro y crear log
     * @return false|string
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionAjaxDeleteMotion()
    {
        $bolError = false;
        $strMensaje = '';
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $numIdRegistro = (!empty($this->request->post()) && isset($this->request->post()['numIdRegistro'])) ? (int) trim($this->request->post()['numIdRegistro']) : null;
            // validar si por lo menos una de las llaves tiene movimientos
            $bolLlavesConMovimiento = Registro::getLlavesConMovimiento($numIdRegistro);
            if(!$bolLlavesConMovimiento){
                $objOldRegistro = $this->findModel($numIdRegistro);
                $objOldLlavesStatus = LlaveStatus::find()->where(['id_registro'=>$numIdRegistro])->all();
                $newRegistroLog = new RegistroLog();
                $newRegistroLog->id_registro = $numIdRegistro;
                $newRegistroLog->id_user = $objOldRegistro->id_user;
                $newRegistroLog->id_llave = $objOldRegistro->id_llave;
                $newRegistroLog->entrada = $objOldRegistro->entrada;
                $newRegistroLog->salida = $objOldRegistro->salida;
                $newRegistroLog->observacion = $objOldRegistro->observacion;
                $newRegistroLog->id_comercial = $objOldRegistro->id_comercial;
                $newRegistroLog->firma_soporte = $objOldRegistro->firma_soporte;
                $newRegistroLog->tipo_documento = $objOldRegistro->tipo_documento;
                $newRegistroLog->documento = $objOldRegistro->documento;
                $newRegistroLog->nombre_responsable = $objOldRegistro->nombre_responsable;
                $newRegistroLog->telefono = $objOldRegistro->telefono;
                $newRegistroLog->action = 'deleted';
                if($newRegistroLog->validate() && $newRegistroLog->save()){
                    foreach ($objOldLlavesStatus as $value) {
                        $newRegistroStatus = new LlaveStatusLog();
                        $newRegistroStatus->load(['LlaveStatusLog'=>$value->getAttributes()]);
                        $newRegistroStatus->id_registro_log = $newRegistroLog->id;
                        if(!$newRegistroStatus->save()){
                            $bolError = true;
                            continue;
                        }
                    }
                }else{
                    $bolError=true;
                }
                //Eliminar registros
                if(!$bolError){
                    LlaveStatus::deleteAll('id_registro = :id_registro', array(':id_registro' => $numIdRegistro));
                    $this->findModel($numIdRegistro)->delete();
                }
            }else{
                $strMensaje = 'Por lo menos una de la llaves tiene más movimientos posteriores a este registro.';
                $bolError = true;
            }
        }catch (\Exception $e){
            $bolError=true;
        }

        if(!$bolError){
            $transaction->commit();
        }else{
            $transaction->rollBack();
        }

        return json_encode(['result' => $bolError ? 'KO':'OK','mensaje'=>$strMensaje]);
    }

    /**
     * @return false|string
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionAjaxUpdateMotion()
    {
        $bolError = false;
        try {
            $register = $this->actionAjaxRegisterMotion();
            $registerLog = $this->actionAjaxDeleteMotion();
        }catch (\Exception $e){
            $bolError=true;
        }

        return json_encode(['result' => $bolError ? 'KO':'OK']);
    }

    /**
     * Generacion de reoprte pdf
     * @return mixed
     */
    public function actionPrintRegister($id = null,$code='')
    {
        if (empty($id)) {
            return false;
        }
        // get Data
        $newObjRegistro = new Registro();
        $newObjRegistro->id = $id;
        $arrParams = $newObjRegistro->getInfoRegistro($id);
        $arrParams['code'] = $code;
        // get your HTML raw content without any layouts or scripts
        $content = $newObjRegistro->getHtmlAceptacion($arrParams);
        $strSetFooter = Yii::$app->params['adminEmail'].' - '.Yii::$app->params['senderName'];
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'filename' => 'MOV_'.$id.'.pdf',
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
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
            'options' => ['title' => Yii::t('app', 'Información movimientos de llave')],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader' => ['{PAGENO}'],
                'SetFooter' => [$strSetFooter],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * Devuelve un listado de comerciales en funcion del nombre especificado
     * @param bool $q
     * @return array
     */
    public function actionFindComercial($q = false)
    {
        $rows = Comerciales::find()->select('id,nombre')->distinct()
            ->where(['like', 'nombre', $q])->andWhere(['estado' => 1])->orderBy('nombre ASC')->asArray()->all();
        return json_encode($rows);
    }

    /**
     * Devuelve un listado de comunidades en funcion del nombre especificado
     * @param bool $q
     * @return array
     */
    public function actionFindComunidad($q = false)
    {
        $rows = Comunidad::find()->select(["id","CONCAT(nomenclatura, ' - ', nombre) as nombre"])->distinct()
            ->where(['OR', ['like','nombre',$q],['like','nomenclatura',$q] ])
            ->andWhere(['estado' => 1])->orderBy('comunidad.nombre ASC')->asArray()->all();
        return json_encode($rows);
    }

    /**
     * Devuelve un listado de propietarios en función del nombre especificado
     * @param bool $q
     * @return array
     */
    public function actionFindPropietarios($q = false)
    {
        $queryWhere = empty($q)?"":" WHERE (pp.nombre_propietario like '%".$q."%' OR pp.nombre_representante like '%".$q."%')";
        $query = "SELECT pp.id, (CASE
                                    WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                                    WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                                    ELSE NULL
                                END) as nombre 
                    FROM propietarios pp ".$queryWhere." 
                    ORDER BY nombre_propietario ASC, nombre_representante ASC ";
        $rows = Yii::$app->db
            ->createCommand($query)
            ->queryAll();

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