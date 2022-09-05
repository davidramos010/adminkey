<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Registro;

/**
 * RegistroSearch represents the model behind the search form of `app\models\Registro`.
 */
class RegistroSearch extends Registro
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_user', 'id_llave'], 'integer'],
            [['entrada', 'salida', 'observacion', 'codigo', 'username','comunidad','comercial','nombre_propietario'], 'safe'],
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

        $query = Registro::find()->alias('r');

        $query->select([
            'r.*',
            'co.nombre as comunidad',
            'cm.nombre as comercial',
            'll.codigo',
            'u.username',
            "(CASE
                WHEN pp.nombre_propietario IS NOT NULL THEN pp.nombre_propietario
                WHEN pp.nombre_representante IS NOT NULL THEN pp.nombre_representante
                ELSE NULL
            END) as nombre_propietario"
        ]);

        $query->leftJoin('llave ll','r.id_llave = ll.id');
        $query->leftJoin('User u','r.id_user = u.id');
        $query->leftJoin('comunidad co','ll.id_comunidad = co.id');
        $query->leftJoin('comerciales cm','r.id_comercial = cm.id');
        $query->leftJoin('propietarios pp','ll.id_propietario = pp.id');
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
            'id_user' => $this->id_user,
            'id_llave' => $this->id_llave,
            'u.username' => $this->username,
            'co.nombre' => $this->comunidad,
            'cm.nombre' => $this->comercial,
        ]);

        if($this->salida){
            $query->andFilterWhere([
                'LIKE', 'salida', Date('Y-m-d', strtotime($this->salida))
            ]);
        }

        if($this->entrada){
            $query->andFilterWhere([
                'LIKE', 'entrada', Date('Y-m-d', strtotime($this->entrada))
            ]);
        }

        // ======================================================
        // Propietarios
        if(!empty($this->nombre_propietario)){
            $query->andWhere(['or',
                ['like', 'pp.nombre_propietario', $this->nombre_propietario],
                ['like', 'pp.nombre_representante', $this->nombre_propietario]]);
        }

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);
        $query->andFilterWhere(['like', 'll.codigo', $this->codigo]);
        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
