<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['username', 'name', 'password', 'authKey', 'accessToken','nombres','apellidos','telefono','perfil','estado'], 'safe'],
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
        $query = User::find()->alias('us');
        $query->select([
            'us.*',
            'in.nombres as nombres',
            'in.apellidos as apellidos',
            'in.telefono as telefono',
            'in.estado as estado',
            'UPPER(pf.nombre) as perfil',
        ]);

        $query->leftJoin('User_info in','in.id_user = us.id');
        $query->leftJoin('perfiles_usuario pu','pu.id_user = us.id');
        $query->leftJoin('perfiles pf','pf.id = pu.id_perfil');
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
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);

        $query->andFilterWhere([
            'in.nombres' => $this->nombres,
            'in.apellidos' => $this->apellidos,
            'in.telefono' => $this->telefono,
            'pf.nombre' => $this->perfil,
        ]);

        $query->andWhere(['in.estado'=> 1]);
        $query->orderBy('us.name ASC');

        return $dataProvider;
    }
}
