<?php

namespace app\models;

use app\models\LlaveStatus;
use app\models\Llave;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * LlaveStatusSearch represents the model behind the search form of `app\models\LlaveStatus`.
 */
class LlaveStatusSearch extends LlaveStatus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_llave'], 'integer'],
            [['status', 'fecha'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LlaveStatus::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_llave' => $this->id_llave,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @param int $numDias
     * @return ActiveDataProvider
     */
    public function searchBetween(array $params, int $numDias = 15): ActiveDataProvider
    {
        $fecha_actual = date("d-m-Y");
        $query = LlaveStatus::find()->alias('ls');
        $query->select(["ls.*" ]);
        $query->innerJoin('llave ll','ls.id_llave=ll.id and ll.activa=1');
        $query->innerJoin('llave_status ls2','ls2.id = ls.id and ls.id_llave = ls2.id_llave and ls2.id = (
           SELECT st.id
           FROM llave_status st 
           WHERE st.id_llave = ls.id_llave
           ORDER BY st.fecha DESC limit 1
        )');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Filtro de fechas
        switch ($numDias){
            case 5:
                $strFechaConsultaIni = date("Y-m-d 23:00:00",strtotime($fecha_actual."- ".$numDias." days"));
                $strFechaConsultaFin = date("Y-m-d 00:00:00",strtotime($fecha_actual."- ".($numDias+5)." days"));
                $query->where(['between', 'ls.fecha', $strFechaConsultaFin, $strFechaConsultaIni]);
                break;

            case 10:
                $strFechaConsultaIni = date("Y-m-d 23:00:00",strtotime($fecha_actual."- ".($numDias+1)." days"));
                $strFechaConsultaFin = date("Y-m-d 00:00:00",strtotime($fecha_actual."- ".($numDias+5)." days"));
                $query->where(['between', 'ls.fecha', $strFechaConsultaFin, $strFechaConsultaIni]);
                break;

            case 15:
            default:
                $strFechaConsultaIni = date("Y-m-d 23:00:00",strtotime($fecha_actual."- ".$numDias." days"));
                $query->where(['<=', 'ls.fecha', $strFechaConsultaIni]);
                break;
        }

        $query->andWhere(['like', 'ls.status', $this->status]);
        return $dataProvider;
    }

    /**
     * Sub consulta para listar clientes con llaves en prestamo
     * @return ActiveDataProvider
     */
    public function searchDataByCliente(bool $bolAllReg = false)
    {
        $subQuery = Llave::find()->alias('l');
        $subQuery->select(["COUNT(1) AS total, l.id_comunidad, c.nombre as descripcion,
                            (SELECT COUNT(ls.id_llave) as numSalida
                                FROM llave_status ls
                                INNER JOIN  llave_status ls2 ON (ls2.id = ls.id and ls2.id_llave = ls.id_llave and ls2.id = (
                                                                SELECT st.id FROM llave_status st WHERE st.id_llave = ls.id_llave ORDER BY st.fecha DESC limit 1
                                                                ))
                                INNER JOIN llave l2 on (ls.id_llave = l2.id )
                                WHERE ls.status ='S' and l2.id_comunidad = l.id_comunidad
                            ) AS salida,c.nomenclatura as nomenclatura" ]);
        $subQuery->leftJoin('comunidad c','l.id_comunidad = c.id ');
        $subQuery->where(['l.activa'=>1,'l.id_tipo'=>1]);
        $subQuery->andWhere(['IS NOT', 'l.id_comunidad', NULL]);
        $subQuery->groupBy('l.id_comunidad');
        // Ordenamos por fecha de creación por defecto
        if (!isset($params['sort'])) {
            $subQuery->orderBy('salida DESC,c.nombre ASC');
        }
        // Select All
        $query = (new Query())->select('r.total as total,r.id_comunidad, r.descripcion, r.salida as salida, r.nomenclatura as nomenclatura')->from(['r' => $subQuery]);
        if(!$bolAllReg){
            $query->where(['>','r.salida',0]);
        }
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    public function searchDataByPropietario(bool $bolAllReg = false)
    {
        $subQuery = Llave::find()->alias('l');
        $subQuery->select(["COUNT(1) AS total, l.id_propietario,
                            ( CASE
                                WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                                WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                                ELSE NULL
                              END) AS descripcion,
                            ( SELECT COUNT(ls.id_llave) as numSalida
                                FROM llave_status ls
                                INNER JOIN  llave_status ls2 ON (ls2.id = ls.id and ls2.id_llave = ls.id_llave and ls2.id = (
                                 SELECT st.id FROM llave_status st WHERE st.id_llave = ls.id_llave ORDER BY st.fecha DESC limit 1
                                ))
                                INNER JOIN llave l2 on (ls.id_llave = l2.id )
                                WHERE ls.status ='S' and l2.id_propietario = l.id_propietario
                            ) AS salida" ]);
        $subQuery->leftJoin('propietarios p','l.id_propietario  = p.id');

        $subQuery->where(['l.activa'=>1,'l.id_tipo'=>2]);
        $subQuery->andWhere(['IS NOT', 'l.id_propietario', NULL]);
        $subQuery->groupBy('l.id_propietario');
        // Ordenamos por fecha de creación por defecto
        if (!isset($params['sort'])) {
            $subQuery->orderBy('salida DESC,p.nombre_propietario ASC, p.nombre_representante');
        }

        // Select All
        $query = (new Query())->select('r.*')->from(['r' => $subQuery]);
        if(!$bolAllReg){
            $query->where(['>','r.salida',0]);
        }
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;

    }
}
