<?php

namespace app\controllers;

use app\models\Codipostal;
use app\models\Comunidad;
use app\models\ContratosLog;
use app\models\ContratosLogLlave;
use app\models\Llave;
use app\models\LlaveSearch;
use app\models\Propietarios;
use app\models\util;
use PhpOffice\PhpWord\TemplateProcessor;
use Yii;
use app\models\Contratos;
use app\models\ContratosSearch;
use yii\db\Exception;
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
        $dataProvider = $searchModel->search_log(Yii::$app->request->queryParams);

        return $this->render('generar_list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Contratos models.
     * @return mixed
     */
    public function actionCreateContrato($idContratoLog=null)
    {
        $model = new Contratos();
        $modelLog = (empty($idContratoLog))?new ContratosLog():ContratosLog::findOne(['id'=>$idContratoLog]);

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
     * Deletes an existing Contratos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteContrato(int $idContratoLog)
    {
        $bolResultModel = ContratosLog::setDeleteContratoLog($idContratoLog);

        if($bolResultModel){
            Yii::$app->session->setFlash('success', Yii::t('yii', 'Se ha eliminado correctamente'));
        }else{
            Yii::$app->session->setFlash('error', Yii::t('yii', 'El proceso no finaliza correctamente, vuelva a intentar.'));
        }

        return $this->redirect(['generar-list']);
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

    /**
     * @return bool|\yii\web\Response
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function actionGenerarContrato()
    {
        if (Yii::$app->request->post()) {
            $arrParam = $this->request->post();
            $objLogContrato = (isset($arrParam['ContratosLog']['id']) && !empty($arrParam['ContratosLog']['id'])) ? ContratosLog::findOne(['id' => $arrParam['ContratosLog']['id']]) : new ContratosLog();

            if (!$objLogContrato->isNewRecord && (isset($_FILES['ContratosLog']['name']['copia_firma']) && !empty($_FILES['ContratosLog']['name']['copia_firma']))) {
                //Guardar contrato firmado
                $documentoContrato = UploadedFile::getInstance($objLogContrato, 'copia_firma');
                $objLogContrato->copia_firma = date('Ymdhi') . '_' . $documentoContrato->getBaseName() . '.' . $documentoContrato->getExtension();
                $pathToSave = Yii::getAlias('@webroot') . '/contratos_firmados/' . $objLogContrato->copia_firma;
                $documentoContrato->saveAs($pathToSave);
                if (file_exists($pathToSave) && $objLogContrato->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Almacenado Correctamente!!'));
                    return $this->redirect(['create-contrato', 'idContratoLog' => $objLogContrato->id]);
                }
            } else {
                $arrParam['ContratosLog']['id_usuario'] = Yii::$app->user->identity->id;
                $arrParam['ContratosLog']['parametros'] = str_replace(['[', ']', 'null'], '', $arrParam['ContratosLog']['parametros']);// Limpiar formato
                $objLogContrato->load($arrParam);
            }

            if ($objLogContrato->save()) {
                try {
                    $arrParam = $arrParam['ContratosLog'];
                    $parametros = $arrParam['parametros'];
                    $contrato = $arrParam['id_contrato'];
                    $observacion = $arrParam['observacion'];
                    //guardar parametros
                    $arrParam['ContratosLogLlave'] = explode(',', $parametros);
                    if (count($arrParam['ContratosLogLlave'])) {
                        ContratosLogLlave::deleteAll(['id_contrato_log' => $objLogContrato->id]);
                    }
                    foreach ($arrParam['ContratosLogLlave'] as $keyLlave) {
                        if (empty($keyLlave))
                            continue;

                        $newObjContratosLogLlave = new ContratosLogLlave();
                        $newObjContratosLogLlave->load(['ContratosLogLlave' => ['id_llave' => (int)$keyLlave, 'id_contrato_log' => (int)$objLogContrato->id]]);
                        $newObjContratosLogLlave->save();
                    }

                    Yii::$app->session->setFlash('success', Yii::t('yii', 'Almacenado Correctamente!!'));
                    return $this->redirect(['create-contrato', 'idContratoLog' => $objLogContrato->id]);
                } catch (ErrorException $e) {
                    Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.'));
                }
            } else {
                Yii::$app->session->setFlash('error', Yii::t('yii', 'No se puede almacenar. Valide el formulario he intente nuevamente.'));
            }
        }
        return $this->actionCreateContrato();
    }

    /**
     * @return void|\yii\console\Response|\yii\web\Response
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function actionDescargarContrato()
    {

        $arrParam = $this->request->get();
        $numIdContratoLog = $arrParam['id'];
        $objContratoLog = ContratosLog::findOne(['id'=>$numIdContratoLog]);
        $objContrato = Contratos::findOne(['id' => $objContratoLog->id_contrato]);
        $objContratoLogLlave = ContratosLogLlave::find()->where(['id_contrato_log'=>$objContratoLog->id])->all();

        $rutaContrato = Yii::getAlias('@webroot') . '/plantillas/' . $objContrato->documento;
        $templateProcessor = new TemplateProcessor($rutaContrato);

        //Carga informacion de llaves y llaves
        $numLLaves = count($objContratoLogLlave);
        $arrLlaves = [];
        if($numLLaves){
            try {
                //$templateProcessor->cloneRow('codigo_llave', $numLLaves);
            }catch (Exception $exception ){
                // no aplica
            }

            $numRowLlave = 0;
            foreach ($objContratoLogLlave as $llave_key){
                $numRowLlave++;
                $arrLlaves[] = $llave_key->id_llave;
                $templateProcessor->setValue('codigo_llave#'.$numRowLlave, htmlspecialchars($llave_key->llave->codigo));
                $templateProcessor->setValue('descripcion_llave#'.$numRowLlave, htmlspecialchars($llave_key->llave->descripcion));
            }
        }

        // Consultar Cliente
        $arrNombreClientes = [];
        $infoCliente = Comunidad::find()->alias('c')->select('c.nombre')->distinct();
        $infoCliente->innerJoin('llave l','l.id_comunidad = c.id');
        $infoCliente->where(['IN','l.id',$arrLlaves])->orderBy('c.nombre asc');
        $infoCliente = $infoCliente->all();
        if(count($infoCliente)){
            foreach ($infoCliente as $keyCliente){
                $arrNombreClientes[] = strtoupper($keyCliente->nombre);
            }
        }

        // Consultar Propietario
        $arrPropietarioNombre = null;
        $arrPropietarioNombreIdentificacion = null;
        $infoPropietario = Propietarios::find()->alias('p')->select('(CASE
                                                                                        WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                                                                                        WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                                                                                        ELSE NULL END) as nombre_propietario,
                                                                                    (CASE
                                                                                        WHEN p.tipo_documento_propietario IS NOT NULL THEN p.tipo_documento_propietario
                                                                                        WHEN p.tipo_documento_representante IS NOT NULL THEN p.tipo_documento_representante
                                                                                        ELSE NULL END) as tipo_documento_propietario,
                                                                                    (CASE
                                                                                        WHEN p.documento_propietario IS NOT NULL THEN p.documento_propietario
                                                                                        WHEN p.documento_representante IS NOT NULL THEN p.documento_representante
                                                                                        ELSE NULL END) as documento_propietario')->distinct();
        $infoPropietario->innerJoin('llave l', 'l.id_propietario= p.id');
        $infoPropietario = $infoPropietario->where(['IN', 'l.id', $arrLlaves])->orderBy('p.nombre_propietario asc')->all();
        if (count($infoPropietario)) {
            foreach ($infoPropietario as $keyPropietario) {
                $strNombrePropietario = (!empty($keyPropietario->nombre_propietario)) ? strtoupper($keyPropietario->nombre_propietario) : '';
                $strDocumentoPropietario = (!empty($keyPropietario->tipo_documento_propietario)) ? Propietarios::getTipoDocmento($keyPropietario->tipo_documento_propietario) : '';

                $arrPropietarioNombre[] = $strNombrePropietario;
                $arrPropietarioNombreIdentificacion[] = $strDocumentoPropietario . ' ' . $keyPropietario->documento_propietario;
            }
        }
        // ---------------------
        $templateProcessor->setValue('llaves', $objContratoLog->parametros);
        $templateProcessor->setValue('observaciones', $objContratoLog->observacion);
        $templateProcessor->setValue('fecha_actual', date('d/m/Y H:i:s'));
        $templateProcessor->setValue('fecha_contrato', util::getDateTimeFormatedSqlToUser($objContratoLog->fecha) );
        $templateProcessor->setValue('cliente', implode(',',$arrNombreClientes) );
        $templateProcessor->setValue('propietario', implode(',',$arrPropietarioNombre));
        $templateProcessor->setValue('propietario_identificacion', implode(',',$arrPropietarioNombreIdentificacion));

        $fileName = date('Ymd') . '_' . $objContrato->documento;
        $pathToSave = tempnam(sys_get_temp_dir(), $fileName);
        $templateProcessor->saveAs($pathToSave);

        if (file_exists($pathToSave)) {
            return Yii::$app->response->sendFile($pathToSave, date('Ymd') . '_' . $objContrato->documento, ['inline' => false]);
        }

    }


}
