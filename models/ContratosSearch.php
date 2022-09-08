<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contratos;

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
}
