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
            [['entrada', 'salida', 'observacion', 'codigo'], 'safe'],
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
            'u.username'
        ]);

        $query->leftJoin('llave ll','r.id_llave = ll.id');
        $query->leftJoin('User u','r.id_user = u.id');
        $query->leftJoin('comunidad co','ll.id_comunidad = co.id');
        $query->leftJoin('comerciales cm','r.id_comercial = cm.id');
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
            'entrada' => $this->entrada,
            'salida' => $this->salida,
            'll.codigo' => $this->codigo,
            'u.username' => $this->username,
            'co.nombre' => $this->comunidad,
            'cm.nombre' => $this->comercial,
        ]);

        $query->andFilterWhere(['like', 'observacion', $this->observacion]);
        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
