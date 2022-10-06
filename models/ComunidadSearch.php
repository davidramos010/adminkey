<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Comunidad;

/**
 * ComunidadSearch represents the model behind the search form of `app\models\comunidad`.
 */
class ComunidadSearch extends Comunidad
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','estado'], 'integer'],
            [['nombre','cod_postal','poblacion', 'direccion', 'documento', 'telefono1', 'telefono2', 'contacto', 'nomenclatura'], 'safe'],
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
        $query = Comunidad::find();

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
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['estado' => $this->estado ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'telefono1', $this->telefono1])
            ->andFilterWhere(['like', 'telefono2', $this->telefono2])
            ->andFilterWhere(['like', 'contacto', $this->contacto])
            ->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'poblacion', $this->poblacion])
            ->andFilterWhere(['like', 'nomenclatura', $this->nomenclatura]);

        $query->orderBy(['nombre'=>SORT_ASC]);

        return $dataProvider;
    }
}
