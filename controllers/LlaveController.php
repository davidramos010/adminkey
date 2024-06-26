<?php

namespace app\controllers;

use app\models\Comunidad;
use app\models\Llave;
use app\models\LlaveNotas;
use app\models\LlaveSearch;
use app\models\LlaveStatus;
use app\models\LlaveStatusSearch;
use app\models\Propietarios;
use app\models\TipoLlave;
use app\models\util;
use kartik\helpers\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Yii;
use yii\filters\AccessControl;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
     * @throws Exception
     */
    public function actionReport(): string
    {
        $searchModel = new LlaveSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->pagination->pageSize = 50;
        $arrParamsGet = $this->request->get() ?? null;
        if ($arrParamsGet && isset($arrParamsGet['report']) && $arrParamsGet['report'] == 'all') {
            $this->fnGenerarReportConsolidadoExcel();
        }

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
        $modelLlaveNota = new LlaveNotas();
        $modelLlaveNota->id_user = Yii::$app->user->identity->username;
        $modelLlaveNota->created = date('Y/m/d H:i');

        $model = $this->findModel($id);
        // definición de la variable nomenclatura
        $model->nomenclatura = $model->getNomenclatura();

        return $this->render('view', [
            'model' => $model,
            'modelNota' => (object) LlaveNotas::find()->where(['id_llave'=>$id,'delete'=>0])->orderBy('id DESC')->all(),
            'llaveNota' => $modelLlaveNota
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
        $modelNotas = new LlaveNotas();
        if ($this->request->isPost) {
            $arrParamsPost = $this->request->post();
            if ($model->load($arrParamsPost) && $model->validate()) {
                $strNota = isset($arrParamsPost['LlaveNotas']['nota']) && !empty($arrParamsPost['LlaveNotas']['nota']) ? trim($arrParamsPost['LlaveNotas']['nota']) : '';
                $model->codigo = $strCodBase = $model->nomenclatura.'-'.$model->codigo;
                $model->codigo .= ($model->copia>1)?'.1':'';
                $model->save();
                $modelNotas->setNewNota( $model->id , $strNota);

                if((int) $model->copia > 1){
                    $numContador = 1;
                    while ((int) $model->copia>$numContador){
                        $numContador++;
                        $modelClone = new Llave();
                        $modelClone->attributes = $model->attributes;
                        $modelClone->codigo = $strCodBase.'.'.$numContador;
                        $modelClone->save();
                        $modelNotas->setNewNota( $modelClone->id , $strNota);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'modelNota' => $modelNotas,
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
        $modelLlaveNota = new LlaveNotas();
        $modelLlaveNota->id_user = Yii::$app->user->identity->username;
        $modelLlaveNota->created = date('Y/m/d H:i');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $model->codigo = $model->nomenclatura.'-'.$model->codigo;
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        // definición de la variable nomenclatura
        $model->nomenclatura = $model->getNomenclatura();
        $model->codigo = $model->getCodigoSinNomenclatura();

        return $this->render('update', [
            'model' => $model,
            'llaveNota' => $modelLlaveNota,
            'modelNota' => (object) LlaveNotas::find()->where(['id_llave'=>$id,'delete'=>0])->orderBy('id DESC')->all(),
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
        $newNotaLlave = new LlaveNotas();
        $newNotaLlave->setNewNota( $id , 'Eliminación/Inactivación de llave');

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
        if (!empty($comunidad_id)) {
            $objComunidad = Comunidad::findOne(['id' => (int) $comunidad_id]);
            $model = new Llave();
            $model->id_comunidad = (int) $comunidad_id;
            $model->id_tipo = (isset($arrParam['tipo'])) ? (int)$arrParam['tipo'] : null;
            $model->nomenclatura = $objComunidad->nomenclatura;
            $arrInfo['id'] = (string)$model->getNext();
            $arrInfo['nomenclatura'] = $objComunidad->nomenclatura;
            if ($model->id_tipo == 'P' || (int)$model->id_tipo == 2) {
                $arrInfo['nomenclatura'] = str_replace('C', 'P', $objComunidad->nomenclatura);
            }
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
     * @return bool
     */
    public function actionAjaxDelLlaveNota(): bool
    {
        $arrParam = $this->request->post();
        $numIdLlave = $arrParam['numIdLlave'];
        $objLlaveNota = LlaveNotas::findOne(['id' => $numIdLlave]);
        $objLlaveNota->delete = 1;
        return $objLlaveNota->save();
    }

    /**
     * @return bool
     */
    public function actionAjaxSetLlaveNota(): string
    {
        $arrParam = $this->request->post();
        $numIdLlave = (int) $arrParam['numIdLlave'];
        $strNotaLlave = $arrParam['strNota'];
        $newNota = new LlaveNotas();
        return json_encode($newNota->setNewNotaAjax($numIdLlave, $strNotaLlave)) ;
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

    /**
     * Funcion que retorna los atributos de una tipo de llave
     * @return false|string
     */
    public function actionAjaxAddCopiKey()
    {
        $bolError = false;
        $arrParam = $this->request->post();
        $numIdLlave = (int) $arrParam['numIdLlave'];
        $objKey = Llave::findOne(['id'=>$numIdLlave]);
        $objKeyNew = clone $objKey;
        $objKeyNew->id = null;
        $objKeyNew->isNewRecord = true;
        $arrNextCopi = $objKeyNew->getNextCopi();
        $objKeyNew->copia = $arrNextCopi['copia'];
        $objKeyNew->codigo = $arrNextCopi['codigo'];
        if(!$objKeyNew->save()){
            $bolError = true;
            $strMessage = 'No se puede crear la copia. Comuniquese con el administrador';
        }else{
            $strMessage = 'Se crea la copia ('.$objKeyNew->copia.') con código '.$objKeyNew->codigo.' corretamente !!';
        }

        return  json_encode( ['error'=>$bolError,'message'=>$strMessage] );
    }

    /**
     * @return void|\yii\console\Response|\yii\web\Response
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function fnGenerarReportConsolidadoExcel()
    {
        $searchModelStatus = new LlaveStatusSearch();
        $dataProviderCliente = $searchModelStatus->searchDataByCliente(true);
        $dataProviderCliente->pagination->pageSize = 1000;
        $dataProviderPropietario = $searchModelStatus->searchDataByPropietario(true);
        $dataProviderPropietario->pagination->pageSize = 1000;

        $content1[] = ['      '];
        $content1[] = ['','Fecha : '.date('d/m/Y H:i:s')];
        $content1[] = ['','LISTA DE COMUNIDADES'];
        $content1[] = [''];
        $content1[] = ['','NOMENCLATURA', 'COMUNIDAD', 'TOTAL', 'EN-PRESTAMO'];
        $content1[] = [''];
        $arrModelCliente = $dataProviderCliente->getModels();
        $numSumasTotal = 0;
        $numSumasPrestamos = 0;
        if (!empty($arrModelCliente)) {
            foreach ($arrModelCliente as $valueReg) {
                $numSumasTotal += (int) $valueReg['total'];
                $numSumasPrestamos += (int) $valueReg['salida'];
                $content1[] = ['',$valueReg['nomenclatura'], $valueReg['descripcion'], $valueReg['total'], $valueReg['salida']];
            }
        }
        $content1[] = [''];
        $content1[] = ['','','TOTALES',$numSumasTotal,$numSumasPrestamos];

        $spreadsheet = new Spreadsheet();
        // Crear la primera hoja
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('LISTA DE COMUNIDADES');
        $sheet1->setCellValue('B3', 'LISTA DE COMUNIDADES');
        $sheet1->getStyle('B3')->getFont()->setBold(true);
        $sheet1->mergeCells('B3:E3');
        $sheet1->getStyle('B3')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet1->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet1->getStyle('B3')->getFill()->getStartColor()->setARGB('007bff');
        $sheet1->fromArray($content1);
        foreach (range('A', 'E') as $column) {
            $sheet1->getColumnDimension($column)->setAutoSize(true);
        }

        // ==========================================================
        $content2[] = ['      '];
        $content2[] = ['','Fecha : '.date('d/m/Y H:i:s')];
        $content2[] = ['','LISTA DE COMUNIDADES'];
        $content2[] = [''];
        $content2[] = ['','ID', 'PARTICULAR', 'TOTAL', 'EN-PRESTAMO'];
        $content2[] = [''];
        $arrModelPropietario = $dataProviderPropietario->getModels();
        $numSumasTotal = 0;
        $numSumasPrestamos = 0;
        if (!empty($arrModelPropietario)) {
            foreach ($arrModelPropietario as $valueReg) {
                $numSumasTotal += (int) $valueReg['total'];
                $numSumasPrestamos += (int) $valueReg['salida'];
                $content2[] = ['',$valueReg['id_propietario'], $valueReg['descripcion'], $valueReg['total'], $valueReg['salida']];
            }
        }
        $content2[] = [''];
        $content2[] = ['','','TOTALES',$numSumasTotal,$numSumasPrestamos];
        // Crear la segunda hoja
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->setActiveSheetIndex(1);
        $sheet2->setTitle('LISTA DE PARTICULARES');
        $sheet2->setCellValue('B3', 'LISTA DE PARTICULARES');
        $sheet2->getStyle('B3')->getFont()->setBold(true);
        $sheet2->mergeCells('B3:E3');
        $sheet2->getStyle('B3')->getFont()->getColor()->setARGB('FFFFFF');
        $sheet2->getStyle('B3')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet2->getStyle('B3')->getFill()->getStartColor()->setARGB('007bff');
        $sheet2->fromArray($content2);
        foreach (range('A', 'E') as $column) {
            $sheet2->getColumnDimension($column)->setAutoSize(true);
        }

        //$spreadsheet = new Spreadsheet();
        $pathToSave = tempnam(sys_get_temp_dir(), "report_") . '.xlsx';
        //$page = $spreadsheet->getActiveSheet();
        //$page->fromArray($content);
        $writer = new Xlsx($spreadsheet);
        $writer->save($pathToSave);
        if (file_exists($pathToSave)) {
            return Yii::$app->response->sendFile($pathToSave, date('Ymd') . '_reporte_consolidado.xls', ['inline' => false]);
        }
    }
}
