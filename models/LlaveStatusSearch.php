<?php

namespace app\models;

use app\models\LlaveStatus;
use app\models\Llave;
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
    public function searchBetween(array $params, int $numDias = 15)
    {
        $fecha_actual = date("d-m-Y");
        $query = LlaveStatus::find()->alias('ls');
        $query->select(["ls.*" ]);
        $query->innerJoin('( SELECT MAX(id) AS indice ,id_llave FROM llave_status  GROUP BY id_llave  ) lsb','lsb.indice = ls.id');
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
                $query->where(['between', 'fecha', $strFechaConsultaFin, $strFechaConsultaIni]);
                break;

            case 10:
                $strFechaConsultaIni = date("Y-m-d 23:00:00",strtotime($fecha_actual."- ".($numDias+1)." days"));
                $strFechaConsultaFin = date("Y-m-d 00:00:00",strtotime($fecha_actual."- ".($numDias+5)." days"));
                $query->where(['between', 'fecha', $strFechaConsultaFin, $strFechaConsultaIni]);
                break;

            case 15:
            default:
                $strFechaConsultaIni = date("Y-m-d 23:00:00",strtotime($fecha_actual."- ".$numDias." days"));
                $query->where(['<=', 'fecha', $strFechaConsultaIni]);
                break;

        }

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    public function searchDataByCliente()
    {
        $query = Llave::find()->alias('l');
        $query->select(["COUNT(1) AS total, l.id_comunidad, c.nombre as descripcion,
                            (SELECT COUNT(ls.id_llave) as numSalida
                                FROM llave_status ls
                                INNER JOIN ( SELECT MAX(id) AS indice ,id_llave FROM llave_status GROUP BY id_llave  ) AS lsb ON ( lsb.indice = ls.id )
                                INNER JOIN llave l2 on (ls.id_llave = l2.id )
                                WHERE ls.status ='S' and l2.id_comunidad = l.id_comunidad
                            ) AS salida" ]);
        $query->leftJoin('comunidad c','l.id_comunidad = c.id ');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where(['l.activa'=>1]);
        $query->andWhere(['IS NOT', 'l.id_comunidad', NULL]);
        $query->groupBy('l.id_comunidad');
        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    public function searchDataByPropietario()
    {
        $query = Llave::find()->alias('l');
        $query->select(["COUNT(1) AS total, l.id_propietario, p.nombre_propietario AS descripcion,
                            (
                            SELECT COUNT(ls.id_llave) as numSalida
                                FROM llave_status ls
                                INNER JOIN ( SELECT MAX(id) AS indice ,id_llave FROM llave_status GROUP BY id_llave  ) AS lsb ON ( lsb.indice = ls.id )
                                INNER JOIN llave l2 on (ls.id_llave = l2.id )
                                WHERE ls.status ='S' and l2.id_propietario = l.id_propietario
                            ) AS salida" ]);
        $query->leftJoin('propietarios p','l.id_propietario  = p.id');
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->where(['l.activa'=>1]);
        $query->andWhere(['IS NOT', 'l.id_propietario', NULL]);
        $query->groupBy('l.id_propietario');

        return $dataProvider;
    }
}
