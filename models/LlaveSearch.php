<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

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
            [['id', 'id_comunidad', 'id_tipo', 'copia', 'activa','alarma','id_propietario','facturable'], 'integer'],
            [['codigo', 'descripcion', 'observacion','codigo_alarma','llaveLastStatus','nombre_propietario','cliente_comunidad'], 'safe'],
            [['nomenclatura'], 'string',  'max' => 4],

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
            "cc.nombre as cliente_comunidad",
            "ls.status as llaveLastStatus",
            "(CASE
                WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                ELSE NULL
            END) as nombre_propietario",

        ]);
        $query->leftJoin('llave_status ls','ls.id_llave = ll.id and ls.id = (
           SELECT st.id
           FROM llave_status st 
           WHERE st.id_llave = ll.id
           ORDER BY st.fecha DESC limit 1
        )');

        $query->leftJoin('propietarios pp','ll.id_propietario = pp.id');
        $query->leftJoin('comunidad cc','ll.id_comunidad = cc.id');

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
            'll.activa' =>  $this->activa,
            'll.alarma' =>  $this->alarma,
            'll.facturable' =>  $this->facturable
        ]);

        $query->andFilterWhere(['like', 'll.codigo', $this->codigo])
            ->andFilterWhere(['like', 'll.descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'cc.nombre', $this->cliente_comunidad])
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


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchManual($params)
    {
        $query = Llave::find()->alias('ll');
        $query->orderBy('ll.id DESC');
        // add conditions that should always apply here
        $query->select([
            "ll.*",
            "cc.nombre as cliente_comunidad",
            "ls.status as llaveLastStatus",
            "(CASE
                WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                ELSE NULL
            END) as nombre_propietario",
            "(CASE
                WHEN cc.nomenclatura IS NOT NULL THEN cc.nomenclatura
                WHEN pp.id IS NOT NULL THEN pp.id
                ELSE NULL
              END) as nomenclatura",
            "cm.nombre as comercial",
            "rg.nombre_responsable as responsable",
            "rg.observacion as observacion",
        ]);
        $query->leftJoin('llave_status ls','ls.id_llave = ll.id and ls.id = (
           SELECT st.id
           FROM llave_status st 
           WHERE st.id_llave = ll.id
           ORDER BY st.fecha DESC limit 1
        )');

        $query->leftJoin('propietarios pp','ll.id_propietario = pp.id');
        $query->leftJoin('comunidad cc','ll.id_comunidad = cc.id');
        $query->leftJoin('registro rg','ls.status is not null and ls.id_registro = rg.id');
        $query->leftJoin('comerciales cm','rg.id is not null and rg.id_comercial = cm.id');

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
            'll.id_propietario' => $this->id_propietario,
            'll.id_tipo' => $this->id_tipo,
            'll.copia' => $this->copia,
            'll.activa' =>  $this->activa,
            'll.alarma' =>  $this->alarma,
            'll.facturable' =>  $this->facturable
        ]);

        $query->andFilterWhere(['like', 'll.codigo', $this->codigo])
            ->andFilterWhere(['like', 'll.descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'cc.nombre', $this->cliente_comunidad])
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
        // ======================================================
        // Nomenclatura
        if(!empty($this->nomenclatura)){
            $strFindNomenclatura = str_replace(['C','P'],'',$this->nomenclatura);
            $strFindNomenclatura = str_pad($strFindNomenclatura, 3, "0", STR_PAD_LEFT);
            $query->andWhere(['or',
                ['like', 'll.codigo', 'C'.$strFindNomenclatura. '%', false],
                ['like', 'll.codigo', 'P'.$strFindNomenclatura. '%', false ]]);
        }

        $query->limit(100);

        return $query->all();
    }
}
