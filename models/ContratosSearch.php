<?php

namespace app\models;

use app\models\Contratos;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ContratosLog;

/**
 * ContratosSearch represents the model behind the search form of `app\models\Contratos`.
 */
class ContratosSearch extends Contratos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'estado', 'id_user'], 'integer'],
            [['nombre', 'descripcion', 'fecha_ini', 'fecha_fin', 'created'], 'safe'],
            [['fecha','llaves', 'propietario','propietario','cliente'], 'safe'],
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
        $query = Contratos::find();
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
            'estado' => $this->estado,
            'id_user' => $this->id_user,
            'created' => $this->created,
        ]);

        if($this->fecha_ini){
            $query->andFilterWhere([
                'LIKE', 'fecha_ini', Date('Y-m-d', strtotime($this->fecha_ini))
            ]);
        }

        if($this->fecha_fin){
            $query->andFilterWhere([
                'LIKE', 'fecha_fin', Date('Y-m-d', strtotime($this->fecha_fin))
            ]);
        }

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search_log($params)
    {
        $query = ContratosLog::find()->alias('cl');
        $query->select([
            "cl.*",
            "cn.nombre as nombre",
            "cn.estado as estado",
            "( SELECT group_concat(llb.codigo) FROM llave llb WHERE llb.id IN (  
                   SELECT clg.id_llave FROM contratos_log_llave clg WHERE clg.id_contrato_log = cl.id
              )) AS llaves ",
            "( SELECT group_concat(c.nombre) FROM comunidad c WHERE id IN (  
               SELECT DISTINCT l.id_comunidad FROM llave l 
               INNER JOIN contratos_log_llave cll ON ( cll.id_llave=l.id ) 
               WHERE cll.id_contrato_log = cl.id
            ) ) AS cliente",
            "( SELECT group_concat(CASE
                                        WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                                        WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                                        ELSE NULL
                                    END) FROM propietarios p WHERE p.id IN (  
               SELECT DISTINCT l.id_propietario FROM llave l 
               INNER JOIN contratos_log_llave cll ON ( cll.id_llave=l.id ) 
               WHERE cll.id_contrato_log = cl.id
            ) ) AS propietario"
        ]);

        $query->leftJoin('contratos cn','cl.id_contrato = cn.id');

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
            'cn.estado' => $this->estado,
            'cn.nombre' => $this->nombre
        ]);

        if($this->fecha){
            $strtotime_fecha = Date('Y-m-d',strtotime($this->fecha));
            $query->andFilterWhere(['between', 'cl.fecha',  $strtotime_fecha." 00:00", $strtotime_fecha." 23:59" ]);
        }

        //filtrar los que no se han eliminado
        $query->andWhere(['deleted' => null]);

        if($this->llaves){
            $query->andHaving("llaves like :L",[':L' => "%".$this->llaves."%"]);
        }

        if($this->cliente){
            $query->andHaving("cliente like :C",[':C' => "%".$this->cliente."%"]);
        }

        if($this->propietario){
            $query->andHaving("propietario like :P",[':P' => "%".$this->propietario."%"]);
        }

        $query->orderBy(['cl.id'=>SORT_DESC]);


        return $dataProvider;
    }
}
