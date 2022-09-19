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
            [['entrada', 'salida', 'observacion', 'codigo', 'username','comunidad','comercial','propietarios','clientes','llaves','llaves_e','llaves_s'], 'safe'],
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
            'll.codigo',
            'u.username',
            'cm.nombre as comercial',
            "( SELECT group_concat(llv.codigo) FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id
              )) AS llaves",
            "( SELECT group_concat(llv.codigo) FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id and st.status = 'E'
              )) AS llaves_e",
            "( SELECT group_concat(llv.codigo) FROM llave llv WHERE llv.id IN (  
                   SELECT st.id_llave FROM llave_status st WHERE st.id_registro = r.id and st.status = 'S'
              )) AS llaves_s",
            "( SELECT group_concat(c.nombre) FROM comunidad c WHERE id IN (  
               SELECT DISTINCT l.id_comunidad FROM llave l 
               INNER JOIN llave_status sta ON ( sta.id_llave=l.id ) 
               WHERE sta.id_registro = r.id
            ) ) AS clientes",
            "( SELECT group_concat(CASE
                                        WHEN p.nombre_propietario IS NOT NULL THEN p.nombre_propietario
                                        WHEN p.nombre_representante IS NOT NULL THEN p.nombre_representante
                                        ELSE NULL
                                    END) FROM propietarios p WHERE p.id IN (
                SELECT DISTINCT l.id_propietario FROM llave l 
                       INNER JOIN llave_status stb ON ( stb.id_llave=l.id ) 
                       WHERE stb.id_registro = r.id
                    ) ) AS propietarios"
        ]);
        $query->leftJoin('llave ll','r.id_llave = ll.id');
        $query->leftJoin('User u','r.id_user = u.id');
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
            'u.username' => $this->username,
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
        $query->andFilterWhere(['like', 'observacion', $this->observacion]);
        $query->andFilterWhere(['like', 'll.codigo', $this->codigo]);

        // ====================================================== andHaving
        if($this->llaves){
            $query->andHaving("llaves like :L",[':L' => "%".$this->llaves."%"]);
        }

        if($this->clientes){
            $query->andHaving("clientes like :C",[':C' => "%".$this->clientes."%"]);
        }

        if($this->propietarios){
            $query->andHaving("propietarios like :P",[':P' => "%".$this->propietarios."%"]);
        }

        $query->orderBy('r.id DESC');

        return $dataProvider;
    }
}
