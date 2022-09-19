<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Llave;

/**
 * LlaveSearch represents the model behind the search form of `app\models\Llave`.
 */
class LlaveSearch extends Llave
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_comunidad', 'id_tipo', 'copia', 'activa','alarma','id_propietario'], 'integer'],
            [['codigo', 'descripcion', 'observacion','codigo_alarma','llaveLastStatus','nombre_propietario'], 'safe'],
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
        $query = Llave::find()->alias('ll');
        $query->orderBy('ll.id DESC');
        // add conditions that should always apply here
        $query->select([
            "ll.*",
            "ls.status as llaveLastStatus",
            "(CASE
                WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                ELSE NULL
            END) as nombre_propietario"
        ]);
        $query->leftJoin('llave_status ls','ls.id_llave = ll.id and ls.id = (
           SELECT MAX(st.id) 
           FROM llave_status st 
           WHERE st.id_llave = ll.id
        )');

        $query->leftJoin('propietarios pp','ll.id_propietario = pp.id');

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
            'll.id' => $this->id,
            'll.id_comunidad' => $this->id_comunidad,
            'll.id_tipo' => $this->id_tipo,
            'll.copia' => $this->copia,
            'll.activa' => $this->activa,
            'll.alarma' => $this->alarma,
        ]);

        $query->andFilterWhere(['like', 'll.codigo', $this->codigo])
            ->andFilterWhere(['like', 'll.descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'll.observacion', $this->observacion]);

        // ======================================================
        // find satatus
        if($this->llaveLastStatus=='E'){
            $query->andWhere(['or',
                ['ls.status'=> $this->llaveLastStatus],
                ['IS', 'ls.status', NULL]]);
        }

        if($this->llaveLastStatus=='S'){
            $query->andFilterWhere(['ls.status'=> $this->llaveLastStatus]);
        }

        // ======================================================
        // Propietarios
        if(!empty($this->nombre_propietario)){
            $query->andWhere(['or',
                ['like', 'pp.nombre_propietario', $this->nombre_propietario],
                ['like', 'pp.nombre_representante', $this->nombre_propietario]]);
        }

        return $dataProvider;
    }
}
