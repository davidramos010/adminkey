<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Propietarios;

/**
 * PropietariosSearch represents the model behind the search form of `app\models\Propietarios`.
 */
class PropietariosSearch extends Propietarios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tipo_documento_propietario', 'tipo_documento_representante'], 'integer'],
            [['nombre_propietario', 'documento_propietario', 'nombre_representante', 'documento_representante', 'direccion', 'cod_postal', 'poblacion', 'telefono', 'movil', 'email', 'observaciones'], 'safe'],
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
        $query = Propietarios::find();

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
            'tipo_documento_propietario' => $this->tipo_documento_propietario,
            'tipo_documento_representante' => $this->tipo_documento_representante,
        ]);

        $query->andFilterWhere(['like', 'nombre_propietario', $this->nombre_propietario])
            ->andFilterWhere(['like', 'documento_propietario', $this->documento_propietario])
            ->andFilterWhere(['like', 'nombre_representante', $this->nombre_representante])
            ->andFilterWhere(['like', 'documento_representante', $this->documento_representante])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'poblacion', $this->poblacion])
            //->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'telefono', $this->movil])
            ->andFilterWhere(['like', 'movil', $this->movil])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones]);

        return $dataProvider;
    }
}
